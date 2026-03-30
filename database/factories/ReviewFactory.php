<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Submission;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            // Jika tidak dipassing ID, factory akan otomatis bikin User & Submission baru
            'submission_id' => Submission::factory(),
            'student_id' => function (array $attributes) {
                // Samakan student_id dengan user_id yang ada di submission agar logis
                return Submission::find($attributes['submission_id'])->user_id;
            },
            'judul' => $this->faker->sentence(4),
            'isi' => $this->faker->paragraph(3),
            'rating' => $this->faker->numberBetween(1, 5),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
