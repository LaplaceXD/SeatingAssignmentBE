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
        Schema::create('Images', function (Blueprint $table) {
            $table->id('ImageID');
            $table->foreignId('IssueID')
                ->constrained('Issues', 'IssueID', 'images_issue_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->binary('ImageBinary');
            $table->string('Filename', 256);
            $table->string('Extension', 16);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Images');
    }
};
