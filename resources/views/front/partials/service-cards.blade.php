@foreach ($services as $service)
  <article @class(['service-card', 'reveal', 'visible', 'service-collapsed' => $loop->index >= 5]) data-service-card data-service-menu-id="{{ $service->menu_item_id }}">
    <div class="icon">{{ $service->icon }}</div>
    <h3>{{ $service->tr('title', $locale) }}</h3>
    <p>{{ $service->tr('description', $locale) }}</p>
    <button
      type="button"
      class="service-detail-link"
      data-service-open
      data-service-title="{{ $service->tr('title', $locale) }}"
      data-service-id="{{ $service->id }}"
      data-service-description="{{ $service->tr('description', $locale) }}">
      {{ $detailsText ?? 'Batafsil' }} →
    </button>
  </article>
@endforeach
