<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $guarded = [];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'subdistrict_id');
    }

    public function getCategoryAttribute()
    {
        return $this->product?->category;
    }
}
