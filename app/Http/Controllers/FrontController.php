<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\AboutSlide;
use App\Models\AppointmentType;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\HeroVideo;
use App\Models\Partner;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\Vacancy;
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
            'services' => Service::query()->where('is_active', true)->orderBy('sort_order')->get(),
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

    private function renderNews(string $locale): View
    {
        $locale = $this->setLocale($locale);

        return view('front.news-index', [
            'locale' => $locale,
            'settings' => SiteSetting::current(),
            'services' => Service::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'articles' => $this->articleQuery()->paginate(12),
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
}
