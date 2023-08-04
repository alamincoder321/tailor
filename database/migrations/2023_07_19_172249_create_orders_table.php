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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("order_code");
            $table->string("orderDate");
            $table->string("deliveryDate");
            $table->foreignId("customer_id")->constrained("customers", "id")->cascadeOnDelete();
            $table->string("refer")->nullable();
            $table->decimal("subtotal");
            $table->decimal("discount");
            $table->decimal("total");
            $table->decimal("advance");
            $table->decimal("due");
            $table->string("tailor_slip_one")->nullable();
            $table->string("tailor_slip_two")->nullable();
            $table->string("addby");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
