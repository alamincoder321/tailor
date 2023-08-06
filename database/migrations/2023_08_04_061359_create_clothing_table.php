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
        Schema::create('clothing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tailor_id')->constrained('tailors', 'id')->cascadeOnDelete();
            $table->string('date');
            $table->decimal('total');
            $table->decimal('paid');
            $table->decimal('due');
            $table->string('addby');
            $table->text('note')->nullable();
            $table->char('status')->default('p');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clothing');
    }
};
