<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function __construct(protected Task $model) {}

    public function all() {
        return $this->model->all();
    }

    public function find(string $id) {
        return $this->model->findOrFail($id);
    }

    public function create(array $data) {
        return $this->model->create($data);
    }

    public function update(string $id, array $data) {
        $task = $this->find($id);
        $task->update($data);
        return $task;
    }
}