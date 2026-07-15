@include('front.partials.topbar')

<header class="site-header">
  <div class="container header-inner">
    <a class="logo" href="{{ $locale === 'uz' ? route('front.home') : route('front.locale', $locale) }}">
      <span class="header-css-logo" aria-hidden="true">
        @for ($i = 1; $i <= 17; $i++)
          <span></span>
        @endfor
      </span>
      <span class="site-logo-text">{{ $settings->tr('brand_name', $locale) }}<small>{{ $settings->tr('brand_subtitle', $locale) }}</small></span>
    </a>

    @php($homeUrl = $locale === 'uz' ? route('front.home') : route('front.locale', $locale))
    <nav class="nav" id="nav">
      @include('front.partials.menu-items', ['menus' => $frontMenus, 'activeUrl' => '/news'])
    </nav>

    <div class="header-actions">
      <div class="language-select" data-language-dropdown>
        <button class="language-current" type="button" aria-label="Tilni tanlash" aria-expanded="false">
          {{ strtoupper($locale) }}
        </button>
        <div class="language-menu">
          <a href="{{ route('front.news') }}" @class(['active' => $locale === 'uz'])>UZ</a>
          <a href="{{ route('front.locale.news', 'ru') }}" @class(['active' => $locale === 'ru'])>RU</a>
          <a href="{{ route('front.locale.news', 'en') }}" @class(['active' => $locale === 'en'])>EN</a>
        </div>
      </div>
      <a href="{{ $homeUrl }}#appointment" class="btn btn-primary">{{ $ui['appointment'] }}</a>
      <button class="menu-btn" id="menuBtn" aria-label="{{ $ui['menu'] }}"><span></span><span></span><span></span></button>
    </div>
  </div>
</header>
