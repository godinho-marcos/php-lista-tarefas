<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Session;
use App\Models\TaskRepositoryFactory;

final class TaskController
{
    public function __construct(private readonly TaskRepositoryFactory $tasks)
    {
    }

    public function index(): void
    {
        $user = Session::requireUser();
        if ($user === null) {
            return;
        }

        $tasks = $this->tasks->make()->findByUserId($user['id']);
        require __DIR__ . '/../Views/tasks/index.php';
    }
}
