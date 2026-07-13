<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasTranslations;

#[Fillable(['name', 'role', 'text', 'translations', 'sort_order', 'is_active'])]
class Testimonial extends Model
{
    use HasTranslations;

    protected function casts(): array
    {
        return [
            'translations' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
