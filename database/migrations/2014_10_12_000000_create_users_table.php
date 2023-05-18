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
        Schema::create('Users', function (Blueprint $table) {
            $table->id('UserID');
            $table->string('FirstName', 256);
            $table->string('LastName', 256);
            $table->string('Email', 256)->unique();
            $table->string('Password');
            $table->timestamp('CreatedAt')->useCurrent();
            $table->enum('Role', ['student', 'professor', 'technician'])->default('student');
            $table->boolean('IsActive')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Users');
    }
};
