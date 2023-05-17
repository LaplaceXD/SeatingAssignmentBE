<?php

use App\Models\User;
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
        Schema::create('Issues', function (Blueprint $table) {
            $table->id('IssueID');

            $table->foreignId('IssuerID')
                ->constrained('Users', 'UserID', 'issues_issuer_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('ValidatorID')
                ->nullable()
                ->constrained('Users', 'UserID', 'issues_validator_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('AssigneeID')
                ->nullable()
                ->constrained('Users', 'UserID', 'issues_assignee_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('LabID')
                ->constrained('Laboratories', 'LabID', 'issues_lab_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('TypeID')
                ->constrained('IssueTypes', 'TypeID', 'issues_type_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('SeatNo', 4);
            $table->text('Description');
            $table->text('ReplicationSteps');
            $table->enum('Status', ['Raised', 'Validated', 'In Progress', 'Dropped', 'Fixed']);

            $table->timestamp('IssuedAt')->useCurrent();
            $table->timestamp('ValidatedAt')->nullable();
            $table->timestamp('CompletedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Issues');
    }
};
