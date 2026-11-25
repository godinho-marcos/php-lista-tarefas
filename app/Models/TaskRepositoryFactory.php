<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class TaskRepositoryFactory extends RepositoryFactory
{
    protected function createRepository(PDO $pdo): TaskRepository
    {
        return new TaskRepository($pdo);
    }
}
