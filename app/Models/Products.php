<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image',
        'sale_type',
        'price',
        'category_id',
        'district_id',
        'user_id',
    ];
}
