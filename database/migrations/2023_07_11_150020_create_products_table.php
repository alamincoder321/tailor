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
            $table->id();
            $table->string("product_code");
            $table->string("name");
            $table->text("description")->nullable();
            $table->foreignId("category_id")->constrained('categories', 'id')->cascadeOnDelete();
            $table->integer("quantity");
            $table->decimal("tailor_price", 18, 2);
            $table->decimal("retail_price", 18, 2);
            $table->decimal("discount", 18, 2);
            $table->foreignId("tailor_id")->constrained('tailors', 'id')->cascadeOnDelete();
            $table->char("status")->default('a');
            $table->string("image")->nullable();
            $table->integer("user_id");
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
