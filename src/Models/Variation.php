<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Variation extends Model
{
    use HasFactory,softDeletes,HasUuids;
    protected $fillable = [
        'title',
        'type',
        'prefix',
        'postfix',
        'countable',
        'value',
        'category_id',
    ];
}
