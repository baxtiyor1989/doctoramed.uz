<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['full_name', 'birth_date', 'region_district', 'phone', 'appointment_type'])]
class AppointmentApplication extends Model
{
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }
}
