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
            MajorSeeder::class,
            PartnerSeeder::class,
            SubmissionSeeder::class,
            ReviewSeeder::class,
        ]);

        $admin = User::create([
            'username'  => 'Guru',
            'email'     => 'admin@gmail.com',
            'fullname'  => 'Admin Ganteng',
            'password'  => bcrypt('admin123'),
            'is_active' => true,
        ]);

        $admin->assignRole('teacher');


        // 5 akun siswa
        $students = [
            [
                'username' => 'Yuk',
                'email' => 'yuk@gmail.com',
                'fullname' => 'Yuktafi',
                'nisn' => '89098098029',
            ],
            [
                'username' => 'Nabil',
                'email' => 'nurmieai@gmail.com',
                'fullname' => 'Nabil Nur Rahmat',
                'nisn' => '89098098030',
            ],
            [
                'username' => 'Aldo',
                'email' => 'aldo@gmail.com',
                'fullname' => 'Aldo Yonanda Firmansyah',
                'nisn' => '89098098031',
            ],
            [
                'username' => 'Afif',
                'email' => 'afif@gmail.com',
                'fullname' => 'Afif Rayhan Habibi',
                'nisn' => '89098098032',
            ],
            [
                'username' => 'Gia',
                'email' => 'gia@gmail.com',
                'fullname' => 'Muhammad Gia Mardhotillah',
                'nisn' => '89098098033',
            ],
        ];

        foreach ($students as $data) {
            $student = User::create([
                ...$data,
                'password'  => bcrypt('abcde123'),
                'is_active' => true,
                'major_id'  => 1,
            ]);

            $student->assignRole('student');
        }


        $role = Role::firstOrCreate(['name' => 'student']);

        User::factory()
            ->count(100)
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
