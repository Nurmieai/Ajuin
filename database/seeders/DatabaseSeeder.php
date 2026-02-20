<?php

namespace Database\Seeders;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PartnerSeeder::class,
            MajorSeeder::class,
            SubmissionSeeder::class,
        ]);

        $admin = User::create([
            'username'  => 'admin',
            'email'     => 'admin@gmail.com',
            'fullname'  => 'Admin Ganteng',
            'password'  => bcrypt('admin123'),
            'is_active' => true,
        ]);

        $admin->assignRole('teacher');

        $student = User::create([
            'username'  => 'Yuk',
            'email'     => 'yuk@gmail.com',
            'fullname'  => 'Yuktafi',
            'password'  => bcrypt('abcde123'),
            'is_active' => true,
            'major_id'  => 1,
            ]);

        $student->assignRole('student');
        $role = Role::firstOrCreate(['name' => 'student']);

        User::factory()
            ->count(10)
            ->create([
                'is_active' => false,
                'major_id'  => 1,
            ])
            ->each(function ($user) use ($role) {
                $user->assignRole($role);
            });
        Submission::factory()->count(10)->create();
    }
}
