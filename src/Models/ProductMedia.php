<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductMedia extends Model
{

    use HasFactory,HasUuids,SoftDeletes;
    protected $table = 'product_medias';
    protected $fillable = [
        
        'product_item_id',
        'path',
        'name',
        'type',
        'ordering'
     ];

}
