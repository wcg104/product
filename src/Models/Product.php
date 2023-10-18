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
        'category_id',
        'short_description',
        'brand',
        'is_active',
        'product_type',
        'slug',
      
    ];

    public static function generateSlug($title)
    {
  
      $slug = \Str::slug($title);// Query to check if slug already exists
      $qry =Product::whereSlug($slug);
  
  
      return $slug; // Return the generated slug
  
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            // Delete all related items
            $product->items()->delete();
        });
    }

    public function items()
    {
        return $this->hasMany(ProductItem::class);
    }
  
}
