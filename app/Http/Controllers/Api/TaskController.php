<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;

class TaskController extends Controller
{
  public function __construct(protected TaskService $service) {}

  public function index()
  {
    return TaskResource::collection($this->service->getAllTasks());
  }

  public function show(string $id)
  {
    $task = $this->service->getTaskById($id);
    return new TaskResource($task);
  }

  public function store(TaskStoreRequest $request)
  {
    $task = $this->service->createTask($request->validated());
    return new TaskResource($task);
  }

  public function update(TaskUpdateRequest $request, string $id)
  {
    $task = $this->service->updateTask($id, $request->validated());
    return new TaskResource($task);
  }

  public function destroy(string $id)
  {
    $this->service->deleteTask($id);
    return response()->noContent();
  }
}
