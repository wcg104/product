<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasFactory,softDeletes,HasUuids;
    protected $fillable = [
        'name',
        'price',
        'short_description',
        'discounted_price',
        'in_stock',
        'is_active',
        'brand',
        'cover_image',
        'main_category',
        'parent_product',
        'images',
        'value',
        'variant',
        'long_description',
        'slug',
        
    ];

    public static function boot()
    {
        parent::boot();
        self::created(function($product){
            $product->slug = \Str::slug($product->name).'/'.$product->id;
        });
        self::updated(function($product){
            $product->slug = \Str::slug($product->name).'/'.$product->id; 
        });

       
    }

}
