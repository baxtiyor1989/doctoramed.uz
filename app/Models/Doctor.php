<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\HasTranslations;

#[Fillable(['menu_item_id', 'name', 'specialty', 'experience', 'category', 'education', 'work_schedule', 'bio', 'image', 'translations', 'sort_order', 'is_active'])]
class Doctor extends Model
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
                $path = 'doctors/'.$path;
            }

            if (str_starts_with($path, 'storage/')) {
                $path = substr($path, strlen('storage/'));
            }

            return route('media.show', ['path' => $path], false);
        });
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }
}
