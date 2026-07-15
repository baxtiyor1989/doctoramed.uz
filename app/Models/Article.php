<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasTranslations;

#[Fillable(['title', 'excerpt', 'body', 'image', 'gallery_images', 'published_at', 'translations', 'sort_order', 'is_active'])]
class Article extends Model
{
    use HasTranslations;

    protected function casts(): array
    {
        return [
            'translations' => 'array',
            'gallery_images' => 'array',
            'published_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    protected function image(): Attribute
    {
        return Attribute::get(fn (?string $value) => $this->mediaUrl($value, 'articles'));
    }

    protected function galleryImages(): Attribute
    {
        return Attribute::get(function ($value) {
            $images = is_array($value) ? $value : json_decode($value ?: '[]', true);

            return collect($images)
                ->filter()
                ->map(fn (string $image) => $this->mediaUrl($image, 'articles'))
                ->values()
                ->all();
        });
    }

    private function mediaUrl(?string $value, string $defaultDirectory): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        $path = ltrim($value, '/');

        if (! str_contains($path, '/')) {
            $path = $defaultDirectory.'/'.$path;
        }

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return route('media.show', ['path' => $path]);
    }
}
