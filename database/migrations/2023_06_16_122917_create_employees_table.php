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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('mobile', 100)->unique();
            $table->string('gender', 20);
            $table->date('join_date');
            $table->date('birth_date');
            $table->foreignId('designation_id')->constrained('designations', 'id')->onDelete('cascade');
            $table->string('father_name', 100)->nullable();
            $table->string('mother_name', 100)->nullable();
            $table->text('present_address', 500)->nullable();
            $table->text('permanent_address', 500)->nullable();
            $table->decimal('salary_range', 16, 2);
            $table->string('image')->nullable();
            $table->foreignId('addedBy')->constrained('users', 'id')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
