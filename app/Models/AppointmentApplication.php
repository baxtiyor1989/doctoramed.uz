<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['last_name', 'first_name', 'full_name', 'birth_date', 'region_id', 'district_id', 'region_district', 'address', 'complaint', 'phone', 'appointment_type', 'viewed_at'])]
class AppointmentApplication extends Model
{
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'viewed_at' => 'datetime',
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
