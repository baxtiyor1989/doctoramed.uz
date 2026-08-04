@foreach ($menus as $menu)
  @php($level = $level ?? 0)
  <div @class(['nav-item', 'has-submenu' => $menu['children']->isNotEmpty()])>
    <a
      @class(['active' => ($activeUrl ?? null) && str_contains($menu['url'], $activeUrl)])
      href="{{ $menu['url'] }}"
      data-menu-id="{{ $menu['id'] }}"
      data-service-filterable="{{ $level > 0 && !($menu['has_doctors'] ?? false) ? '1' : '0' }}">
      {{ $menu['title'] }}
    </a>
    @if ($menu['children']->isNotEmpty())
      <button class="nav-submenu-toggle" type="button" aria-label="{{ $menu['title'] }} submenu">
        <span></span>
      </button>
      <div class="submenu">
        @include('front.partials.menu-items', ['menus' => $menu['children'], 'activeUrl' => $activeUrl ?? null, 'level' => $level + 1])
      </div>
    @endif
  </div>
@endforeach
