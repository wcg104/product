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
        Schema::create('product_item_sizes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_item_id');
            $table->string('itemname');
            $table->integer('itemquantity');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_item_sizes', function (Blueprint $table) {
            //
            Schema::dropIfExists('product_item_sizes');

        });
    }
};
