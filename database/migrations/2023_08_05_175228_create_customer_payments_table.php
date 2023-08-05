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
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->id();
            $table->string("date");
            $table->string("transaction_type");
            $table->string("payment_type");
            $table->integer("bank_id")->nullable();
            $table->integer("customer_id");
            $table->decimal("amount");
            $table->decimal("due");
            $table->text("note")->nullable();
            $table->string("addby");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_payments');
    }
};
