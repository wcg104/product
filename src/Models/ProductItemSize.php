<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductItemSize extends Model
{
    use HasFactory, HasUuids , SoftDeletes;
    protected $table = 'product_item_sizes';
    protected $fillable = [
        
        'product_item_id',
        'itemname',
        'itemquantity',
    ];
   
}
