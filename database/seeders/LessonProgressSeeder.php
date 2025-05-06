<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['employee_id' => 1,'lesson_id' => 1,'is_completed' => true,'completed_at' => now(),'watch_duration' => 20],
            ['employee_id' => 1,'lesson_id' => 2,'is_completed' => true,'completed_at' => now(),'watch_duration' => 10],
            ['employee_id' => 1,'lesson_id' => 3,'is_completed' => true,'completed_at' => now(),'watch_duration' => 15],
            ['employee_id' => 1,'lesson_id' => 4,'is_completed' => true,'completed_at' => now(),'watch_duration' => 15],
            ['employee_id' => 1,'lesson_id' => 5,'is_completed' => true,'completed_at' => now(),'watch_duration' => 30],
        ];

        foreach ($data as $lessonProgress) {
            \App\Models\LessonProgress::create($lessonProgress);
        }
    }
}
