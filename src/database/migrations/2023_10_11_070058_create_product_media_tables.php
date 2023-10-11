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
        Schema::create('product_medias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_item_id');
            $table->string('path');
            $table->string('name');
            $table->integer('ordering');
            $table->string('video')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_medias', function (Blueprint $table) {
            //
            Schema::dropIfExists('product_medias');

        });
    }
};
