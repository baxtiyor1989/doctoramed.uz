<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
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
}
