<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul');
            $table->text('isi');
            $table->unsignedTinyInteger('rating')->between(1, 5);
            $table->timestamps();

            // Satu siswa hanya bisa punya satu ulasan per submission
            $table->unique(['submission_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasans');
    }
};