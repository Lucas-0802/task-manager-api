<?php

namespace Tests\Integration\Services;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TaskServiceIntegrationTest extends TestCase
{
  use RefreshDatabase;

  private TaskService $service;

  protected function setUp(): void
  {
    parent::setUp();
    $this->service = app(TaskService::class);
  }

  #[Test]
  public function it_retrieves_all_tasks_from_database()
  {
    Task::factory()->count(3)->create();

    $tasks = $this->service->getAllTasks();

    $this->assertCount(3, $tasks);
  }

  #[Test]
  public function it_retrieves_a_single_task_by_id()
  {
    $task = Task::factory()->create([
      'title' => 'Find Me',
    ]);

    $foundTask = $this->service->getTaskById($task->id);

    $this->assertEquals($task->id, $foundTask->id);
    $this->assertEquals('Find Me', $foundTask->title);
  }

  #[Test]
  public function it_throws_exception_when_task_not_found()
  {
    $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

    $this->service->getTaskById('550e8400-e29b-41d4-a716-446655440000');
  }

  #[Test]
  public function it_creates_a_new_task()
  {
    $data = [
      'title' => 'New Task',
      'description' => 'Task description',
      'completed' => false,
    ];

    $task = $this->service->createTask($data);

    $this->assertNotNull($task->id);
    $this->assertEquals('New Task', $task->title);
    $this->assertEquals('Task description', $task->description);
    $this->assertFalse($task->completed);
    $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
  }

  #[Test]
  public function it_updates_an_existing_task()
  {
    $task = Task::factory()->create([
      'title' => 'Original Title',
      'completed' => false,
    ]);

    $updatedTask = $this->service->updateTask($task->id, [
      'title' => 'Updated Title',
      'completed' => true,
    ]);

    $this->assertEquals('Updated Title', $updatedTask->title);
    $this->assertTrue($updatedTask->completed);
    $this->assertDatabaseHas('tasks', [
      'id' => $task->id,
      'title' => 'Updated Title',
      'completed' => 1,
    ]);
  }

  #[Test]
  public function it_deletes_a_task()
  {
    $task = Task::factory()->create();
    $taskId = $task->id;

    $this->assertDatabaseHas('tasks', ['id' => $taskId]);

    $this->service->deleteTask($taskId);

    $this->assertDatabaseMissing('tasks', ['id' => $taskId]);
  }

  #[Test]
  public function it_returns_tasks_sorted_by_creation_date_descending()
  {
    $task1 = Task::factory()->create(['title' => 'Task 1']);
    sleep(1); // Ensure different timestamps
    $task2 = Task::factory()->create(['title' => 'Task 2']);

    $tasks = $this->service->getAllTasks();

    $this->assertEquals($task2->id, $tasks->first()->id);
    $this->assertEquals($task1->id, $tasks->last()->id);
  }
}
