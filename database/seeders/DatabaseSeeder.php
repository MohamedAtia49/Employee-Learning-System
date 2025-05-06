<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1- Create employees
        Employee::create([
            'name' => 'Mohamed Atia',
            'email' => 'atia@employee.com',
            'password' => bcrypt('2480123m'),
        ]);

        Employee::factory(10)->create();

        User::factory(10)->create();

        $this->call([
            CourseSeeder::class,
            EnrollmentSeeder::class,
            LessonProgressSeeder::class,
            CErtificationsSeeder::class,
            QuizSeeder::class,
        ]);
    }
}
