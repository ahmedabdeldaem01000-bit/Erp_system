<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchases extends Model
{
    /** @use HasFactory<\Database\Factories\PurchasesFactory> */
    use HasFactory;
    protected $fillable = ['total', 'supplier_id'];
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
        public function purchase()
    {
        return $this->hasMany(Purchases::class);
    }
}

