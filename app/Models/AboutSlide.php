<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['tag', 'title', 'text', 'items', 'image', 'translations', 'sort_order', 'is_active'])]
class AboutSlide extends Model
{
    use HasTranslations;

    protected function casts(): array
    {
        return [
            'translations' => 'array',
            'is_active' => 'boolean',
        ];
    }

    protected function image(): Attribute
    {
        return Attribute::get(function (?string $value) {
            if ($value === null || $value === '') {
                return null;
            }

            if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                return $value;
            }

            $path = ltrim($value, '/');

            if (! str_contains($path, '/')) {
                $path = 'about-slides/'.$path;
            }

            if (str_starts_with($path, 'storage/')) {
                $path = substr($path, strlen('storage/'));
            }

            return route('media.show', ['path' => $path]);
        });
    }
}
