<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'title' => $this->faker->sentence(3),
      'description' => $this->faker->paragraph(),
      'completed' => $this->faker->boolean(20),
    ];
  }

  /**
   * Indicate that the task is completed.
   */
  public function completed(): static
  {
    return $this->state(fn(array $attributes) => [
      'completed' => true,
    ]);
  }

  /**
   * Indicate that the task is not completed.
   */
  public function incomplete(): static
  {
    return $this->state(fn(array $attributes) => [
      'completed' => false,
    ]);
  }
}
