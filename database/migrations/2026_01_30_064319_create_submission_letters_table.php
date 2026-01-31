<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('submission_letters', function (Blueprint $table) {
        $table->id();
        $table->foreignId('submission_id')
            ->constrained('submissions')
            ->cascadeOnDelete();

        $table->enum('status', [
            'requested',
            'approved',
            'rejected'
        ])->default('requested');

        $table->string('letter_number')->nullable();
        $table->string('file_path')->nullable();

        $table->timestamp('approved_at')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_letters');
    }
};
