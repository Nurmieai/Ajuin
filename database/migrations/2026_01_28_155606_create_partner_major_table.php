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
        Schema::create('partner_major', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id')->unsigned();
            $table->unsignedBigInteger('major_id')->unsigned();
            $table->unique(['partner_id', 'major_id']);
            $table->timestamps();
            $table->foreign('partner_id')->references('id')->on('partners');
            $table->foreign('major_id')->references('id')->on('majors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_major');
    }
};
