<?php

namespace App\Services;

use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskService
{
    public function __construct(protected TaskRepositoryInterface $repository) {}

    public function getAllTasks() {
        return $this->repository->all();
    }

    public function createTask(array $data) {
        $data['completed'] = false; 
        return $this->repository->create($data);
    }

    public function updateTask(string $id, array $data) {
        return $this->repository->update($id, $data);
    }
}