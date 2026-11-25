<?php

declare(strict_types=1);

namespace App\Models;

final class TaskRepository extends Repository
{
    public function findByUserId(int $userId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, titulo, disciplina, prazo, status
             FROM tarefas WHERE usuario_id = :usuario_id
             ORDER BY prazo IS NULL, prazo, id',
        );
        $statement->execute(['usuario_id' => $userId]);

        return $statement->fetchAll();
    }
}
