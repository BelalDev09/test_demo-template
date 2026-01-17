<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'price',
        'status',
        'medias'
    ];

    protected $casts = [
        'medias' => 'array', // JSON → Array
        'status' => 'boolean',
    ];
    
   
}

