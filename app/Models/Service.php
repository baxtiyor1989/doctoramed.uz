<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasTranslations;

#[Fillable(['icon', 'title', 'description', 'translations', 'sort_order', 'is_active'])]
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
}
