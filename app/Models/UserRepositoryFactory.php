<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class UserRepositoryFactory extends RepositoryFactory
{
    protected function createRepository(PDO $pdo): UserRepository
    {
        return new UserRepository($pdo);
    }
}
