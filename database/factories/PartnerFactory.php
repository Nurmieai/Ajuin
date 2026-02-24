<?php

namespace Database\Factories;

use App\Models\Major;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
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

    /**
     * Hubungkan dengan major setelah partner dibuat
     */
    public function configure()
    {
        return $this->afterCreating(function (Partner $partner) {
            $majorIds = Major::inRandomOrder()->limit(rand(1, 2))->pluck('id');
            $partner->majors()->attach($majorIds);
        });
    }
}
