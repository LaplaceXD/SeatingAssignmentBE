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
        Schema::create('Laboratories', function (Blueprint $table) {
            $table->id('LabID');
            $table->string('LabName', 128);
            $table->string('RoomNo', 4);
            $table->string('AisleNo', 4);
            $table->string('FloorNo', 4);
            $table->string('BuildingCode', 8);
            $table->integer('Capacity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Laboratories');
    }
};
