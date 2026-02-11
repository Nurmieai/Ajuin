<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,
            MajorSeeder::class,
        ]);

        $user = User::create([
            'username' => 'admin',
            'email'    => 'admin@gmail.com',
            'fullname' => 'admin ganteng',
            'password' => 'admin123',
            'is_active' => true,
            ]);

        $user->assignRole('teacher');

        $yuk = User::firstOrCreate(
        ['email'    => 'Yuk@gmail.com'],
            [
            'username' => 'Yuk',
            'fullname' => 'Yuktafi',
            'password' => 'abcde123',
            'is_active' => true,
            ]);
        $yuk->assignRole('student');
    }
}
