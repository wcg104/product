<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->integer('price');
            $table->string('slug')->nullable();
            $table->string('short_description');
            $table->string('keypoints')->nullable();
            $table->float('discounted_price');
            $table->integer('in_stock');
            $table->boolean('is_active');
            $table->string('brand');
            $table->string('cover_image');
            $table->integer('main_category');
            $table->integer('parent_product');
            $table->string('variant');
            $table->integer('value');
            $table->string('long_description');
            $table->string('images');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
