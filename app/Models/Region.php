<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'translations', 'sort_order', 'is_active'])]
class Region extends Model
{
    use HasTranslations;

    protected function casts(): array
    {
        return ['translations' => 'array', 'is_active' => 'boolean'];
    }

    public function districts(): HasMany
    {
        return $this->hasMany(District::class)->orderBy('sort_order')->orderBy('title');
    }
}
