<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class Breadcrumbs extends Component
{
    public function render()
    {
        $name = Route::currentRouteName();
        $parts = explode('.', $name);
        $breadcrumbs = [];

        if ($parts) {
            $breadcrumbs[] = ['label' => 'Dashboard', 'url' => route('dashboard')];

            if (count($parts) > 1) {
                $label = ucfirst($parts[0]);
                $breadcrumbs[] = ['label' => $label, 'url' => route($parts[0].'.index')];

                if ($parts[1] !== 'index') {
                    $breadcrumbs[] = ['label' => ucfirst($parts[1])];
                }
            }
        }

        return view('components.breadcrumbs', ['items' => $breadcrumbs]);
    }
}
