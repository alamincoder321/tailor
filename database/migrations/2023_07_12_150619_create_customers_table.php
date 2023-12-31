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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string("customer_code");
            $table->string("name")->nullable();
            $table->string("phone", 15)->nullable();
            $table->string("email")->nullable();
            $table->text("address")->nullable();
            $table->string('customer_type')->default('retail');
            $table->decimal('previous_due', 18, 2)->default(0);
            $table->string("image")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
