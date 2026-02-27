<?php

namespace Tests\Unit\Services;

use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Services\TaskService;
use PHPUnit\Framework\Attributes\Test;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;

class TaskServiceTest extends MockeryTestCase
{
  private TaskService $service;
  /** @var TaskRepositoryInterface & MockInterface */
  private $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = \Mockery::mock(TaskRepositoryInterface::class);
    $this->service = new TaskService($this->repository);
  }

  #[Test]
  public function it_retrieves_all_tasks_from_repository()
  {
    $tasks = [
      ['id' => '1', 'title' => 'Task 1', 'completed' => false],
      ['id' => '2', 'title' => 'Task 2', 'completed' => true],
    ];

    $this->repository
      ->shouldReceive('all')
      ->once()
      ->andReturn($tasks);

    $result = $this->service->getAllTasks();

    $this->assertEquals($tasks, $result);
  }

  #[Test]
  public function it_retrieves_a_single_task_by_id()
  {
    $taskId = '550e8400-e29b-41d4-a716-446655440000';
    $task = ['id' => $taskId, 'title' => 'Test Task', 'completed' => false];

    $this->repository
      ->shouldReceive('find')
      ->with($taskId)
      ->once()
      ->andReturn($task);

    $result = $this->service->getTaskById($taskId);

    $this->assertEquals($task, $result);
  }

  #[Test]
  public function it_creates_a_new_task_with_provided_data()
  {
    $data = [
      'title' => 'New Task',
      'description' => 'Task description',
      'completed' => false,
    ];

    $createdTask = array_merge(['id' => '550e8400-e29b-41d4-a716-446655440000'], $data);

    $this->repository
      ->shouldReceive('create')
      ->with($data)
      ->once()
      ->andReturn($createdTask);

    $result = $this->service->createTask($data);

    $this->assertEquals($createdTask, $result);
  }

  #[Test]
  public function it_updates_a_task_with_new_data()
  {
    $taskId = '550e8400-e29b-41d4-a716-446655440000';
    $data = ['title' => 'Updated Task', 'completed' => true];
    $updatedTask = array_merge(['id' => $taskId], $data);

    $this->repository
      ->shouldReceive('update')
      ->with($taskId, $data)
      ->once()
      ->andReturn($updatedTask);

    $result = $this->service->updateTask($taskId, $data);

    $this->assertEquals($updatedTask, $result);
  }

  #[Test]
  public function it_deletes_a_task_by_id()
  {
    $taskId = '550e8400-e29b-41d4-a716-446655440000';

    $this->repository
      ->shouldReceive('delete')
      ->with($taskId)
      ->once()
      ->andReturn(true);

    $result = $this->service->deleteTask($taskId);

    $this->assertTrue($result);
  }
}
