<?php

namespace Database\Factories;

use App\Models\Action;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Action>
 */
class ActionFactory extends Factory
{
    protected $model = Action::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['connectWallet', 'aprovedTransaction', 'transactionRequest']),
            'domain' => "testdomainwork.ru",
            'ip' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'address' => $this->faker->address,
            'balance' => $this->faker->randomFloat(2, 0, 10000),
            'country' => $this->faker->country,
            'created_at' => $this->faker->dateTimeThisYear,
        ];
    }
}