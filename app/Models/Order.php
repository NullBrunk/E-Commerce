<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_buyer",
        "id_seller",
        "id_product",   
        "quantity",
        "price",
        "reference",
    ];
}
