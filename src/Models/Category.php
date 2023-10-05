<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Category extends Model
{
    use HasFactory,SoftDeletes,HasUuids;
    protected $fillable = [
        'title',
        'description',
        'parent_category',
        'is_parent',
        'slug',
    ];
    
}
