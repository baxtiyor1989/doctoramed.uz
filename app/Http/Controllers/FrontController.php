<?php

namespace App\Http\Controllers;

use App\Models\AboutSlide;
use App\Models\AppointmentType;
use App\Models\Article;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\HeroVideo;
use App\Models\MenuItem;
use App\Models\Partner;
use App\Models\Region;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontController extends Controller
{
    public function __invoke(?string $locale = null): View
    {
        $locale = in_array($locale, ['uz', 'ru', 'en'], true) ? $locale : 'uz';
        app()->setLocale($locale);

        $heroVideos = HeroVideo::query()->where('is_active', true)->orderBy('sort_order')->get();
        $heroVideoItems = $heroVideos
            ->flatMap(fn (HeroVideo $video) => $video->videoItems($locale))
            ->values();

        return view('front.home', [
            'locale' => $locale,
            'settings' => SiteSetting::current(),
            'services' => Service::query()->with('menuItem.parentRecursive')->where('is_active', true)->orderBy('sort_order')->get(),
            'aboutSlides' => AboutSlide::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'doctors' => Doctor::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'testimonials' => Testimonial::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'articles' => $this->articleQuery()->limit(4)->get(),
            'partners' => Partner::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'vacancies' => Vacancy::query()->where('is_active', true)->whereNotNull('branch_id')->orderBy('sort_order')->get(),
            'branches' => Branch::query()
                ->where('is_active', true)
                ->whereHas('vacancies', fn ($query) => $query->where('is_active', true))
                ->orderBy('sort_order')
                ->get(),
            'appointmentTypes' => AppointmentType::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'regions' => $this->appointmentRegions(),
            'heroVideos' => $heroVideos,
            'heroVideoItems' => $heroVideoItems,
            'frontMenus' => $this->frontMenus($locale),
        ]);
    }

    public function news(): View
    {
        return $this->renderNews('uz');
    }

    public function localizedNews(string $locale): View
    {
        return $this->renderNews($locale);
    }

    public function article(Article $article): View
    {
        return $this->renderArticle('uz', $article);
    }

    public function localizedArticle(string $locale, Article $article): View
    {
        return $this->renderArticle($locale, $article);
    }

    public function doctor(Doctor $doctor): View
    {
        return $this->renderDoctor('uz', $doctor);
    }

    public function localizedDoctor(string $locale, Doctor $doctor): View
    {
        return $this->renderDoctor($locale, $doctor);
    }

    public function doctors(Request $request): View
    {
        return $this->renderDoctors('uz', $request);
    }

    public function localizedDoctors(string $locale, Request $request): View
    {
        return $this->renderDoctors($locale, $request);
    }

    public function filterServices(Request $request): JsonResponse
    {
        return $this->renderFilteredServices('uz', $request);
    }

    public function localizedFilterServices(string $locale, Request $request): JsonResponse
    {
        return $this->renderFilteredServices($locale, $request);
    }

    private function renderNews(string $locale): View
    {
        $locale = $this->setLocale($locale);

        return view('front.news-index', [
            'locale' => $locale,
            'settings' => SiteSetting::current(),
            'services' => Service::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'articles' => $this->articleQuery()->paginate(12),
            'frontMenus' => $this->frontMenus($locale),
        ]);
    }

    private function renderArticle(string $locale, Article $article): View
    {
        $locale = $this->setLocale($locale);
        abort_unless($article && $article->is_active, 404);

        return view('front.news-show', [
            'locale' => $locale,
            'settings' => SiteSetting::current(),
            'services' => Service::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'article' => $article,
            'relatedArticles' => $this->articleQuery()->whereKeyNot($article->getKey())->limit(4)->get(),
            'frontMenus' => $this->frontMenus($locale),
        ]);
    }

    private function renderDoctor(string $locale, Doctor $doctor): View
    {
        $locale = $this->setLocale($locale);
        abort_unless($doctor->is_active, 404);

        return view('front.doctor-show', [
            'locale' => $locale,
            'settings' => SiteSetting::current(),
            'services' => Service::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'doctor' => $doctor,
            'relatedDoctors' => Doctor::query()
                ->where('is_active', true)
                ->whereKeyNot($doctor->getKey())
                ->orderBy('sort_order')
                ->limit(4)
                ->get(),
            'appointmentTypes' => AppointmentType::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'regions' => $this->appointmentRegions(),
            'frontMenus' => $this->frontMenus($locale),
            'languageRoutes' => [
                'uz' => route('front.doctors.show', $doctor),
                'ru' => route('front.locale.doctors.show', ['ru', $doctor]),
                'en' => route('front.locale.doctors.show', ['en', $doctor]),
            ],
            'activeMenuUrl' => '#doctors',
        ]);
    }

    private function renderDoctors(string $locale, Request $request): View
    {
        $locale = $this->setLocale($locale);
        $menuId = (int) $request->query('menu_id');
        $selectedMenu = $menuId > 0
            ? MenuItem::query()->where('is_active', true)->findOrFail($menuId)
            : null;
        $menuIds = $selectedMenu ? $this->menuWithDescendantIds($selectedMenu->id) : collect();

        $doctors = Doctor::query()
            ->where('is_active', true)
            ->when($selectedMenu, fn ($query) => $query->whereIn('menu_item_id', $menuIds))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('front.doctors-index', [
            'locale' => $locale,
            'settings' => SiteSetting::current(),
            'services' => Service::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'doctors' => $doctors,
            'selectedMenu' => $selectedMenu,
            'frontMenus' => $this->frontMenus($locale),
            'languageRoutes' => [
                'uz' => route('front.doctors.index', array_filter(['menu_id' => $selectedMenu?->id])),
                'ru' => route('front.locale.doctors.index', array_filter(['locale' => 'ru', 'menu_id' => $selectedMenu?->id])),
                'en' => route('front.locale.doctors.index', array_filter(['locale' => 'en', 'menu_id' => $selectedMenu?->id])),
            ],
            'activeMenuUrl' => $selectedMenu ? 'doctors?menu_id='.$selectedMenu->id : '#doctors',
        ]);
    }

    private function appointmentRegions()
    {
        return Region::query()
            ->where('is_active', true)
            ->with(['districts' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }

    private function renderFilteredServices(string $locale, Request $request): JsonResponse
    {
        $locale = $this->setLocale($locale);
        $menuId = (int) $request->query('menu_id');
        $menuIds = $menuId > 0 ? $this->menuWithDescendantIds($menuId) : collect();

        $services = Service::query()
            ->where('is_active', true)
            ->when($menuIds->isNotEmpty(), fn ($query) => $query->whereIn('menu_item_id', $menuIds))
            ->orderBy('sort_order')
            ->get();

        if ($menuIds->isNotEmpty() && $services->isEmpty()) {
            $services = Service::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }

        return response()->json([
            'html' => view('front.partials.service-cards', [
                'services' => $services,
                'locale' => $locale,
                'detailsText' => [
                    'uz' => 'Batafsil',
                    'ru' => 'Подробнее',
                    'en' => 'Details',
                ][$locale] ?? 'Batafsil',
            ])->render(),
            'count' => $services->count(),
        ]);
    }

    private function setLocale(?string $locale = null): string
    {
        $locale = in_array($locale, ['uz', 'ru', 'en'], true) ? $locale : 'uz';
        app()->setLocale($locale);

        return $locale;
    }

    private function articleQuery()
    {
        return Article::query()
            ->where('is_active', true)
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }

    private function frontMenus(string $locale)
    {
        $directDoctorMenuIds = Doctor::query()
            ->where('is_active', true)
            ->whereNotNull('menu_item_id')
            ->pluck('menu_item_id')
            ->unique();
        $doctorMenuIds = $directDoctorMenuIds->values();
        $currentMenuIds = $directDoctorMenuIds;

        while ($currentMenuIds->isNotEmpty()) {
            $parentIds = MenuItem::query()
                ->whereIn('id', $currentMenuIds)
                ->whereNotNull('parent_id')
                ->pluck('parent_id')
                ->unique();

            $doctorMenuIds = $doctorMenuIds->merge($parentIds)->unique()->values();
            $currentMenuIds = $parentIds;
        }

        return MenuItem::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with(['childrenRecursive' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (MenuItem $menu) => $this->formatMenu($menu, $locale, $doctorMenuIds));
    }

    private function formatMenu(MenuItem $menu, string $locale, $doctorMenuIds): array
    {
        return [
            'id' => $menu->id,
            'title' => $menu->tr('title', $locale),
            'has_doctors' => $doctorMenuIds->contains($menu->id),
            'url' => $doctorMenuIds->contains($menu->id)
                ? ($locale === 'uz'
                    ? route('front.doctors.index', ['menu_id' => $menu->id], false)
                    : route('front.locale.doctors.index', ['locale' => $locale, 'menu_id' => $menu->id], false))
                : $this->menuUrl($menu->url, $locale),
            'children' => $menu->childrenRecursive
                ->where('is_active', true)
                ->map(fn (MenuItem $child) => $this->formatMenu($child, $locale, $doctorMenuIds))
                ->values(),
        ];
    }

    private function menuUrl(?string $url, string $locale): string
    {
        $url = trim((string) $url);
        $homeUrl = $locale === 'uz' ? route('front.home') : route('front.locale', $locale);

        if ($url === '') {
            return $homeUrl;
        }

        if (str_starts_with($url, '#')) {
            return $homeUrl.$url;
        }

        if ($url === 'services' || str_starts_with($url, 'services?')) {
            return $homeUrl.'#services';
        }

        if ($url === '/news') {
            return $locale === 'uz' ? route('front.news') : route('front.locale.news', $locale);
        }

        if (str_starts_with($url, '/')) {
            return url($locale === 'uz' ? $url : '/'.$locale.$url);
        }

        return $url;
    }

    private function menuWithDescendantIds(int $menuId)
    {
        $ids = collect([$menuId]);
        $current = collect([$menuId]);

        while ($current->isNotEmpty()) {
            $children = MenuItem::query()
                ->whereIn('parent_id', $current)
                ->pluck('id');

            $ids = $ids->merge($children);
            $current = $children;
        }

        return $ids->unique()->values();
    }
}
