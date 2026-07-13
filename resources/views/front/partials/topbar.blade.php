@php
  $accessibilityUi = [
    'uz' => [
      'button' => 'Maxsus imkoniyatlar',
      'grayscale' => 'Kulrang rejim',
      'font_down' => 'A-',
      'font_normal' => 'A',
      'font_up' => 'A+',
      'hide_images' => 'Rasmlarni yashirish',
      'reset' => 'Tiklash',
    ],
    'ru' => [
      'button' => 'Спец. возможности',
      'grayscale' => 'Серый режим',
      'font_down' => 'A-',
      'font_normal' => 'A',
      'font_up' => 'A+',
      'hide_images' => 'Скрыть изображения',
      'reset' => 'Сброс',
    ],
    'en' => [
      'button' => 'Accessibility',
      'grayscale' => 'Grayscale',
      'font_down' => 'A-',
      'font_normal' => 'A',
      'font_up' => 'A+',
      'hide_images' => 'Hide images',
      'reset' => 'Reset',
    ],
  ][$locale] ?? [];
  $topPhones = collect([
      $settings->tr('phone', $locale),
    ])
    ->flatMap(fn ($value) => preg_split('/\r\n|\r|\n|,|;/', (string) $value))
    ->map(fn ($value) => trim($value))
    ->filter()
    ->unique()
    ->values();
  $socialLinks = collect([
    ['label' => 'Facebook', 'type' => 'text', 'icon' => 'f', 'url' => $settings->facebook_url],
    ['label' => 'Telegram', 'type' => 'telegram', 'icon' => '', 'url' => $settings->telegram_url],
    ['label' => 'Instagram', 'type' => 'text', 'icon' => 'in', 'url' => $settings->instagram_url],
    ['label' => 'YouTube', 'type' => 'text', 'icon' => '▶', 'url' => $settings->youtube_url],
  ])->filter(function ($item) {
    return filled($item['url']);
  })->values();
@endphp

<div class="top-social-bar">
  <div class="container top-social-inner">
    <span>{{ $settings->tr('address', $locale) }}</span>
    <div class="top-social-side">
      @if ($topPhones->isNotEmpty())
        <div class="top-phone">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M7.2 4.8 9 8.9l-2 1.4c1.1 2.4 3.1 4.4 5.5 5.6l1.5-2 4.1 1.8-.7 3.4c-.2.8-.9 1.4-1.7 1.3C9.2 19.9 4.1 14.8 3.6 8.3c-.1-.8.5-1.5 1.3-1.7l2.3-.8Z" />
          </svg>
          <span class="top-phone-list">
            @foreach ($topPhones as $phone)
              <a href="tel:{{ preg_replace('/\D+/', '', $phone) }}">{{ $phone }}</a>
            @endforeach
          </span>
        </div>
      @endif
      <div class="accessibility-widget" data-accessibility-widget>
        <button class="accessibility-toggle" type="button" aria-expanded="false" aria-label="{{ $accessibilityUi['button'] }}">
          <span>A</span>
        </button>
        <div class="accessibility-panel">
          <button type="button" data-accessibility-toggle="grayscale">{{ $accessibilityUi['grayscale'] }}</button>
          <div class="accessibility-font-controls">
            <button type="button" data-accessibility-font="down">{{ $accessibilityUi['font_down'] }}</button>
            <button type="button" data-accessibility-font="normal">{{ $accessibilityUi['font_normal'] }}</button>
            <button type="button" data-accessibility-font="up">{{ $accessibilityUi['font_up'] }}</button>
          </div>
          <button type="button" data-accessibility-toggle="hide-images">{{ $accessibilityUi['hide_images'] }}</button>
          <button type="button" data-accessibility-reset>{{ $accessibilityUi['reset'] }}</button>
        </div>
      </div>
      @if ($socialLinks->isNotEmpty())
        <div class="top-social-links" aria-label="Ijtimoiy tarmoqlar">
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
  </div>
</div>
