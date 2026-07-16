<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\HasTranslations;

#[Fillable(['icon', 'menu_item_id', 'title', 'description', 'translations', 'sort_order', 'is_active'])]
class Service extends Model
{
    use HasTranslations;

    protected function casts(): array
    {
        return [
            'translations' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    protected function icon(): Attribute
    {
        return Attribute::get(function (?string $value) {
            if ($value === null || $value === '') {
                return null;
            }

            if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                return $value;
            }

            $path = ltrim($value, '/');
            $isImage = str_starts_with($path, 'storage/')
                || str_contains($path, '/')
                || preg_match('/\.(png|jpe?g|webp|gif|svg)$/i', $path);

            if (! $isImage) {
                return $value;
            }

            if (! str_contains($path, '/')) {
                $path = 'services/'.$path;
            }

            if (str_starts_with($path, 'storage/')) {
                $path = substr($path, strlen('storage/'));
            }

            return '/storage/'.$path;
        });
    }
}
