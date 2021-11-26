<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition():array
    {
        return [
            'user_id' => 1,
            'sender_first_name' => $this->faker->firstName(),
            'sender_email' => $this->faker->email(),
            'receiver_email' => $this->faker->email(),
            'sender_last_name' => $this->faker->lastName(),
            'sender_middle_name' => $this->faker->lastName(),
            'sender_address' => $this->faker->address(),
            'sender_mobile' => $this->faker->phoneNumber(),
            'receiver_first_name' => $this->faker->firstName(),
            'receiver_last_name' => $this->faker->lastName(),
            'receiver_middle_name' => $this->faker->lastName(),
            'receiver_address' => $this->faker->address(),
            'receiver_mobile' => $this->faker->phoneNumber(),
            'purpose' => $this->faker->paragraph(),
            'relationship' => $this->faker->sentence(),
            'amount' => $this->faker->numberBetween(500, 2000),
            'created_at' => $this->faker->dateTimeThisDecade(),
        ];
    }
}
