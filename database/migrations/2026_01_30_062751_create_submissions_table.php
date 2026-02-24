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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id')->nullable()->unsigned();
            $table->enum('submission_type', ['mitra', 'mandiri']);
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->enum('status', [
                'submitted',
                'approved',
                'rejected',
                'cancelled'
            ])->default('submitted');
            $table->string('company_name')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_phone_number')->nullable();
            $table->text('company_address')->nullable();
            $table->string('criteria')->nullable();
            $table->date('start_date');
            $table->date('finish_date');
            $table->timestamp('approved_at')->nullable();
            $table->foreign('partner_id')->references('id')->on('partners')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
