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
        Schema::create('payjamas', function (Blueprint $table) {
            $table->id();
            $table->string('long')->nullable();
            $table->string('komor')->nullable();
            $table->string('mohori')->nullable();
            $table->string('high')->nullable();
            $table->string('ran')->nullable();
            $table->string('pocket_chain')->default('false');
            $table->string('good_runner')->default('false');
            $table->string('back_pocket')->default('false');
            $table->string('pocket_chain_one')->default('false');
            $table->string('fitha')->default('false');
            $table->string('rabar')->default('false');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payjamas');
    }
};
