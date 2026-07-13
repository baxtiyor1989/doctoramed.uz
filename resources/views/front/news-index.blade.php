@php
  $ui = [
    'uz' => ['home' => 'Bosh sahifa', 'services' => 'Xizmatlar', 'doctors' => 'Shifokorlar', 'about' => 'Klinika haqida', 'news' => 'Yangiliklar', 'contact' => 'Aloqa', 'appointment' => 'Qabulga yozilish', 'details' => 'Batafsil', 'all_news' => 'Barcha yangiliklar', 'prev' => 'Oldingi', 'next' => 'Keyingi', 'clinic' => 'Klinika', 'team' => 'Bizning jamoa', 'menu' => 'Menu'],
    'ru' => ['home' => 'Главная', 'services' => 'Услуги', 'doctors' => 'Врачи', 'about' => 'О клинике', 'news' => 'Новости', 'contact' => 'Контакты', 'appointment' => 'Записаться', 'details' => 'Подробнее', 'all_news' => 'Все новости', 'prev' => 'Назад', 'next' => 'Вперёд', 'clinic' => 'Клиника', 'team' => 'Наша команда', 'menu' => 'Меню'],
    'en' => ['home' => 'Home', 'services' => 'Services', 'doctors' => 'Doctors', 'about' => 'About clinic', 'news' => 'News', 'contact' => 'Contact', 'appointment' => 'Book appointment', 'details' => 'Details', 'all_news' => 'All news', 'prev' => 'Previous', 'next' => 'Next', 'clinic' => 'Clinic', 'team' => 'Our team', 'menu' => 'Menu'],
  ][$locale] ?? [];
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $ui['all_news'] }} | {{ $settings->tr('site_title', $locale) }}</title>
  <link rel="icon" type="image/png" href="{{ asset('front-assets/logo.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('front-assets/logo.png') }}">
  <link rel="stylesheet" href="{{ asset('front-assets/style.css') }}">
</head>
<body id="top">
  <div class="page-lines">
    <span class="line line-1"></span>
    <span class="line line-2"></span>
    <span class="line line-3"></span>
  </div>

  @include('front.partials.header')

  <main>
    <section class="section news-page">
      <div class="container">
        <div class="section-head reveal visible">
          <span>{{ $settings->tr('news_subtitle', $locale) }}</span>
          <h1>{{ $settings->tr('news_title', $locale) }}</h1>
        </div>

        <div class="news-grid news-grid-page">
          @foreach ($articles as $article)
            <article class="news-card reveal visible">
              @if ($article->image)
                <img src="{{ $article->image }}" alt="{{ $article->tr('title', $locale) }}">
              @endif
              <small>{{ optional($article->published_at)->format('d.m.Y') }}</small>
              <h3>{{ $article->tr('title', $locale) }}</h3>
              <p>{!! \Illuminate\Support\Str::limit(strip_tags($article->tr('excerpt', $locale) ?: $article->tr('body', $locale)), 120) !!}</p>
              <a href="{{ $locale === 'uz' ? route('front.news.show', $article) : route('front.locale.news.show', [$locale, $article]) }}">{{ $ui['details'] }} →</a>
            </article>
          @endforeach
        </div>

        @if ($articles->hasPages())
          <nav class="custom-pagination" aria-label="Pagination">
            @if ($articles->onFirstPage())
              <span class="disabled">{{ $ui['prev'] }}</span>
            @else
              <a href="{{ $articles->previousPageUrl() }}">{{ $ui['prev'] }}</a>
            @endif

            @foreach ($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
              @if ($page === $articles->currentPage())
                <span class="active">{{ $page }}</span>
              @else
                <a href="{{ $url }}">{{ $page }}</a>
              @endif
            @endforeach

            @if ($articles->hasMorePages())
              <a href="{{ $articles->nextPageUrl() }}">{{ $ui['next'] }}</a>
            @else
              <span class="disabled">{{ $ui['next'] }}</span>
            @endif
          </nav>
        @endif
      </div>
    </section>
  </main>
  @include('front.partials.footer')
  <script src="{{ asset('front-assets/script.js') }}"></script>
</body>
</html>
