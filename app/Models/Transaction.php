<?php

namespace App\Models;

use Dom\Attr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    protected function casts()
    {
        return [
            'paid_at' => 'datetime'
        ];
    }

    public function scopeCompleted(Builder $builder, bool $status = true)
    {
        if ($status) {
            return $builder->whereStatus('completed');
        } else {
            return $builder->where('status', '!=', 'completed');
        }
    }
}
