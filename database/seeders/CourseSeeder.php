<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
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
                'title' => 'Python for Data Science',
                'description' => 'Learn the fundamentals of Python and how to use it for data analysis and visualization.',
                'price' => 49.99,
                'duration' => 20,
                'level' => 'Beginner',
                'user_id' => User::where('role', 'instructor')->inRandomOrder()->first()->id,
            ],
            [
                'title' => 'Web Development Bootcamp',
                'description' => 'A complete guide to front-end and back-end web development with hands-on projects.',
                'price' => 99.99,
                'duration' => 40,
                'level' => 'Intermediate',
                'user_id' => User::where('role', 'instructor')->inRandomOrder()->first()->id,
            ],
            [
                'title' => 'Advanced React Techniques',
                'description' => 'Dive deep into React with hooks, context, and advanced patterns for scalable applications.',
                'price' => 79.99,
                'duration' => 30,
                'level' => 'Advanced',
                'user_id' => User::where('role', 'instructor')->inRandomOrder()->first()->id,
            ],
            [
                'title' => 'Machine Learning Basics',
                'description' => 'Introduction to the world of AI and machine learning algorithms using Python.',
                'price' => 59.99,
                'duration' => 25,
                'level' => 'Beginner',
                'user_id' => User::where('role', 'instructor')->inRandomOrder()->first()->id,
            ],
            [
                'title' => 'DevOps Essentials',
                'description' => 'Master the essentials of CI/CD pipelines, Docker, and Kubernetes for modern software deployment.',
                'price' => 89.99,
                'duration' => 35,
                'level' => 'Intermediate',
                'user_id' => User::where('role', 'instructor')->inRandomOrder()->first()->id,
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
