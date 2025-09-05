<?php

namespace App\Support;

use App\Models\MenuOverride;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class MenuBuilder
{
    /**
     * Constrói a árvore do menu a partir das rotas com _menu + overrides do console.
     */
    public static function build(): array
    {
        $routes = Route::getRoutes();
        $items  = [];

        // 1) Coleta itens com _menu definidos nas rotas
        foreach ($routes as $route) {
            $meta = $route->defaults['_menu'] ?? null;
            if (!$meta) {
                continue;
            }

            $name = $route->getName();               // ex.: users.index
            $url  = self::routeUrlOrNull($name);

            $item = [
                'key'        => $meta['key']    ?? $name ?? uniqid('menu_', true),
                'label'      => $meta['label']  ?? $name ?? 'Item',
                'icon'       => $meta['icon']   ?? 'far fa-circle',
                'order'      => $meta['order']  ?? 9999,
                'parent'     => $meta['parent'] ?? null,
                'permission' => $meta['permission'] ?? null,
                'route'      => $name,
                'url'        => $url,
                'children'   => [],
                '_hidden'    => false,          // será ajustado pelo override
                '_ext'       => false,          // link externo (custom_url)
                '_target'    => null,           // _blank se new_tab
            ];

            // Permissão (se informada)
            if (!empty($item['permission']) && !self::can($item['permission'])) {
                continue;
            }

            $items[$item['key']] = $item;
        }

        // 2) Aplica overrides (label, icon, order, hidden, parent_key, link/rota, target)
        self::applyOverrides($items);

        // 3) Pai inexistente → vira raiz (defensivo)
        foreach ($items as &$it) {
            if (!empty($it['parent']) && !isset($items[$it['parent']])) {
                $it['parent'] = null;
            }
        }
        unset($it);

        // 4) Monta a árvore (ignora itens ocultos)
        $tree = [];
        foreach ($items as $key => &$item) {
            if (!empty($item['_hidden'])) {
                continue;
            }

            if (!empty($item['parent']) && isset($items[$item['parent']]) && empty($items[$item['parent']]['_hidden'])) {
                $items[$item['parent']]['children'][] = &$item;
            } else {
                $tree[] = &$item;
            }
        }
        unset($item);

        // 5) Ordena por 'order' em todos os níveis
        $sort = function (&$nodes) use (&$sort) {
            usort($nodes, function ($a, $b) {
                $cmp = ($a['order'] <=> $b['order']);
                return $cmp !== 0 ? $cmp : strcmp($a['label'], $b['label']);
            });
            foreach ($nodes as &$n) {
                if (!empty($n['children'])) {
                    $sort($n['children']);
                }
            }
        };
        $sort($tree);

        return $tree;
    }

    /**
     * Aplica overrides vindos da tabela menu_overrides.
     * - Prioridade: custom_url > route_name > rota original
     * - parent_key substitui o 'parent' original
     */
    private static function applyOverrides(array &$items): void
    {
        if (empty($items)) {
            return;
        }

        $overrides = MenuOverride::query()
            ->whereIn('key', array_keys($items))
            ->get()
            ->keyBy('key');

        foreach ($items as $k => &$it) {
            if (!$overrides->has($k)) {
                continue;
            }
            $ov = $overrides[$k];

            // Label / ícone / ordem / ocultar
            if (!is_null($ov->label)) {
                $it['label'] = $ov->label;
            }
            if (!is_null($ov->icon)) {
                $it['icon']  = $ov->icon;
            }
            if (!is_null($ov->order)) {
                $it['order'] = (int) $ov->order;
            }
            $it['_hidden'] = (bool) $ov->hidden;

            // Pai (parent_key) — impede pai de si mesmo
            if (!is_null($ov->parent_key) && $ov->parent_key !== '') {
                $it['parent'] = ($ov->parent_key !== $it['key']) ? $ov->parent_key : null;
            }

            // Link / Rota
            if (!empty($ov->custom_url)) {
                $it['route'] = null;
                $it['url']   = $ov->custom_url;   // externa ou interna
                $it['_ext']  = true;
            } elseif (!empty($ov->route_name)) {
                $it['route'] = $ov->route_name;
                $it['url']   = self::routeUrlOrNull($ov->route_name);
                $it['_ext']  = false;
            }

            // Target
            $it['_target'] = $ov->new_tab ? '_blank' : null;
        }
    }

    /**
     * Marca item ativo por nome da rota (inclui sufixos .*) e por URL (quando custom_url).
     */
    public static function isActive(array $item): bool
    {
        // Por rota
        if (!empty($item['route'])) {
            if (request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*')) {
                return true;
            }
        }

        // Por URL (custom_url tem prioridade no override)
        if (!empty($item['url'])) {
            // compara início da URL atual com a URL do item (tolerante a query string)
            $current = url()->current();
            if (strpos(rtrim($current, '/'), rtrim($item['url'], '/')) === 0) {
                return true;
            }
        }

        // Recursivo nos filhos
        foreach ($item['children'] ?? [] as $child) {
            if (self::isActive($child)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica permissão (Spatie -> $user->hasPermissionTo, depois $user->can, depois Gate::allows).
     */
    private static function can(?string $permission): bool
    {
        if (!$permission) {
            return true;
        }

        $user = Auth::user();
        if (!$user) {
            return false;
        }

        // Spatie Permission
        if (method_exists($user, 'hasPermissionTo')) {
            try {
                return $user->hasPermissionTo($permission);
            } catch (\Throwable $e) {
                // segue para fallback
            }
        }

        // Authorizable / policies
        if (method_exists($user, 'can')) {
            try {
                return $user->can($permission);
            } catch (\Throwable $e) {
                // fallback final
            }
        }

        return Gate::allows($permission);
    }

    /**
     * Tenta resolver a URL de uma rota; retorna null se não existir.
     */
    private static function routeUrlOrNull(?string $name): ?string
    {
        if (!$name) {
            return null;
        }
        try {
            return route($name);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
