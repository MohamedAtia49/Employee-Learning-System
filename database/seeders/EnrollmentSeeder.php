<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enrollments = [
            [
                'employee_id' => 1,
                'course_id' => 1,
                'enrollment_date' => now(),
            ],
            [
                'employee_id' => 2,
                'course_id' => 2,
                'enrollment_date' => now(),
            ],
            [
                'employee_id' => 3,
                'course_id' => 2,
                'enrollment_date' => now(),
            ],
        ];

        foreach ($enrollments as $enrollment) {
            \DB::table('enrollments')->insert([
                'employee_id' => $enrollment['employee_id'],
                'course_id' => $enrollment['course_id'],
                'enrollment_date' => $enrollment['enrollment_date'],
            ]);
        }
    }
}
