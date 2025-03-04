<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'is_active',
        'status',
        'elapsed_time',
        'last_updated_at'
    ];

    protected $dates = ['last_updated_at'];
}
