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
        Schema::create('IssueTrails', function (Blueprint $table) {
            $table->id('TrailID');
            $table->foreignId('IssueID')
                ->constrained('Issues', 'IssueID', 'trails_issue_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('ExecutorID')
                ->nullable()
                ->constrained('Users', 'UserID', 'trails_executor_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // Tracks the previous and current value of a field
            $table->string('FieldName', 64)->nullable();
            $table->string('PreviousValue', 256)->nullable();
            $table->string('NewValue', 256)->nullable();

            // Some Trails are just information messages
            // Ex. Issue Created
            $table->text('Message')->nullable();

            $table->enum('ActionType', ['change', 'message']);
            $table->timestamp('ExecutedAt')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('IssueTrails');
    }
};
