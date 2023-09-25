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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders', 'id')->cascadeOnDelete();
            $table->integer('tailor_id')->nullable();
            $table->integer('product_id');
            $table->integer('quantity');
            $table->decimal('retail_price');
            $table->decimal('tailor_price');
            $table->decimal('total');
            $table->integer("jama_id")->default(0);
            $table->integer("payjama_id")->default(0);
            $table->char('status', 20)->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
