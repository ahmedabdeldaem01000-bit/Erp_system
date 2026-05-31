<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseItemFactory> */
    use HasFactory;
    protected $fillable = ['name','purchase_id', 'product_id', 'quantity', 'price'];

    public function purchase()
    {
        return $this->belongsTo(Purchases::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
