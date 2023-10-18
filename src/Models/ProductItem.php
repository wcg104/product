<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductItem extends Model
{
    use HasFactory,HasUuids,SoftDeletes;
   
    protected $fillable = [
        
        'product_id',
        'color',
        'price',
        'final_price',
        'is_available',
        'quantity',
        'tags',
        'ordering'
    ];


        /**
         * Get all of the comments for the ProductItem
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
       
         protected static function boot()
         {
             parent::boot();
     
             static::deleting(function ($item) {
                 // Delete all related sizes
                 $item->sizes()->delete();
     
                 // Delete all related images
                 $item->images()->each(function ($image) {
                    // Unlink the image from local storage
                    
                   unlink(public_path('images/product_media/'.$image->name));
                    $image->delete();
                });
             });
            
         }
     
         public function sizes()
         {
             return $this->hasMany(ProductItemSize::class);
         }
     
         public function images()
         {
             return $this->hasMany(ProductMedia::class);
         }
    }