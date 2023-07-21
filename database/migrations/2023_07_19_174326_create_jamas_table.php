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
        Schema::create('jamas', function (Blueprint $table) {
            $table->id();
            $table->string('long')->nullable();
            $table->string('body')->nullable();
            $table->string('tira')->nullable();
            $table->string('hata')->nullable();
            $table->string('mohori')->nullable();
            $table->string('gola')->nullable();
            $table->string('gher')->nullable();
            $table->string('plate')->nullable();
            $table->string('mora')->nullable();
            $table->string('ghari')->nullable();
            $table->string('peter_map')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jamas');
    }
};
