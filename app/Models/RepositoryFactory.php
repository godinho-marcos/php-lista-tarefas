<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

abstract class RepositoryFactory
{
    final public function make(): Repository
    {
        return $this->createRepository(Database::connection());
    }

    abstract protected function createRepository(PDO $pdo): Repository;
}
