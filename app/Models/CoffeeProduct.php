<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoffeeProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'profit_margin',
    ];

}
