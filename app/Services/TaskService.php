<?php

namespace App\Services;

use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskService
{
  public function __construct(protected TaskRepositoryInterface $repository) {}

  public function getAllTasks()
  {
    return $this->repository->all();
  }

  public function getTaskById(string $id)
  {
    return $this->repository->find($id);
  }

  public function createTask(array $data)
  {
    return $this->repository->create($data);
  }

  public function updateTask(string $id, array $data)
  {
    return $this->repository->update($id, $data);
  }

  public function deleteTask(string $id)
  {
    return $this->repository->delete($id);
  }
}
