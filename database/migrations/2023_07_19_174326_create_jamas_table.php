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
            $table->string('long');
            $table->string('body');
            $table->string('tira');
            $table->string('hata');
            $table->string('mohori');
            $table->string('gola');
            $table->string('gher');
            $table->string('plate');
            $table->string('mora');
            $table->string('ghari');
            $table->string('peter_map');
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
