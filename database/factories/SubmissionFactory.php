<?php

namespace Database\Factories;

use App\Models\Submission;
use App\Models\User;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubmissionFactory extends Factory
{
    protected $model = Submission::class;

    public function definition(): array
    {
        $type = 'mandiri';
        $start = $this->faker->dateTimeBetween('+1 week', '+1 month');
        $finish = (clone $start)->modify('+3 months');

        // $status = $this->faker->randomElement([
        //     'submitted',
        //     'approved',
        //     'rejected',
        //     'cancelled'
        // ]);

        return [

            'submission_type' => $type,

            'user_id' => User::factory(),

            'status' => 'submitted',

            'company_name' => $type === 'mandiri'
                ? $this->faker->company()
                : null,

            'company_email' => $type === 'mandiri'
                ? $this->faker->companyEmail()
                : null,

            'company_phone_number' => $type === 'mandiri'
                ? $this->faker->phoneNumber()
                : null,

            'company_address' => $type === 'mandiri'
                ? $this->faker->address()
                : null,

            // 'criteria' => $this->faker->randomElement([
            //     'Web Development',
            //     'Mobile Development',
            //     'Data Analysis',
            //     'Network Engineering'
            // ]),

            'start_date' => $start->format('Y-m-d'),
            'finish_date' => $finish->format('Y-m-d'),
        ];
    }
}
