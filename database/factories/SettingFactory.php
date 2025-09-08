<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      // 'kta_path_before' => $this->faker->word(),
      // 'kta_file_before' => $this->faker->word(),
      'kta_path_now' => $this->faker->word(),
      'kta_file_now' => $this->faker->word(),
    ];
  }
}
