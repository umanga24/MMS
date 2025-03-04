<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'brand_id',
        'price',
        'qty',
        'total',
        'title'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
