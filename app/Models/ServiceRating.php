<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['score', 'voter_hash', 'locale'])]
class ServiceRating extends Model
{
}
