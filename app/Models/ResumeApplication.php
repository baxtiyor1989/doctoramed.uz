<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['last_name', 'first_name', 'full_name', 'birth_date', 'region_id', 'district_id', 'region_district', 'address', 'branch_id', 'vacancy_id', 'phone', 'position', 'branch', 'message'])]
class ResumeApplication extends Model
{
    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function branchRelation(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }
}
