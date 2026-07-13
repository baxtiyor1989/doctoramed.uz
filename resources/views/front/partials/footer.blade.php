<footer class="footer" id="contact">
  <div class="container footer-grid">
    <div>
      <a class="logo footer-logo" href="{{ $locale === 'uz' ? route('front.home') : route('front.locale', $locale) }}#home">
        <span class="footer-css-logo" aria-hidden="true">
          @for ($i = 1; $i <= 25; $i++)
            <span></span>
          @endfor
        </span>
        <span class="site-logo-text">{{ $settings->tr('brand_name', $locale) }}<small>{{ $settings->tr('brand_subtitle', $locale) }}</small></span>
      </a>
      <p>{{ $settings->tr('footer_text', $locale) }}</p>
      @php
        $socialLinks = collect([
          ['label' => 'Facebook', 'type' => 'text', 'icon' => 'f', 'url' => $settings->facebook_url],
          ['label' => 'Telegram', 'type' => 'telegram', 'icon' => '', 'url' => $settings->telegram_url],
          ['label' => 'Instagram', 'type' => 'text', 'icon' => 'in', 'url' => $settings->instagram_url],
          ['label' => 'YouTube', 'type' => 'text', 'icon' => '▶', 'url' => $settings->youtube_url],
        ])->filter(function ($item) {
          return filled($item['url']);
        })->values();
      @endphp
      @if ($socialLinks->isNotEmpty())
        <div class="socials">
          @foreach ($socialLinks as $social)
            <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }}">
              @if ($social['type'] === 'telegram')
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.8 4.6 18.5 20c-.25 1.1-.9 1.36-1.82.85l-5.02-3.7-2.42 2.33c-.27.27-.5.5-1.02.5l.36-5.12 9.32-8.42c.4-.36-.09-.56-.62-.2L5.76 13.5.8 11.95c-1.08-.34-1.1-1.08.23-1.6L20.4 2.88c.9-.33 1.68.2 1.4 1.72Z" /></svg>
              @else
                {{ $social['icon'] }}
              @endif
            </a>
          @endforeach
        </div>
      @endif
    </div>

    @php($homeUrl = $locale === 'uz' ? route('front.home') : route('front.locale', $locale))
    <div>
      <h3>{{ $ui['clinic'] }}</h3>
      <a href="{{ $homeUrl }}#about">{{ $ui['about'] }}</a>
      <a href="{{ $homeUrl }}#doctors">{{ $ui['team'] }}</a>
      <a href="{{ $homeUrl }}#services">{{ $ui['services'] }}</a>
      <a href="{{ $homeUrl }}#contact">{{ $ui['contact'] }}</a>
    </div>

    <div>
      <h3>{{ $ui['services'] }}</h3>
      @foreach ($services->take(4) as $service)
        <a href="{{ $homeUrl }}#services">{{ $service->tr('title', $locale) }}</a>
      @endforeach
    </div>

    <div>
      <h3>{{ $ui['contact'] }}</h3>
      <p>{{ $settings->tr('address', $locale) }}</p>
      <p>{{ $settings->tr('phone', $locale) }}</p>
      <p>{{ $settings->tr('email', $locale) }}</p>
      <p>{{ $settings->tr('website', $locale) }}</p>
    </div>
  </div>

  <div class="container footer-bottom">
    <p>{{ $settings->tr('footer_copyright', $locale) }}</p>
    <a href="#top" class="to-top">↑</a>
  </div>
</footer>
