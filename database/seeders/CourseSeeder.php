<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Syllabus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Introduction to Laravel',
                'user_id' => 1,
                'duration' => 60,
                'description' => 'Learn Laravel from scratch with this comprehensive course.',
                'video_url' => 'https://www.youtube.com/watch?v=PEo0KmuuzSc',
                'syllabus' => [
                    [
                        'title' => 'Getting Started',
                        'description' => 'Introduction to Laravel framework',
                        'duration' => 30,
                        'order' => 1,
                        'lessons' => [
                            [
                                'title' => 'Installation',
                                'video_url' => 'https://www.youtube.com/watch?v=PEo0KmuuzSc',
                                'duration' => 20,
                                'is_preview' => true,
                                'order' => 1,
                            ],
                            [
                                'title' => 'Configuration',
                                'video_url' => 'https://www.youtube.com/watch?v=PEo0KmuuzSc',
                                'duration' => 10,
                                'is_preview' => true,
                                'order' => 2,
                            ],
                        ],
                    ],
                    [
                        'title' => 'Basic Concepts',
                        'description' => 'Learn the fundamental concepts of Laravel',
                        'duration' => 30,
                        'order' => 2,
                        'lessons' => [
                            [
                                'title' => 'Routing',
                                'video_url' => 'https://www.youtube.com/watch?v=PEo0KmuuzSc',
                                'duration' => 15,
                                'is_preview' => true,
                                'order' => 1,
                            ],
                            [
                                'title' => 'Controllers',
                                'video_url' => 'https://www.youtube.com/watch?v=PEo0KmuuzSc',
                                'duration' => 15,
                                'is_preview' => true,
                                'order' => 2,
                            ],
                        ],
                    ],
                ],
                'thumbnail_image' => 'courses/default-course.jpg',
            ],
            [
                'title' => 'Advanced PHP Techniques',
                'user_id' => 2,
                'duration' => 30,
                'description' => 'Master advanced PHP concepts and patterns.',
                'video_url' => 'https://fb.watch/zgTCkP3RYg/',
                'syllabus' => [
                    [
                        'title' => 'Design Patterns',
                        'description' => 'Learn common PHP design patterns',
                        'duration' => 30,
                        'order' => 1,
                        'lessons' => [
                            [
                                'title' => 'Singleton Pattern',
                                'video_url' => 'https://www.youtube.com/watch?v=PEo0KmuuzSc',
                                'duration' => 30,
                                'is_preview' => true,
                                'order' => 1,
                            ],
                        ],
                    ],
                ],
                'thumbnail_image' => 'courses/default-course.jpg',
            ],
        ];

        foreach ($courses as $courseData) {
            $syllabus = $courseData['syllabus'];
            unset($courseData['syllabus']);
            unset($courseData['image_path']);

            $course = Course::create($courseData);

            foreach ($syllabus as $syllabusData) {
                $lessons = $syllabusData['lessons'] ?? [];
                unset($syllabusData['lessons']);

                $syllabus = Syllabus::create(array_merge($syllabusData, [
                    'course_id' => $course->id,
                ]));

                foreach ($lessons as $lessonData) {
                    Lesson::create(array_merge($lessonData, [
                        'syllabus_id' => $syllabus->id,
                    ]));
                }
            }
        }
    }
}
