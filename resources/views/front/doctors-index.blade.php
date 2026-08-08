@php
  $ui = [
    'uz' => ['home' => 'Bosh sahifa', 'services' => 'Xizmatlar', 'doctors' => 'Shifokorlar', 'about' => 'Klinika haqida', 'contact' => 'Aloqa', 'appointment' => 'Qabulga yozilish', 'title' => 'Bizning mutaxassislar', 'subtitle' => 'Malakali shifokorlar', 'intro' => 'Tajribali shifokorlarimiz, ularning mutaxassisligi va qabul ma’lumotlari', 'count' => 'nafar shifokor', 'experience' => 'Tajriba', 'category' => 'Toifa', 'schedule' => 'Qabul kunlari', 'empty' => 'Bu menu uchun shifokorlar hali biriktirilmagan.', 'details' => 'Batafsil', 'clinic' => 'Klinika', 'team' => 'Bizning jamoa', 'menu' => 'Menu'],
    'ru' => ['home' => 'Главная', 'services' => 'Услуги', 'doctors' => 'Врачи', 'about' => 'О клинике', 'contact' => 'Контакты', 'appointment' => 'Записаться', 'title' => 'Наши специалисты', 'subtitle' => 'Квалифицированные врачи', 'intro' => 'Опытные врачи, их специализация и информация о приёме', 'count' => 'врачей', 'experience' => 'Опыт', 'category' => 'Категория', 'schedule' => 'Дни приёма', 'empty' => 'К этому меню пока не прикреплены врачи.', 'details' => 'Подробнее', 'clinic' => 'Клиника', 'team' => 'Наша команда', 'menu' => 'Меню'],
    'en' => ['home' => 'Home', 'services' => 'Services', 'doctors' => 'Doctors', 'about' => 'About clinic', 'contact' => 'Contact', 'appointment' => 'Book appointment', 'title' => 'Our specialists', 'subtitle' => 'Qualified doctors', 'intro' => 'Experienced doctors, their specialties and appointment information', 'count' => 'doctors', 'experience' => 'Experience', 'category' => 'Category', 'schedule' => 'Office hours', 'empty' => 'No doctors have been assigned to this menu yet.', 'details' => 'Details', 'clinic' => 'Clinic', 'team' => 'Our team', 'menu' => 'Menu'],
  ][$locale] ?? [];
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $selectedMenu?->tr('title', $locale) ?: $ui['title'] }} | {{ $settings->tr('site_title', $locale) }}</title>
  <link rel="icon" type="image/png" href="{{ asset('front-assets/logo.png') }}">
  <link rel="stylesheet" href="{{ asset('front-assets/style.css') }}?v={{ filemtime(public_path('front-assets/style.css')) }}">
</head>
<body id="top">
  <div class="page-lines"><span class="line line-1"></span><span class="line line-2"></span><span class="line line-3"></span></div>
  @include('front.partials.header')

  <main class="doctors-list-page">
    <section class="section doctors-list-section">
      <div class="doctors-list-art" aria-hidden="true"><span></span><span></span><i></i></div>
      <div class="container">
        <header class="doctors-list-hero">
          <div class="doctors-list-heading">
            <span>{{ $ui['subtitle'] }}</span>
            <h1>{{ $selectedMenu?->tr('title', $locale) ?: $ui['title'] }}</h1>
          </div>
          <p>{{ $ui['intro'] }}</p>
          <strong>{{ $doctors->count() }} {{ $ui['count'] }}</strong>
        </header>

        @if ($doctors->isNotEmpty())
          <div class="doctors-list-grid">
            @foreach ($doctors as $doctor)
              <article class="doctors-list-card">
                @php($doctorUrl = $locale === 'uz' ? route('front.doctors.show', $doctor) : route('front.locale.doctors.show', [$locale, $doctor]))
                <a class="doctors-list-photo" href="{{ $doctorUrl }}">
                  <span class="doctors-list-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                  @if ($doctor->image)
                    <img src="{{ $doctor->image }}" alt="{{ $doctor->tr('name', $locale) }}">
                  @else
                    <span class="doctors-list-placeholder" aria-hidden="true">✚</span>
                  @endif
                </a>
                <div class="doctors-list-card-content">
                  <span class="doctors-list-specialty">{{ $doctor->tr('specialty', $locale) }}</span>
                  <h2><a href="{{ $doctorUrl }}">{{ $doctor->tr('name', $locale) }}</a></h2>
                  @if ($doctor->tr('category', $locale))<p class="doctors-list-degree">{{ $doctor->tr('category', $locale) }}</p>@endif
                  <dl class="doctors-list-facts">
                    @if ($doctor->tr('experience', $locale))
                      <div><dt><i>✚</i> {{ $ui['experience'] }}</dt><dd>{{ $doctor->tr('experience', $locale) }}</dd></div>
                    @endif
                    @if ($doctor->tr('category', $locale))
                      <div><dt><i>◆</i> {{ $ui['category'] }}</dt><dd>{{ $doctor->tr('category', $locale) }}</dd></div>
                    @endif
                    @if ($doctor->tr('work_schedule', $locale))
                      <div class="doctors-list-fact-wide"><dt><i>▣</i> {{ $ui['schedule'] }}</dt><dd>{{ $doctor->tr('work_schedule', $locale) }}</dd></div>
                    @endif
                  </dl>
                </div>
                <a class="doctors-list-link" href="{{ $doctorUrl }}" aria-label="{{ $doctor->tr('name', $locale) }} — {{ $ui['details'] }}"><span>→</span></a>
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
  @include('front.partials.service-rating')
  <script src="{{ asset('front-assets/script.js') }}?v={{ filemtime(public_path('front-assets/script.js')) }}"></script>
</body>
</html>
