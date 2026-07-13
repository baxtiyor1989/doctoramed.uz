@php
  $ui = [
    'uz' => ['home' => 'Bosh sahifa', 'services' => 'Xizmatlar', 'doctors' => 'Shifokorlar', 'about' => 'Klinika haqida', 'news' => 'Yangiliklar', 'contact' => 'Aloqa', 'appointment' => 'Qabulga yozilish', 'back' => 'Barcha yangiliklar', 'related' => 'O‘xshash yangiliklar', 'details' => 'Batafsil', 'clinic' => 'Klinika', 'team' => 'Bizning jamoa', 'menu' => 'Menu'],
    'ru' => ['home' => 'Главная', 'services' => 'Услуги', 'doctors' => 'Врачи', 'about' => 'О клинике', 'news' => 'Новости', 'contact' => 'Контакты', 'appointment' => 'Записаться', 'back' => 'Все новости', 'related' => 'Похожие новости', 'details' => 'Подробнее', 'clinic' => 'Клиника', 'team' => 'Наша команда', 'menu' => 'Меню'],
    'en' => ['home' => 'Home', 'services' => 'Services', 'doctors' => 'Doctors', 'about' => 'About clinic', 'news' => 'News', 'contact' => 'Contact', 'appointment' => 'Book appointment', 'back' => 'All news', 'related' => 'Related news', 'details' => 'Details', 'clinic' => 'Clinic', 'team' => 'Our team', 'menu' => 'Menu'],
  ][$locale] ?? [];
  $gallery = collect($article->gallery_images ?? [])->filter()->values();
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $article->tr('title', $locale) }} | {{ $settings->tr('site_title', $locale) }}</title>
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
    <article class="section article-page">
      <div class="container article-shell">
        <a class="article-back" href="{{ $locale === 'uz' ? route('front.news') : route('front.locale.news', $locale) }}">← {{ $ui['back'] }}</a>
        <small>{{ optional($article->published_at)->format('d.m.Y') }}</small>
        <h1>{{ $article->tr('title', $locale) }}</h1>

        @if ($article->image)
          <img class="article-main-image" src="{{ $article->image }}" alt="{{ $article->tr('title', $locale) }}" data-article-main-image>
        @endif

        @if ($gallery->isNotEmpty())
          <div class="article-gallery">
            @foreach ($gallery as $image)
              <button type="button" class="article-gallery-thumb" data-article-gallery-image="{{ $image }}" aria-label="{{ $article->tr('title', $locale) }}">
                <img src="{{ $image }}" alt="{{ $article->tr('title', $locale) }}">
              </button>
            @endforeach
          </div>
        @endif

        <div class="article-content">
          {!! $article->tr('body', $locale) ?: $article->tr('excerpt', $locale) !!}
        </div>
      </div>
    </article>

    @if ($relatedArticles->isNotEmpty())
      <section class="section news-section">
        <div class="container">
          <div class="section-head reveal visible">
            <span>{{ $ui['news'] }}</span>
            <h2>{{ $ui['related'] }}</h2>
          </div>
          <div class="news-grid">
            @foreach ($relatedArticles as $related)
              <article class="news-card reveal visible">
                @if ($related->image)
                  <img src="{{ $related->image }}" alt="{{ $related->tr('title', $locale) }}">
                @endif
                <small>{{ optional($related->published_at)->format('d.m.Y') }}</small>
                <h3>{{ $related->tr('title', $locale) }}</h3>
                <a href="{{ $locale === 'uz' ? route('front.news.show', $related) : route('front.locale.news.show', [$locale, $related]) }}">{{ $ui['details'] }} →</a>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @endif
  </main>
  @include('front.partials.footer')
  <script>
    document.querySelectorAll('[data-article-gallery-image]').forEach((button) => {
      button.addEventListener('click', () => {
        const mainImage = document.querySelector('[data-article-main-image]');
        if (!mainImage) return;

        document.querySelectorAll('[data-article-gallery-image]').forEach((item) => item.classList.remove('active'));
        button.classList.add('active');
        mainImage.src = button.dataset.articleGalleryImage;
      });
    });
  </script>
  <script src="{{ asset('front-assets/script.js') }}"></script>
</body>
</html>
