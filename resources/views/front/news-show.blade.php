@php
  $ui = [
    'uz' => ['home' => 'Bosh sahifa', 'services' => 'Xizmatlar', 'doctors' => 'Shifokorlar', 'about' => 'Klinika haqida', 'news' => 'Yangiliklar', 'contact' => 'Aloqa', 'appointment' => 'Qabulga yozilish', 'back' => 'Barcha yangiliklar', 'related' => 'O‘xshash yangiliklar', 'details' => 'Batafsil', 'clinic' => 'Klinika', 'team' => 'Bizning jamoa', 'menu' => 'Menu', 'gallery' => 'Fotogalereya', 'open_image' => 'Rasmni kattalashtirish', 'close' => 'Yopish', 'prev' => 'Oldingi rasm', 'next' => 'Keyingi rasm', 'no_content' => 'Maqola matni tez orada joylanadi.'],
    'ru' => ['home' => 'Главная', 'services' => 'Услуги', 'doctors' => 'Врачи', 'about' => 'О клинике', 'news' => 'Новости', 'contact' => 'Контакты', 'appointment' => 'Записаться', 'back' => 'Все новости', 'related' => 'Похожие новости', 'details' => 'Подробнее', 'clinic' => 'Клиника', 'team' => 'Наша команда', 'menu' => 'Меню', 'gallery' => 'Фотогалерея', 'open_image' => 'Увеличить изображение', 'close' => 'Закрыть', 'prev' => 'Предыдущее изображение', 'next' => 'Следующее изображение', 'no_content' => 'Текст статьи будет опубликован в ближайшее время.'],
    'en' => ['home' => 'Home', 'services' => 'Services', 'doctors' => 'Doctors', 'about' => 'About clinic', 'news' => 'News', 'contact' => 'Contact', 'appointment' => 'Book appointment', 'back' => 'All news', 'related' => 'Related news', 'details' => 'Details', 'clinic' => 'Clinic', 'team' => 'Our team', 'menu' => 'Menu', 'gallery' => 'Photo gallery', 'open_image' => 'Enlarge image', 'close' => 'Close', 'prev' => 'Previous image', 'next' => 'Next image', 'no_content' => 'The article text will be published soon.'],
  ][$locale] ?? [];
  $gallery = collect($article->gallery_images ?? [])->filter()->values();
  $lightboxImages = collect([$article->image])->merge($gallery)->filter()->unique()->values();
  $articleText = $article->tr('body', $locale) ?: $article->tr('excerpt', $locale);
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $article->tr('title', $locale) }} | {{ $settings->tr('site_title', $locale) }}</title>
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
    <article class="section article-page">
      <div class="container article-shell">
        <header class="article-hero">
          <div class="article-hero-lines" aria-hidden="true">
            <span></span><span></span><i></i>
          </div>
          <a class="article-back" href="{{ $locale === 'uz' ? route('front.news') : route('front.locale.news', $locale) }}">← {{ $ui['back'] }}</a>
          <div class="article-hero-meta">
            <span>{{ $ui['news'] }}</span>
            <time datetime="{{ optional($article->published_at)->format('Y-m-d') }}">{{ optional($article->published_at)->format('d.m.Y') }}</time>
          </div>
          <h1>{{ $article->tr('title', $locale) }}</h1>
        </header>

        <div @class(['article-body-layout', 'article-body-layout-wide' => $relatedArticles->isEmpty()])>
          <div class="article-primary">
            @if ($article->image)
              <div class="article-main-visual">
                <div class="article-main-art" aria-hidden="true"><span></span><span></span><i></i></div>
                <button type="button" class="article-main-open" data-lightbox-index="0" aria-label="{{ $ui['open_image'] }}">
                  <img class="article-main-image" src="{{ $article->image }}" alt="{{ $article->tr('title', $locale) }}" data-article-main-image>
                  <span class="article-zoom-mark" aria-hidden="true">↗</span>
                </button>
              </div>
            @endif

            @if ($gallery->isNotEmpty())
              <section class="article-gallery-section" aria-labelledby="articleGalleryTitle">
                <div class="article-gallery-head">
                  <div>
                    <span>{{ $ui['news'] }}</span>
                    <h2 id="articleGalleryTitle">{{ $ui['gallery'] }}</h2>
                  </div>
                  <div class="article-gallery-controls">
                    <button type="button" data-gallery-prev aria-label="{{ $ui['prev'] }}">←</button>
                    <button type="button" data-gallery-next aria-label="{{ $ui['next'] }}">→</button>
                  </div>
                </div>
                <div class="article-gallery-viewport" data-gallery-viewport>
                  <div class="article-gallery" data-gallery-track>
                    @foreach ($gallery as $image)
                      @php($lightboxIndex = $lightboxImages->search($image))
                      <button type="button" class="article-gallery-thumb" data-lightbox-index="{{ $lightboxIndex }}" aria-label="{{ $ui['open_image'] }} {{ $loop->iteration }}">
                        <img src="{{ $image }}" alt="{{ $article->tr('title', $locale) }} — {{ $loop->iteration }}">
                        <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                      </button>
                    @endforeach
                  </div>
                </div>
                <div class="article-gallery-progress"><span data-gallery-progress></span></div>
              </section>
            @endif

            <div class="article-content">
              {!! $articleText ?: '<p>'.e($ui['no_content']).'</p>' !!}
            </div>
          </div>

          @if ($relatedArticles->isNotEmpty())
            <aside class="article-related" aria-labelledby="relatedArticlesTitle">
              <div class="article-related-inner">
                <div class="article-related-head">
                  <span>{{ $ui['news'] }}</span>
                  <h2 id="relatedArticlesTitle">{{ $ui['related'] }}</h2>
                </div>
                <div class="article-related-list">
                  @foreach ($relatedArticles as $related)
                    <article class="article-related-card">
                      @if ($related->image)
                        <a class="article-related-image" href="{{ $locale === 'uz' ? route('front.news.show', $related) : route('front.locale.news.show', [$locale, $related]) }}">
                          <img src="{{ $related->image }}" alt="{{ $related->tr('title', $locale) }}">
                        </a>
                      @endif
                      <div>
                        <time datetime="{{ optional($related->published_at)->format('Y-m-d') }}">{{ optional($related->published_at)->format('d.m.Y') }}</time>
                        <h3><a href="{{ $locale === 'uz' ? route('front.news.show', $related) : route('front.locale.news.show', [$locale, $related]) }}">{{ $related->tr('title', $locale) }}</a></h3>
                        <a class="article-related-link" href="{{ $locale === 'uz' ? route('front.news.show', $related) : route('front.locale.news.show', [$locale, $related]) }}">{{ $ui['details'] }} →</a>
                      </div>
                    </article>
                  @endforeach
                </div>
              </div>
            </aside>
          @endif
        </div>
      </div>
    </article>

    @if ($lightboxImages->isNotEmpty())
      <div class="article-lightbox" data-article-lightbox aria-hidden="true">
        <div class="article-lightbox-backdrop" data-lightbox-close></div>
        <div class="article-lightbox-dialog" role="dialog" aria-modal="true" aria-label="{{ $ui['gallery'] }}">
          <button type="button" class="article-lightbox-close" data-lightbox-close aria-label="{{ $ui['close'] }}">×</button>
          <button type="button" class="article-lightbox-nav article-lightbox-prev" data-lightbox-prev aria-label="{{ $ui['prev'] }}">←</button>
          <figure>
            <img src="" alt="{{ $article->tr('title', $locale) }}" data-lightbox-image>
            <figcaption><span data-lightbox-current>1</span> / {{ $lightboxImages->count() }}</figcaption>
          </figure>
          <button type="button" class="article-lightbox-nav article-lightbox-next" data-lightbox-next aria-label="{{ $ui['next'] }}">→</button>
        </div>
      </div>
      <script type="application/json" id="articleLightboxImages">@json($lightboxImages)</script>
    @endif

  </main>
  @include('front.partials.footer')
  <script>
    (() => {
      const viewport = document.querySelector('[data-gallery-viewport]');
      const track = document.querySelector('[data-gallery-track]');
      const slides = [...document.querySelectorAll('.article-gallery-thumb')];
      const progress = document.querySelector('[data-gallery-progress]');
      let galleryIndex = 0;
      let galleryTimer;

      const visibleSlides = () => window.innerWidth <= 560 ? 1 : (window.innerWidth <= 900 ? 2 : 3);
      const showGallerySlide = (index) => {
        if (!viewport || !slides.length) return;
        const maxIndex = Math.max(0, slides.length - visibleSlides());
        galleryIndex = index > maxIndex ? 0 : (index < 0 ? maxIndex : index);
        const gap = parseFloat(getComputedStyle(track).gap || '18');
        viewport.scrollTo({ left: galleryIndex * (slides[0].offsetWidth + gap), behavior: 'smooth' });
        if (progress) progress.style.width = `${Math.min(100, ((galleryIndex + visibleSlides()) / slides.length) * 100)}%`;
      };
      const restartGallery = () => {
        window.clearInterval(galleryTimer);
        if (slides.length > visibleSlides()) {
          galleryTimer = window.setInterval(() => showGallerySlide(galleryIndex + 1), 3800);
        }
      };

      document.querySelector('[data-gallery-prev]')?.addEventListener('click', () => {
        showGallerySlide(galleryIndex - 1);
        restartGallery();
      });
      document.querySelector('[data-gallery-next]')?.addEventListener('click', () => {
        showGallerySlide(galleryIndex + 1);
        restartGallery();
      });
      viewport?.addEventListener('mouseenter', () => window.clearInterval(galleryTimer));
      viewport?.addEventListener('mouseleave', restartGallery);
      window.addEventListener('resize', () => showGallerySlide(galleryIndex));

      const lightbox = document.querySelector('[data-article-lightbox]');
      const lightboxImage = document.querySelector('[data-lightbox-image]');
      const counter = document.querySelector('[data-lightbox-current]');
      const imagesSource = document.querySelector('#articleLightboxImages');
      const images = imagesSource ? JSON.parse(imagesSource.textContent) : [];
      let lightboxIndex = 0;
      let lastFocusedElement;

      const showLightboxImage = (index) => {
        if (!images.length || !lightboxImage) return;
        lightboxIndex = (index + images.length) % images.length;
        lightboxImage.src = images[lightboxIndex];
        if (counter) counter.textContent = String(lightboxIndex + 1);
      };
      const openLightbox = (index, trigger) => {
        if (!lightbox) return;
        lastFocusedElement = trigger;
        showLightboxImage(index);
        lightbox.classList.add('open');
        lightbox.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        lightbox.querySelector('[data-lightbox-close]')?.focus();
      };
      const closeLightbox = () => {
        if (!lightbox) return;
        lightbox.classList.remove('open');
        lightbox.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        lastFocusedElement?.focus();
      };

      document.querySelectorAll('[data-lightbox-index]').forEach((button) => {
        button.addEventListener('click', () => openLightbox(Number(button.dataset.lightboxIndex), button));
      });
      document.querySelectorAll('[data-lightbox-close]').forEach((button) => button.addEventListener('click', closeLightbox));
      document.querySelector('[data-lightbox-prev]')?.addEventListener('click', () => showLightboxImage(lightboxIndex - 1));
      document.querySelector('[data-lightbox-next]')?.addEventListener('click', () => showLightboxImage(lightboxIndex + 1));
      document.addEventListener('keydown', (event) => {
        if (!lightbox?.classList.contains('open')) return;
        if (event.key === 'Escape') closeLightbox();
        if (event.key === 'ArrowLeft') showLightboxImage(lightboxIndex - 1);
        if (event.key === 'ArrowRight') showLightboxImage(lightboxIndex + 1);
      });

      showGallerySlide(0);
      restartGallery();
    })();
  </script>
  <script src="{{ asset('front-assets/script.js') }}"></script>
</body>
</html>
