<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\Temp;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void

    {

        
    
        //dropping the older table product
        Schema::dropIfExists('products');

        //creating new table
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('category_id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('short_description');
            $table->string('product_type')->nullable();
            $table->boolean('is_active');
            $table->string('brand');
            $table->timestamps();
            $table->softDeletes();
        });
  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
            Schema::dropIfExists('products');

        });
    }
};
