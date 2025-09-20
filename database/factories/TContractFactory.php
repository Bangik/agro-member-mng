<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TContract>
 */
class TContractFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'm_member_id' => \App\Models\Member::inRandomOrder()->first()->id,
      'part' => \App\Models\Part::inRandomOrder()->first()->name,
      'contract_number' => $this->faker->unique()->numerify('CONTRACT-#####'),
      'start_date' => $this->faker->date(),
      'end_date' => $this->faker->date(),
    ];
  }
}
