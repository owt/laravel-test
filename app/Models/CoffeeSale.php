<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoffeeSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quantity',
        'unit_cost',
        'selling_price',
    ];

}
