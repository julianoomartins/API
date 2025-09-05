@php
    $renderNodes = function($nodes) use (&$renderNodes) {
        foreach ($nodes as $node) {
            $hasChildren = !empty($node['children']);
            $isActive    = \App\Support\MenuBuilder::isActive($node);

            $linkClass = 'nav-link' . ($isActive ? ' active' : '');

            if ($hasChildren) {
                echo '<li class="nav-item has-treeview '.($isActive ? 'menu-open' : '').'">';
                echo   '<a href="#" class="'.$linkClass.'" aria-expanded="'.($isActive ? 'true' : 'false').'">';
                echo     '<i class="nav-icon '.$node['icon'].'"></i>';
                echo     '<p>'.$node['label'].'<i class="right fas fa-angle-left"></i></p>';
                echo   '</a>';
                echo   '<ul class="nav nav-treeview">';
                $renderNodes($node['children']);
                echo   '</ul>';
                echo '</li>';
            } else {
                $href = $node['url'] ?: '#';
                echo '<li class="nav-item">';
                echo   '<a href="'.e($href).'" class="'.$linkClass.'">';
                echo     '<i class="nav-icon '.$node['icon'].'"></i>';
                echo     '<p>'.$node['label'].'</p>';
                echo   '</a>';
                echo '</li>';
            }
        }
    };
@endphp

<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column"
      data-widget="treeview" data-accordion="false" role="menu">
    {!! $renderNodes($menuTree) !!}
  </ul>
</nav>

