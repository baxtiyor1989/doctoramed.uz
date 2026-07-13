<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['full_name', 'phone', 'position', 'branch', 'message'])]
class ResumeApplication extends Model
{
}
