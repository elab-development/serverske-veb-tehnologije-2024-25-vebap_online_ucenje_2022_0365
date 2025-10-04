<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();

        foreach ($courses as $course) {
            $lessons = [
                [
                    'title' => 'Introduction to ' . $course->title,
                    'content' => 'This lesson covers the basics of ' . strtolower($course->title) . '.',
                    'video_url' => 'https://example.com/videos/introduction-' . strtolower(str_replace(' ', '-', $course->title)) . '.mp4',
                    'course_id' => $course->id,
                ],
                [
                    'title' => 'Deep Dive into ' . $course->title,
                    'content' => 'In this lesson, we dive deeper into the concepts of ' . strtolower($course->title) . '.',
                    'video_url' => 'https://example.com/videos/deep-dive-' . strtolower(str_replace(' ', '-', $course->title)) . '.mp4',
                    'course_id' => $course->id,
                ],
                [
                    'title' => 'Practical Application of ' . $course->title,
                    'content' => 'Learn how to apply the knowledge of ' . strtolower($course->title) . ' to real-world scenarios.',
                    'video_url' => 'https://example.com/videos/practical-' . strtolower(str_replace(' ', '-', $course->title)) . '.mp4',
                    'course_id' => $course->id,
                ],
            ];

            foreach ($lessons as $lesson) {
                Lesson::create($lesson);
            }
        }
    }
}
