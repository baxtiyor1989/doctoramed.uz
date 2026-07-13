@extends('admin.layout')

@section('title', 'Asosiy sozlamalar')

@section('content')
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-body">
                <div class="row">
                    @foreach ([
                        'site_title' => 'Sayt title',
                        'brand_name' => 'Brend nomi',
                        'brand_subtitle' => 'Brend tag',
                        'phone' => 'Telefon',
                        'email' => 'Email',
                        'address' => 'Manzil',
                        'website' => 'Website',
                        'facebook_url' => 'Facebook havolasi',
                        'telegram_url' => 'Telegram havolasi',
                        'instagram_url' => 'Instagram havolasi',
                        'youtube_url' => 'YouTube havolasi',
                        'hero_eyebrow' => 'Hero kichik matn',
                        'hero_title' => 'Hero sarlavha',
                        'hero_highlight' => 'Hero ajratilgan so‘z',
                        'services_subtitle' => 'Xizmatlar tag',
                        'services_title' => 'Xizmatlar sarlavha',
                        'about_tag' => 'Klinika tag',
                        'about_title' => 'Klinika sarlavha',
                        'about_image' => 'Klinika rasmi URL',
                        'doctors_subtitle' => 'Shifokorlar tag',
                        'doctors_title' => 'Shifokorlar sarlavha',
                        'testimonials_subtitle' => 'Fikrlar tag',
                        'testimonials_title' => 'Fikrlar sarlavha',
                        'appointment_title' => 'Qabul sarlavha',
                        'appointment_image' => 'Qabul rasmi URL',
                        'appointment_hours' => 'Ish vaqti',
                        'news_subtitle' => 'Yangiliklar tag',
                        'news_title' => 'Yangiliklar sarlavha',
                        'footer_copyright' => 'Copyright',
                    ] as $name => $label)
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="{{ $name }}">{{ $label }}</label>
                            <input class="form-control" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $settings->{$name}) }}">
                            @foreach ($locales as $locale => $localeLabel)
                                <div class="input-group input-group-sm mt-2">
                                    <span class="input-group-text">{{ $localeLabel }}</span>
                                    <input class="form-control" name="translations[{{ $name }}][{{ $locale }}]" value="{{ old("translations.$name.$locale", $settings->translations[$name][$locale] ?? ($locale === 'uz' ? $settings->{$name} : '')) }}">
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="col-12 mb-3">
                        <label class="form-label" for="map_embed_url">Xarita embed havolasi</label>
                        <textarea class="form-control" id="map_embed_url" name="map_embed_url" rows="3" placeholder="Google/Yandex xarita iframe kodi yoki embed URL">{{ old('map_embed_url', $settings->map_embed_url) }}</textarea>
                        <div class="form-text">Google Maps yoki Yandex Maps’dan olingan iframe kodini to‘liq joylasangiz ham bo‘ladi.</div>
                    </div>

                    @foreach ([
                        'hero_text' => 'Hero matn',
                        'services_text' => 'Xizmatlar matni',
                        'about_text' => 'Klinika matni',
                        'appointment_text' => 'Qabul matni',
                        'footer_text' => 'Footer matni',
                    ] as $name => $label)
                        <div class="col-12 mb-3">
                            <label class="form-label" for="{{ $name }}">{{ $label }}</label>
                            <textarea class="form-control" id="{{ $name }}" name="{{ $name }}" rows="3">{{ old($name, $settings->{$name}) }}</textarea>
                            @foreach ($locales as $locale => $localeLabel)
                                <div class="input-group input-group-sm mt-2">
                                    <span class="input-group-text">{{ $localeLabel }}</span>
                                    <textarea class="form-control" name="translations[{{ $name }}][{{ $locale }}]" rows="2">{{ old("translations.$name.$locale", $settings->translations[$name][$locale] ?? ($locale === 'uz' ? $settings->{$name} : '')) }}</textarea>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="col-12 mb-3">
                        <label class="form-label" for="hero_features_text">Hero xususiyatlari</label>
                        <textarea class="form-control" id="hero_features_text" name="hero_features_text" rows="4">{{ old('hero_features_text', collect($settings->hero_features)->map(fn ($item) => ($item['icon'] ?? '').'|'.($item['text'] ?? ''))->implode("\n")) }}</textarea>
                        <div class="form-text">Har qatorda: ikonka|matn</div>
                        @foreach ($locales as $locale => $localeLabel)
                            <div class="input-group input-group-sm mt-2">
                                <span class="input-group-text">{{ $localeLabel }}</span>
                                <textarea class="form-control" name="translations[hero_features_text][{{ $locale }}]" rows="3">{{ old("translations.hero_features_text.$locale", collect($settings->translations['hero_features'][$locale] ?? ($locale === 'uz' ? $settings->hero_features : []))->map(fn ($item) => ($item['icon'] ?? '').'|'.($item['text'] ?? ''))->implode("\n")) }}</textarea>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label" for="stats_text">Statistika</label>
                        <textarea class="form-control" id="stats_text" name="stats_text" rows="5">{{ old('stats_text', collect($settings->stats)->map(fn ($item) => ($item['icon'] ?? '').'|'.($item['value'] ?? '').'|'.($item['label'] ?? ''))->implode("\n")) }}</textarea>
                        <div class="form-text">Har qatorda: ikonka|qiymat|nomi</div>
                        @foreach ($locales as $locale => $localeLabel)
                            <div class="input-group input-group-sm mt-2">
                                <span class="input-group-text">{{ $localeLabel }}</span>
                                <textarea class="form-control" name="translations[stats_text][{{ $locale }}]" rows="4">{{ old("translations.stats_text.$locale", collect($settings->translations['stats'][$locale] ?? ($locale === 'uz' ? $settings->stats : []))->map(fn ($item) => ($item['icon'] ?? '').'|'.($item['value'] ?? '').'|'.($item['label'] ?? ''))->implode("\n")) }}</textarea>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label" for="about_items_text">Klinika punktlari</label>
                        <textarea class="form-control" id="about_items_text" name="about_items_text" rows="4">{{ old('about_items_text', collect($settings->about_items)->implode("\n")) }}</textarea>
                        <div class="form-text">Har qatorda bitta punkt.</div>
                        @foreach ($locales as $locale => $localeLabel)
                            <div class="input-group input-group-sm mt-2">
                                <span class="input-group-text">{{ $localeLabel }}</span>
                                <textarea class="form-control" name="translations[about_items_text][{{ $locale }}]" rows="3">{{ old("translations.about_items_text.$locale", collect($settings->translations['about_items'][$locale] ?? ($locale === 'uz' ? $settings->about_items : []))->implode("\n")) }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Saqlash</button>
            </div>
        </div>
    </form>
@endsection
