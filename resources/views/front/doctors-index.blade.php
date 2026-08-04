@php
  $ui = [
    'uz' => ['home' => 'Bosh sahifa', 'services' => 'Xizmatlar', 'doctors' => 'Shifokorlar', 'about' => 'Klinika haqida', 'contact' => 'Aloqa', 'appointment' => 'Qabulga yozilish', 'title' => 'Bizning mutaxassislar', 'subtitle' => 'Malakali shifokorlar', 'empty' => 'Bu menu uchun shifokorlar hali biriktirilmagan.', 'details' => 'Batafsil', 'clinic' => 'Klinika', 'team' => 'Bizning jamoa', 'menu' => 'Menu'],
    'ru' => ['home' => 'Главная', 'services' => 'Услуги', 'doctors' => 'Врачи', 'about' => 'О клинике', 'contact' => 'Контакты', 'appointment' => 'Записаться', 'title' => 'Наши специалисты', 'subtitle' => 'Квалифицированные врачи', 'empty' => 'К этому меню пока не прикреплены врачи.', 'details' => 'Подробнее', 'clinic' => 'Клиника', 'team' => 'Наша команда', 'menu' => 'Меню'],
    'en' => ['home' => 'Home', 'services' => 'Services', 'doctors' => 'Doctors', 'about' => 'About clinic', 'contact' => 'Contact', 'appointment' => 'Book appointment', 'title' => 'Our specialists', 'subtitle' => 'Qualified doctors', 'empty' => 'No doctors have been assigned to this menu yet.', 'details' => 'Details', 'clinic' => 'Clinic', 'team' => 'Our team', 'menu' => 'Menu'],
  ][$locale] ?? [];
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $selectedMenu?->tr('title', $locale) ?: $ui['title'] }} | {{ $settings->tr('site_title', $locale) }}</title>
  <link rel="icon" type="image/png" href="{{ asset('front-assets/logo.png') }}">
  <link rel="stylesheet" href="{{ asset('front-assets/style.css') }}">
</head>
<body id="top">
  <div class="page-lines"><span class="line line-1"></span><span class="line line-2"></span><span class="line line-3"></span></div>
  @include('front.partials.header')

  <main class="doctors-list-page">
    <section class="section doctors-list-section">
      <div class="doctors-list-art" aria-hidden="true"><span></span><span></span><i></i></div>
      <div class="container">
        <header class="doctors-list-hero">
          <span>{{ $ui['subtitle'] }}</span>
          <h1>{{ $selectedMenu?->tr('title', $locale) ?: $ui['title'] }}</h1>
        </header>

        @if ($doctors->isNotEmpty())
          <div class="doctors-list-grid">
            @foreach ($doctors as $doctor)
              <article class="doctors-list-card">
                @if ($doctor->image)
                  <a href="{{ $locale === 'uz' ? route('front.doctors.show', $doctor) : route('front.locale.doctors.show', [$locale, $doctor]) }}">
                    <img src="{{ $doctor->image }}" alt="{{ $doctor->tr('name', $locale) }}">
                  </a>
                @endif
                <div class="doctors-list-card-content">
                  <h2><a href="{{ $locale === 'uz' ? route('front.doctors.show', $doctor) : route('front.locale.doctors.show', [$locale, $doctor]) }}">{{ $doctor->tr('name', $locale) }}</a></h2>
                  <p>{{ $doctor->tr('specialty', $locale) }}</p>
                  @if ($doctor->tr('category', $locale))<small>{{ $doctor->tr('category', $locale) }}</small>@endif
                  <a class="doctors-list-link" href="{{ $locale === 'uz' ? route('front.doctors.show', $doctor) : route('front.locale.doctors.show', [$locale, $doctor]) }}">{{ $ui['details'] }} →</a>
                </div>
              </article>
            @endforeach
          </div>
        @else
          <div class="doctors-list-empty">{{ $ui['empty'] }}</div>
        @endif
      </div>
    </section>
  </main>

  @include('front.partials.footer')
  <script src="{{ asset('front-assets/script.js') }}"></script>
</body>
</html>
