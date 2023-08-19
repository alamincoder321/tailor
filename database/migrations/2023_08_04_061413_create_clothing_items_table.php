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
        Schema::create('clothing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clothing_id')->constrained('clothing', 'id');
            $table->integer('tailor_id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->decimal('tailor_price');
            $table->decimal('total');
            $table->char('status', 5)->default('p');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clothing_items');
    }
};
