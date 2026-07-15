<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\AboutSlide;
use App\Models\AppointmentType;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\HeroVideo;
use App\Models\MenuItem;
use App\Models\Partner;
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
            'vacancies' => Vacancy::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'branches' => Branch::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'appointmentTypes' => AppointmentType::query()->where('is_active', true)->orderBy('sort_order')->get(),
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
        return MenuItem::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with(['childrenRecursive' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (MenuItem $menu) => $this->formatMenu($menu, $locale));
    }

    private function formatMenu(MenuItem $menu, string $locale): array
    {
        return [
            'id' => $menu->id,
            'title' => $menu->tr('title', $locale),
            'url' => $this->menuUrl($menu->url, $locale),
            'children' => $menu->childrenRecursive
                ->where('is_active', true)
                ->map(fn (MenuItem $child) => $this->formatMenu($child, $locale))
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
