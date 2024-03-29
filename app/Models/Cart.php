<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "id",
        "id_user",
        "id_product",
        "quantity",
    ];

    
    public function product()
    {
        return $this->belongsTo(Product::class, "id_product");
    }
}
