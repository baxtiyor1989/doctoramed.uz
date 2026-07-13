<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'url', 'video_titles', 'translations', 'sort_order', 'is_active'])]
class HeroVideo extends Model
{
    use HasTranslations;

    protected function casts(): array
    {
        return [
            'translations' => 'array',
            'video_titles' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function embedUrl(): string
    {
        return $this->embedUrlFor($this->url);
    }

    public function thumbnailUrl(): ?string
    {
        return $this->thumbnailUrlFor($this->url);
    }

    public function videoItems(string $locale): array
    {
        $urls = $this->urlList();
        $titles = $this->video_titles ?? [];

        return collect($urls)
            ->map(fn (string $url, int $index) => [
                'title' => $titles[$index] ?? $this->fallbackTitle($locale, $index, count($urls)),
                'embed_url' => $this->embedUrlFor($url),
                'thumbnail_url' => $this->thumbnailUrlFor($url),
            ])
            ->all();
    }

    private function fallbackTitle(string $locale, int $index, int $count): string
    {
        $title = $this->tr('title', $locale) ?: 'Video';

        return $title.($count > 1 ? ' '.($index + 1) : '');
    }

    public function urlList(): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $this->url))
            ->map(fn (string $url) => trim($url))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function embedUrlFor(string $url): string
    {
        $id = $this->youtubeId($url);

        return $id ? "https://www.youtube.com/embed/{$id}" : $url;
    }

    public function thumbnailUrlFor(string $url): ?string
    {
        $id = $this->youtubeId($url);

        return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : null;
    }

    public function youtubeId(?string $url = null): ?string
    {
        $url ??= $this->url;

        if (preg_match('/youtu\.be\/([A-Za-z0-9_-]{6,})/', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('/[?&]v=([A-Za-z0-9_-]{6,})/', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('/youtube\.com\/embed\/([A-Za-z0-9_-]{6,})/', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('/youtube\.com\/shorts\/([A-Za-z0-9_-]{6,})/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
