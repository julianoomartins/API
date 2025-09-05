<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMenuRequest;
use App\Models\MenuOverride;
use App\Support\MenuBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class MenuController extends Controller
{
    public function index()
    {
        // Árvore atual já com overrides aplicados
        $tree = MenuBuilder::build();

        // Overrides existentes (para preencher formulário)
        $overrides = MenuOverride::all()->keyBy('key');

        return view('admin.menu.index', compact('tree','overrides'));
    }

    public function update(Request $request)
{
    // existentes
    $keys       = $request->input('key', []);
    $labels     = $request->input('label', []);
    $icons      = $request->input('icon', []);
    $orders     = $request->input('order', []);
    $hiddens    = $request->input('hidden', []);      // array de keys marcadas

    // novos
    $routeNames = $request->input('route_name', []);
    $customUrls = $request->input('custom_url', []);
    $newTabs    = $request->input('new_tab', []);     // array de keys marcadas
    $parentKeys = $request->input('parent_key', []);  // <<< FALTAVA

    foreach ($keys as $i => $key) {
        // saneamento do parent
        $parent = $parentKeys[$i] ?? null;
        if ($parent === '' || $parent === $key) {
            $parent = null; // evita pai vazio ou ser pai de si mesmo
        }

        MenuOverride::updateOrCreate(
            ['key' => $key],
            [
                'label'      => $labels[$i]  ?? null,
                'icon'       => $icons[$i]   ?? null,
                'order'      => is_numeric($orders[$i] ?? null) ? (int)$orders[$i] : null,
                'hidden'     => in_array($key, $hiddens ?? []),

                'route_name' => $routeNames[$i] ?? null,
                'custom_url' => $customUrls[$i] ?? null,
                'new_tab'    => in_array($key, $newTabs ?? []),

                'parent_key' => $parent, // agora vai gravar certo
            ]
        );
    }

    return back()->with('success', 'Menu atualizado com sucesso!');
}

public function create()
    {
        // opções de pai
        $overrides = MenuOverride::query()
            ->orderBy('order')->orderBy('key')
            ->get()->keyBy('key');

        $parentOptions = $overrides->keys()->sort()->values();

        // rotas nomeadas para o select
        $namedRoutes = collect(Route::getRoutes())
            ->map(fn($r) => $r->getName())
            ->filter()                    // remove null
            ->unique()
            ->sort()
            ->values();

        return view('admin.menu.create', [
            'parentOptions' => $parentOptions,
            'overrides'     => $overrides,
            'namedRoutes'   => $namedRoutes,
        ]);
    }

public function store(StoreMenuRequest $request)
    {
        $data = $request->validated();

        // garantir key única (se colidir, sufixa -2, -3, ...)
        $baseKey = $data['key'];
        $tryKey  = $baseKey;
        $i = 2;
        while (MenuOverride::where('key', $tryKey)->exists()) {
            $tryKey = $baseKey.'-'.$i;
            $i++;
        }
        $data['key'] = $tryKey;

        // order: se vazio, coloca no fim dos irmãos do mesmo parent
        if (!isset($data['order'])) {
            $maxOrder = MenuOverride::where('parent_key', $data['parent_key'] ?? null)->max('order');
            $data['order'] = is_null($maxOrder) ? 0 : ($maxOrder + 1);
        }

        // criar
        MenuOverride::create([
            'key'        => $data['key'],
            'label'      => $data['label'],
            'icon'       => $data['icon'] ?? null,
            'route_name' => $data['route_name'] ?? null,
            'custom_url' => $data['custom_url'] ?? null,
            'order'      => $data['order'],
            'parent_key' => $data['parent_key'] ?? null,
            'new_tab'    => (bool)($data['new_tab'] ?? false),
            'hidden'     => (bool)($data['hidden'] ?? false),
        ]);

        return redirect()
            ->route('admin.menu.index')
            ->with('success', 'Item de menu criado com sucesso!');
    }
}
