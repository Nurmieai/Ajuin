<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partner>
 */
class PartnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        // Finish date diatur setelah start date
        $finishDate = $this->faker->dateTimeBetween($startDate, '+6 months');

        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'quota' => $this->faker->numberBetween(5, 50),
            'address' => $this->faker->address(),
            'criteria' => $this->faker->sentence(),
            'start_date' => $startDate,
            'finish_date' => $finishDate,
        ];
    }
}
