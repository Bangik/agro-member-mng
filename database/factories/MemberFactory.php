<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'reg_number'          => $this->faker->unique()->numerify('REG#####'),
      'national_id_number'  => $this->faker->unique()->numerify('################'),
      'name'                => $this->faker->name(),
      'birth_place'         => $this->faker->city(),
      'birth_date'          => $this->faker->date(),
      'gender'              => $this->faker->randomElement(['male', 'female']),
      'address'             => $this->faker->address(),
      'rt'                  => $this->faker->numerify('##'),
      'rw'                  => $this->faker->numerify('##'),
      'village'             => $this->faker->streetName(),
      'district'            => $this->faker->streetName(),
      'city'                => $this->faker->city(),
      'state'               => $this->faker->state(),
      'post_code'           => $this->faker->postcode(),
      'phone'               => $this->faker->phoneNumber(),
      'email'               => $this->faker->unique()->safeEmail(),
      'religion'            => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
      'blood_type'          => $this->faker->randomElement(['A', 'B', 'AB', 'O']),
      'is_married'          => $this->faker->boolean(),
      'hobbies'             => $this->faker->word(),
    ];
  }
}
