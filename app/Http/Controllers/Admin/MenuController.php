<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuOverride;
use App\Support\MenuBuilder;
use Illuminate\Http\Request;

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


}
