<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalShippingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'province',
        'city',
        'district',
        'rate_per_kg',
        'min_rate',
    ];
}