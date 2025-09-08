<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TComplaint>
 */
class TComplaintFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'm_member_id' => Member::inRandomOrder()->first()->id,
      'm_user_id' => User::where('role', 'admin')->inRandomOrder()->first()->id,
      'code' => $this->faker->unique()->bothify('CMP-#####'),
      'title' => $this->faker->sentence(),
      'complaint' => $this->faker->text(),
      'response' => $this->faker->text(),
      'resolved_at' => $this->faker->date(),
      'status' => $this->faker->randomElement(['pending', 'in_progress', 'resolved']),
    ];
  }
}
