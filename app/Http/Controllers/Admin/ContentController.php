<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSlide;
use App\Models\AppointmentType;
use App\Models\Article;
use App\Models\Branch;
use App\Models\District;
use App\Models\Doctor;
use App\Models\HeroVideo;
use App\Models\MenuItem;
use App\Models\Partner;
use App\Models\Region;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\Vacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContentController extends Controller
{
    private array $locales = ['uz' => 'O‘zbekcha', 'ru' => 'Русский', 'en' => 'English'];

    private array $resources = [
        'services' => [
            'title' => 'Xizmatlar',
            'model' => Service::class,
            'fields' => [
                'icon' => ['label' => 'Ikonka PNG yuklash', 'type' => 'file', 'accept' => 'image/png,image/*'],
                'menu_item_id' => ['label' => 'Qaysi menuga mansub', 'type' => 'select', 'options' => []],
                'title' => ['label' => 'Nomi', 'type' => 'text', 'required' => true, 'translatable' => true],
                'description' => ['label' => 'Tavsif', 'type' => 'textarea', 'translatable' => true],
                'sort_order' => ['label' => 'Tartib', 'type' => 'number'],
                'is_active' => ['label' => 'Faol', 'type' => 'checkbox'],
            ],
        ],
        'about-slides' => [
            'title' => 'Klinika slaydlari',
            'model' => AboutSlide::class,
            'fields' => [
                'tag' => ['label' => 'Tag', 'type' => 'text', 'translatable' => true],
                'title' => ['label' => 'Sarlavha', 'type' => 'text', 'required' => true, 'translatable' => true],
                'text' => ['label' => 'Matn', 'type' => 'textarea', 'translatable' => true],
                'items' => ['label' => 'Punktlar (har qatorda bittadan)', 'type' => 'textarea', 'translatable' => true],
                'image' => ['label' => 'Rasm yuklash', 'type' => 'file', 'accept' => 'image/*'],
                'sort_order' => ['label' => 'Tartib', 'type' => 'number'],
                'is_active' => ['label' => 'Faol', 'type' => 'checkbox'],
            ],
        ],
        'doctors' => [
            'title' => 'Shifokorlar',
            'model' => Doctor::class,
            'fields' => [
                'menu_item_id' => ['label' => 'Qaysi menuga mansub', 'type' => 'select', 'options' => []],
                'name' => ['label' => 'Ism', 'type' => 'text', 'required' => true, 'translatable' => true],
                'specialty' => ['label' => 'Mutaxassislik', 'type' => 'text', 'translatable' => true],
                'experience' => ['label' => 'Tajriba', 'type' => 'text', 'translatable' => true],
                'category' => ['label' => 'Toifa / daraja', 'type' => 'text', 'translatable' => true],
                'education' => ['label' => 'Ta’lim va malaka', 'type' => 'textarea', 'translatable' => true],
                'work_schedule' => ['label' => 'Ish jadvali', 'type' => 'text', 'translatable' => true],
                'bio' => ['label' => 'Shifokor haqida batafsil', 'type' => 'richtext', 'translatable' => true],
                'image' => ['label' => 'Rasm yuklash', 'type' => 'file', 'accept' => 'image/*'],
                'sort_order' => ['label' => 'Tartib', 'type' => 'number'],
                'is_active' => ['label' => 'Faol', 'type' => 'checkbox'],
            ],
        ],
        'testimonials' => [
            'title' => 'Bemorlar fikri',
            'model' => Testimonial::class,
            'fields' => [
                'name' => ['label' => 'Ism', 'type' => 'text', 'required' => true, 'translatable' => true],
                'role' => ['label' => 'Rol', 'type' => 'text', 'translatable' => true],
                'text' => ['label' => 'Fikr', 'type' => 'textarea', 'required' => true, 'translatable' => true],
                'sort_order' => ['label' => 'Tartib', 'type' => 'number'],
                'is_active' => ['label' => 'Faol', 'type' => 'checkbox'],
            ],
        ],
        'articles' => [
            'title' => 'Yangiliklar',
            'model' => Article::class,
            'fields' => [
                'title' => ['label' => 'Sarlavha', 'type' => 'text', 'required' => true, 'translatable' => true],
                'excerpt' => ['label' => 'Qisqa matn', 'type' => 'richtext', 'translatable' => true],
                'body' => ['label' => 'Asosiy matn', 'type' => 'richtext', 'translatable' => true],
                'image' => ['label' => 'Asosiy rasm', 'type' => 'file', 'accept' => 'image/*'],
                'gallery_images' => ['label' => 'Slider rasmlari', 'type' => 'multi_file', 'accept' => 'image/*'],
                'published_at' => ['label' => 'Sana', 'type' => 'date'],
                'sort_order' => ['label' => 'Tartib', 'type' => 'number'],
                'is_active' => ['label' => 'Faol', 'type' => 'checkbox'],
            ],
        ],
        'partners' => [
            'title' => 'Hamkorlar',
            'model' => Partner::class,
            'fields' => [
                'name' => ['label' => 'Nomi', 'type' => 'text', 'required' => true, 'translatable' => true],
                'logo' => ['label' => 'Logo yuklash', 'type' => 'file', 'accept' => 'image/*'],
                'sort_order' => ['label' => 'Tartib', 'type' => 'number'],
                'is_active' => ['label' => 'Faol', 'type' => 'checkbox'],
            ],
        ],
        'hero-videos' => [
            'title' => 'Hero videolar',
            'model' => HeroVideo::class,
            'fields' => [
                'title' => ['label' => 'Video nomi', 'type' => 'text', 'translatable' => true],
                'url' => ['label' => 'YouTube havola', 'type' => 'textarea', 'required' => true],
                'sort_order' => ['label' => 'Tartib', 'type' => 'number'],
                'is_active' => ['label' => 'Faol', 'type' => 'checkbox'],
            ],
        ],
        'vacancies' => [
            'title' => 'Vakant lavozimlar',
            'model' => Vacancy::class,
            'fields' => [
                'branch_id' => ['label' => 'Qaysi filialga tegishli', 'type' => 'select', 'required' => true, 'options' => []],
                'title' => ['label' => 'Lavozim nomi', 'type' => 'text', 'required' => true, 'translatable' => true],
                'sort_order' => ['label' => 'Tartib', 'type' => 'number'],
                'is_active' => ['label' => 'Faol', 'type' => 'checkbox'],
            ],
        ],
        'branches' => [
            'title' => 'Filiallar',
            'model' => Branch::class,
            'fields' => [
                'title' => ['label' => 'Filial nomi', 'type' => 'text', 'required' => true, 'translatable' => true],
                'sort_order' => ['label' => 'Tartib', 'type' => 'number'],
                'is_active' => ['label' => 'Faol', 'type' => 'checkbox'],
            ],
        ],
        'appointment-types' => [
            'title' => 'Qabul yo‘nalishlari',
            'model' => AppointmentType::class,
            'fields' => [
                'title' => ['label' => 'Shifokor yoki tekshiruv nomi', 'type' => 'text', 'required' => true, 'translatable' => true],
                'sort_order' => ['label' => 'Tartib', 'type' => 'number'],
                'is_active' => ['label' => 'Faol', 'type' => 'checkbox'],
            ],
        ],
        'regions' => [
            'title' => 'Viloyatlar',
            'model' => Region::class,
            'fields' => [
                'title' => ['label' => 'Viloyat nomi', 'type' => 'text', 'required' => true, 'translatable' => true],
                'sort_order' => ['label' => 'Tartib', 'type' => 'number'],
                'is_active' => ['label' => 'Faol', 'type' => 'checkbox'],
            ],
        ],
        'districts' => [
            'title' => 'Tumanlar',
            'model' => District::class,
            'fields' => [
                'region_id' => ['label' => 'Qaysi viloyatga tegishli', 'type' => 'select', 'required' => true, 'options' => []],
                'title' => ['label' => 'Tuman nomi', 'type' => 'text', 'required' => true, 'translatable' => true],
                'sort_order' => ['label' => 'Tartib', 'type' => 'number'],
                'is_active' => ['label' => 'Faol', 'type' => 'checkbox'],
            ],
        ],
        'menus' => [
            'title' => 'Front menyular',
            'model' => MenuItem::class,
            'fields' => [
                'parent_id' => ['label' => 'Parent menu', 'type' => 'select', 'options' => []],
                'title' => ['label' => 'Menu nomi', 'type' => 'text', 'required' => true, 'translatable' => true],
                'url' => ['label' => 'Havola (#services, /news yoki https://...)', 'type' => 'text', 'required' => true],
                'sort_order' => ['label' => 'Tartib', 'type' => 'number'],
                'is_active' => ['label' => 'Faol', 'type' => 'checkbox'],
            ],
        ],
    ];

    public function settings(): View
    {
        return view('admin.content.settings', [
            'settings' => SiteSetting::current(),
            'locales' => $this->locales,
        ]);
    }

    public function branchesVacancies(): View
    {
        return view('admin.content.branches-vacancies', [
            'branches' => Branch::query()
                ->withCount('vacancies')
                ->orderBy('sort_order')
                ->orderBy('title')
                ->get(),
            'vacancies' => Vacancy::query()
                ->with('branch')
                ->orderBy('sort_order')
                ->orderBy('title')
                ->get(),
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_title' => ['required', 'string'],
            'brand_name' => ['required', 'string'],
            'brand_subtitle' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'email' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'map_embed_url' => ['nullable', 'string'],
            'website' => ['nullable', 'string'],
            'facebook_url' => ['nullable', 'string'],
            'telegram_url' => ['nullable', 'string'],
            'instagram_url' => ['nullable', 'string'],
            'youtube_url' => ['nullable', 'string'],
            'hero_eyebrow' => ['nullable', 'string'],
            'hero_title' => ['nullable', 'string'],
            'hero_highlight' => ['nullable', 'string'],
            'hero_text' => ['nullable', 'string'],
            'hero_features_text' => ['nullable', 'string'],
            'stats_text' => ['nullable', 'string'],
            'services_subtitle' => ['nullable', 'string'],
            'services_title' => ['nullable', 'string'],
            'services_text' => ['nullable', 'string'],
            'about_tag' => ['nullable', 'string'],
            'about_title' => ['nullable', 'string'],
            'about_text' => ['nullable', 'string'],
            'about_image' => ['nullable', 'string'],
            'about_items_text' => ['nullable', 'string'],
            'doctors_subtitle' => ['nullable', 'string'],
            'doctors_title' => ['nullable', 'string'],
            'testimonials_subtitle' => ['nullable', 'string'],
            'testimonials_title' => ['nullable', 'string'],
            'appointment_title' => ['nullable', 'string'],
            'appointment_text' => ['nullable', 'string'],
            'appointment_image' => ['nullable', 'string'],
            'appointment_hours' => ['nullable', 'string'],
            'news_subtitle' => ['nullable', 'string'],
            'news_title' => ['nullable', 'string'],
            'footer_text' => ['nullable', 'string'],
            'footer_copyright' => ['nullable', 'string'],
        ]);

        $data['hero_features'] = $this->parsePairs($data['hero_features_text'] ?? '', ['icon', 'text']);
        $data['stats'] = $this->parsePairs($data['stats_text'] ?? '', ['icon', 'value', 'label']);
        $data['about_items'] = $this->parseLines($data['about_items_text'] ?? '');
        $data['map_embed_url'] = $this->normalizeMapEmbedUrl($data['map_embed_url'] ?? null);
        $data['translations'] = $this->settingsTranslations($request);
        foreach (array_keys($this->locales) as $locale) {
            $data['translations']['hero_features'][$locale] = $this->parsePairs($request->input("translations.hero_features_text.$locale", ''), ['icon', 'text']);
            $data['translations']['stats'][$locale] = $this->parsePairs($request->input("translations.stats_text.$locale", ''), ['icon', 'value', 'label']);
            $data['translations']['about_items'][$locale] = $this->parseLines($request->input("translations.about_items_text.$locale", ''));
        }
        unset($data['hero_features_text'], $data['stats_text'], $data['about_items_text']);

        SiteSetting::current()->update($data);

        return back()->with('status', 'Sozlamalar saqlandi.');
    }

    public function clearCache(): RedirectResponse
    {
        Artisan::call('optimize:clear');

        return back()->with('status', 'Cachelar tozalandi.');
    }

    public function index(string $resource): View
    {
        $config = $this->resource($resource);
        $query = $config['model']::query()->orderBy('sort_order')->orderBy('id');

        if ($resource === 'menus') {
            $items = MenuItem::query()
                ->whereNull('parent_id')
                ->with('childrenRecursive')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            return view('admin.content.index', compact('resource', 'config', 'items'));
        }

        if (in_array($resource, ['services', 'doctors'], true)) {
            $query->with('menuItem');
        }

        if ($resource === 'districts') {
            $query->with('region');
        }

        if ($resource === 'vacancies') {
            $query->with('branch');
        }

        $items = $query->get();

        return view('admin.content.index', compact('resource', 'config', 'items'));
    }

    public function create(string $resource): View
    {
        $config = $this->resource($resource);
        $item = new $config['model'](['is_active' => true, 'sort_order' => 0]);

        $locales = $this->locales;

        return view('admin.content.form', compact('resource', 'config', 'item', 'locales'));
    }

    public function store(Request $request, string $resource): RedirectResponse
    {
        $config = $this->resource($resource);
        $data = $this->validatedData($request, $config, $resource);
        $config['model']::create($data);

        $route = in_array($resource, ['branches', 'vacancies'], true)
            ? route('admin.branches-vacancies')
            : route('admin.content.index', $resource);

        return redirect($route)->with('status', 'Ma’lumot qo‘shildi.');
    }

    public function edit(string $resource, int $id): View
    {
        $config = $this->resource($resource);
        $item = $config['model']::findOrFail($id);

        $locales = $this->locales;

        return view('admin.content.form', compact('resource', 'config', 'item', 'locales'));
    }

    public function update(Request $request, string $resource, int $id): RedirectResponse
    {
        $config = $this->resource($resource);
        $item = $config['model']::findOrFail($id);
        $data = $this->validatedData($request, $config, $resource);

        if ($resource === 'menus') {
            $parentId = (int) ($data['parent_id'] ?? 0);
            if ($parentId === $item->id || $this->descendantMenuIds($item->id)->contains($parentId)) {
                $data['parent_id'] = null;
            }
        }

        $item->update($data);

        $route = in_array($resource, ['branches', 'vacancies'], true)
            ? route('admin.branches-vacancies')
            : route('admin.content.index', $resource);

        return redirect($route)->with('status', 'Ma’lumot yangilandi.');
    }

    public function destroy(string $resource, int $id): RedirectResponse
    {
        $config = $this->resource($resource);
        $config['model']::findOrFail($id)->delete();

        return back()->with('status', 'Ma’lumot o‘chirildi.');
    }

    private function resource(string $resource): array
    {
        abort_unless(isset($this->resources[$resource]), 404);

        $config = $this->resources[$resource];

        if ($resource === 'menus') {
            $menuOptions = MenuItem::query()
                ->whereNull('parent_id')
                ->with('childrenRecursive')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            $config['fields']['parent_id']['options'] = ['' => 'Parent yo‘q'] + $this->menuParentOptions($menuOptions);
        }

        if (in_array($resource, ['services', 'doctors'], true)) {
            $menuOptions = MenuItem::query()
                ->whereNull('parent_id')
                ->with('childrenRecursive')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            $config['fields']['menu_item_id']['options'] = ['' => 'Menu tanlanmagan'] + $this->menuParentOptions($menuOptions);
        }

        if ($resource === 'districts') {
            $config['fields']['region_id']['options'] = Region::query()
                ->orderBy('sort_order')
                ->orderBy('title')
                ->pluck('title', 'id')
                ->all();
        }

        if ($resource === 'vacancies') {
            $config['fields']['branch_id']['options'] = Branch::query()
                ->orderBy('sort_order')
                ->orderBy('title')
                ->pluck('title', 'id')
                ->all();
        }

        return $config;
    }

    private function menuParentOptions($menus, int $level = 0)
    {
        $options = [];

        foreach ($menus as $menu) {
            $options[$menu->id] = str_repeat('— ', $level).$menu->title;
            $options += $this->menuParentOptions($menu->childrenRecursive, $level + 1);
        }

        return $options;
    }

    private function descendantMenuIds(int $menuId)
    {
        $ids = collect();
        $current = collect([$menuId]);

        while ($current->isNotEmpty()) {
            $children = MenuItem::query()
                ->whereIn('parent_id', $current)
                ->pluck('id');

            $ids = $ids->merge($children);
            $current = $children;
        }

        return $ids;
    }

    private function validatedData(Request $request, array $config, string $resource): array
    {
        $rules = [];

        foreach ($config['fields'] as $name => $field) {
            if ($field['translatable'] ?? false) {
                $requiredRule = ($field['required'] ?? false)
                    ? "required_without_all:translations.$name.ru,translations.$name.en"
                    : 'nullable';
                $rules[$name] = ['nullable', 'string'];
                $rules["translations.$name.uz"] = ['nullable', $requiredRule, 'string'];
                $rules["translations.$name.ru"] = ['nullable', ($field['required'] ?? false) ? "required_without_all:translations.$name.uz,translations.$name.en" : 'nullable', 'string'];
                $rules["translations.$name.en"] = ['nullable', ($field['required'] ?? false) ? "required_without_all:translations.$name.uz,translations.$name.ru" : 'nullable', 'string'];

                continue;
            }

            $rules[$name] = match ($field['type']) {
                'checkbox' => ['nullable', 'boolean'],
                'file' => ['nullable', 'image', 'max:2048'],
                'multi_file' => ['nullable', 'array'],
                'select' => [($field['required'] ?? false) ? 'required' : 'nullable'],
                'number' => ['nullable', 'integer', 'min:0'],
                'date' => ['nullable', 'date'],
                default => [($field['required'] ?? false) ? 'required' : 'nullable', 'string'],
            };

            if ($field['type'] === 'multi_file') {
                $rules["{$name}.*"] = ['image', 'max:2048'];
            }
        }

        if ($resource === 'districts') {
            $rules['region_id'] = ['required', 'exists:regions,id'];
        }

        if ($resource === 'vacancies') {
            $rules['branch_id'] = ['required', 'exists:branches,id'];
        }

        $data = $request->validate($rules, [
            '*.image' => 'Yuklangan fayl rasm formatida bo‘lishi kerak.',
            '*.max' => 'Rasm hajmi 2MB dan oshmasligi kerak.',
            'gallery_images.*.uploaded' => 'Slider rasmi yuklanmadi. Rasm 2MB dan kichik bo‘lishi kerak.',
            'image.uploaded' => 'Asosiy rasm yuklanmadi. Rasm 2MB dan kichik bo‘lishi kerak.',
        ]);
        $translations = [];

        foreach ($config['fields'] as $name => $field) {
            if ($field['type'] === 'checkbox') {
                $data[$name] = $request->boolean($name);
            }

            if ($field['type'] === 'select') {
                $data[$name] = filled($data[$name] ?? null) ? $data[$name] : null;
            }

            if ($field['type'] === 'file') {
                unset($data[$name]);

                if ($request->hasFile($name)) {
                    $data[$name] = Storage::url($request->file($name)->store($resource, 'public'));
                } elseif ($request->boolean("remove_{$name}")) {
                    $data[$name] = null;
                }
            }

            if ($field['type'] === 'multi_file') {
                unset($data[$name]);

                $existingImages = collect($request->input("existing_{$name}", []));
                $removedImages = collect($request->input("remove_{$name}", []));
                $images = $existingImages
                    ->reject(fn (string $image) => $removedImages->contains($image))
                    ->values();

                if ($request->hasFile($name)) {
                    $images = $images->merge(
                        collect($request->file($name))
                            ->map(fn ($file) => Storage::url($file->store($resource, 'public')))
                    );
                }

                if ($existingImages->isNotEmpty() || $removedImages->isNotEmpty() || $request->hasFile($name)) {
                    $data[$name] = $images->values()->all();
                }
            }

            if ($field['translatable'] ?? false) {
                foreach (array_keys($this->locales) as $locale) {
                    $value = $request->input("translations.$name.$locale");
                    if ($value !== null && $value !== '') {
                        $translations[$name][$locale] = $value;
                    }
                }

                if (isset($translations[$name])) {
                    $data[$name] = $translations[$name]['uz']
                        ?? $translations[$name]['ru']
                        ?? $translations[$name]['en']
                        ?? $data[$name]
                        ?? null;
                }
            }
        }

        $data['translations'] = $translations;

        if (($config['model'] ?? null) === HeroVideo::class) {
            $data = $this->prepareHeroVideoData($data);
        }

        return $data;
    }

    private function prepareHeroVideoData(array $data): array
    {
        $urls = collect(preg_split('/\r\n|\r|\n/', $data['url'] ?? ''))
            ->map(fn (string $url) => trim($url))
            ->filter()
            ->values();

        $videoTitles = $urls
            ->map(fn (string $url, int $index) => $this->fetchYoutubeTitle($url) ?: $this->fallbackVideoTitle($data, $index, $urls->count()))
            ->all();

        $data['video_titles'] = $videoTitles;

        if (empty($data['title'])) {
            $data['title'] = $videoTitles[0] ?? 'Video';
        }

        if (empty($data['translations']['title']['uz'])) {
            $data['translations']['title']['uz'] = $data['title'];
        }

        return $data;
    }

    private function fetchYoutubeTitle(string $url): ?string
    {
        try {
            $response = Http::withoutVerifying()->timeout(4)->get('https://www.youtube.com/oembed', [
                'url' => $url,
                'format' => 'json',
            ]);

            if ($response->successful()) {
                return $response->json('title');
            }
        } catch (\Throwable) {
            return null;
        }

        return null;
    }

    private function fallbackVideoTitle(array $data, int $index, int $count): string
    {
        $title = $data['translations']['title']['uz'] ?? $data['title'] ?? 'Video';

        return $title.($count > 1 ? ' '.($index + 1) : '');
    }

    private function settingsTranslations(Request $request): array
    {
        $fields = [
            'site_title', 'brand_name', 'brand_subtitle', 'phone', 'email', 'address', 'website',
            'hero_eyebrow', 'hero_title', 'hero_highlight', 'hero_text',
            'services_subtitle', 'services_title', 'services_text',
            'about_tag', 'about_title', 'about_text',
            'doctors_subtitle', 'doctors_title',
            'testimonials_subtitle', 'testimonials_title',
            'appointment_title', 'appointment_text', 'appointment_hours',
            'news_subtitle', 'news_title', 'footer_text', 'footer_copyright',
        ];

        $translations = [];

        foreach ($fields as $field) {
            foreach (array_keys($this->locales) as $locale) {
                $value = $request->input("translations.$field.$locale");
                if ($value !== null && $value !== '') {
                    $translations[$field][$locale] = $value;
                }
            }
        }

        return $translations;
    }

    private function normalizeMapEmbedUrl(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $value = trim($value);

        if (preg_match('/src=["\']([^"\']+)["\']/', $value, $matches)) {
            return $matches[1];
        }

        return $value;
    }

    private function parseLines(?string $value): array
    {
        if ($value === null || trim($value) === '') {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', $value))
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private function parsePairs(?string $value, array $keys): array
    {
        return collect($this->parseLines($value))
            ->map(function (string $line) use ($keys) {
                $parts = array_map('trim', explode('|', $line));

                return collect($keys)
                    ->mapWithKeys(fn (string $key, int $index) => [$key => $parts[$index] ?? ''])
                    ->all();
            })
            ->values()
            ->all();
    }
}
