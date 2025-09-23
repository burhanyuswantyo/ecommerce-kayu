<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Dom\Attr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use Sluggable, HasFactory;

    protected $guarded = [];

    protected $casts = [
        'images' => 'array',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // public function price(): Attribute
    // {
    //     return Attribute::make(fn($value) => number_format($value, 0, ',', '.'));
    // }

    public function weight(): Attribute
    {
        return Attribute::make(fn($value) => round($value));
    }

    public function length(): Attribute
    {
        return Attribute::make(fn($value) => round($value));
    }

    public function height(): Attribute
    {
        return Attribute::make(fn($value) => round($value));
    }

    public function width(): Attribute
    {
        return Attribute::make(fn($value) => round($value));
    }

    public function dimension(): Attribute
    {
        return Attribute::make(fn() => "$this->length  x  $this->width  x  $this->height $this->dimension_unit");
    }

    protected function weightKg(): Attribute
    {
        return Attribute::make(
            function () {
                if ($this->weight_unit === 'kg') {
                    return number_format($this->weight, 2);
                } elseif ($this->weight_unit === 'gram') {
                    return number_format($this->weight / 1000, 2);
                }
            }
        );
    }

    // protected function images(): Attribute
    // {
    //     return Attribute::make(function ($value) {
    //         foreach (json_decode($value, true) as $image) {
    //             $images[]['path'] = $image;
    //         }

    //         return $images;
    //     });
    // }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    // public function soldCount(): Attribute
    // {
    //     return Attribute::make(function () {
    //         return $this
    //             ->transactionItems()
    //             ->whereHas('transaction', fn($query) => $query->whereNotIn('status', ['waiting_for_payment']))
    //             ->count();
    //     });
    // }

    public function scopeActive(Builder $builder)
    {
        return $builder->whereIsActive(true);
    }
}
