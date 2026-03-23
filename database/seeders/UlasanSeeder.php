<?php

namespace Database\Seeders;

use App\Models\Ulasan;
use Illuminate\Database\Seeder;

class UlasanSeeder extends Seeder
{
    public function run(): void
    {
        // Langsung buat 20 data menggunakan factory
        Ulasan::factory()->count(20)->create();

        $this->command->info('Berhasil membuat 20 data ulasan test.');
    }
}
