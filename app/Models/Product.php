<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'quantity',
        'image',
        'description',
        'category_id'
    ];

        public function category()
    {
        return $this->belongsTo(Category::class);
    }

      public function product()
    {
        return $this->hasMany(Product::class);
    }
}
