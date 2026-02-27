<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TaskControllerTest extends TestCase
{
  use RefreshDatabase;

  #[Test]
  public function index_returns_all_tasks()
  {
    Task::factory()->count(3)->create();

    $response = $this->getJson('/api/tasks');

    $response->assertStatus(200)
      ->assertJsonStructure([
        'data' => [
          '*' => ['id', 'title', 'description', 'completed', 'created_at', 'updated_at'],
        ],
      ])
      ->assertJsonCount(3, 'data');
  }

  #[Test]
  public function index_returns_empty_array_when_no_tasks()
  {
    $response = $this->getJson('/api/tasks');

    $response->assertStatus(200)
      ->assertJson(['data' => []])
      ->assertJsonCount(0, 'data');
  }

  #[Test]
  public function show_returns_single_task()
  {
    $task = Task::factory()->create([
      'title' => 'Test Task',
      'description' => 'Test Description',
    ]);

    $response = $this->getJson("/api/tasks/{$task->id}");

    $response->assertStatus(200)
      ->assertJsonStructure([
        'data' => ['id', 'title', 'description', 'completed', 'created_at', 'updated_at'],
      ])
      ->assertJson([
        'data' => [
          'id' => $task->id,
          'title' => 'Test Task',
          'description' => 'Test Description',
        ],
      ]);
  }

  #[Test]
  public function show_returns_404_for_nonexistent_task()
  {
    $response = $this->getJson('/api/tasks/550e8400-e29b-41d4-a716-446655440000');

    $response->assertStatus(404);
  }

  #[Test]
  public function store_creates_a_new_task()
  {
    $payload = [
      'title' => 'New Task',
      'description' => 'New Description',
    ];

    $response = $this->postJson('/api/tasks', $payload);

    $response->assertStatus(201)
      ->assertJsonStructure([
        'data' => ['id', 'title', 'description', 'completed', 'created_at', 'updated_at'],
      ])
      ->assertJson([
        'data' => [
          'title' => 'New Task',
          'description' => 'New Description',
          'completed' => false,
        ],
      ]);

    $this->assertDatabaseHas('tasks', [
      'title' => 'New Task',
      'description' => 'New Description',
    ]);
  }

  #[Test]
  public function store_raises_validation_error_for_missing_title()
  {
    $payload = [
      'description' => 'Description without title',
    ];

    $response = $this->postJson('/api/tasks', $payload);

    $response->assertStatus(422)
      ->assertJsonValidationErrors(['title']);
  }

  #[Test]
  public function store_raises_validation_error_for_short_title()
  {
    $payload = [
      'title' => 'Mo', // Less than 3 characters
      'description' => 'Description',
    ];

    $response = $this->postJson('/api/tasks', $payload);

    $response->assertStatus(422)
      ->assertJsonValidationErrors(['title']);
  }

  #[Test]
  public function store_raises_validation_error_for_long_description()
  {
    $payload = [
      'title' => 'Valid Title',
      'description' => str_repeat('a', 1001), // More than 1000 characters
    ];

    $response = $this->postJson('/api/tasks', $payload);

    $response->assertStatus(422)
      ->assertJsonValidationErrors(['description']);
  }

  #[Test]
  public function update_updates_task_partially()
  {
    $task = Task::factory()->create([
      'title' => 'Original Title',
      'completed' => false,
    ]);

    $response = $this->putJson("/api/tasks/{$task->id}", [
      'title' => 'Updated Title',
    ]);

    $response->assertStatus(200)
      ->assertJson([
        'data' => [
          'id' => $task->id,
          'title' => 'Updated Title',
          'completed' => false,
        ],
      ]);

    $this->assertDatabaseHas('tasks', [
      'id' => $task->id,
      'title' => 'Updated Title',
    ]);
  }

  #[Test]
  public function update_updates_task_completed_status()
  {
    $task = Task::factory()->create([
      'completed' => false,
    ]);

    $response = $this->putJson("/api/tasks/{$task->id}", [
      'completed' => true,
    ]);

    $response->assertStatus(200)
      ->assertJson([
        'data' => [
          'completed' => true,
        ],
      ]);

    $this->assertDatabaseHas('tasks', [
      'id' => $task->id,
      'completed' => 1,
    ]);
  }

  #[Test]
  public function update_returns_404_for_nonexistent_task()
  {
    $response = $this->putJson('/api/tasks/550e8400-e29b-41d4-a716-446655440000', [
      'title' => 'Updated Title',
    ]);

    $response->assertStatus(404);
  }

  #[Test]
  public function destroy_deletes_a_task()
  {
    $task = Task::factory()->create();

    $response = $this->deleteJson("/api/tasks/{$task->id}");

    $response->assertStatus(204)
      ->assertNoContent();

    $this->assertDatabaseMissing('tasks', [
      'id' => $task->id,
    ]);
  }

  #[Test]
  public function destroy_returns_404_for_nonexistent_task()
  {
    $response = $this->deleteJson('/api/tasks/550e8400-e29b-41d4-a716-446655440000');

    $response->assertStatus(404);
  }

  #[Test]
  public function destroy_returns_204_on_successful_deletion()
  {
    $task = Task::factory()->create();

    $response = $this->deleteJson("/api/tasks/{$task->id}");

    $response->assertNoContent(); // HTTP 204
  }
}
