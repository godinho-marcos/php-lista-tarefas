<?php

declare(strict_types=1);

namespace App\Models;

final class TaskRepository extends Repository
{
    public const STATUSES = [
        'pendente' => 'Pendente',
        'em_andamento' => 'Em andamento',
        'concluida' => 'Concluída',
    ];

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

    public function find(int $id, int $userId): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, titulo, disciplina, prazo, status FROM tarefas WHERE id = :id AND usuario_id = :usuario_id',
        );
        $statement->execute(['id' => $id, 'usuario_id' => $userId]);

        $task = $statement->fetch();
        return $task === false ? null : $task;
    }

    public function create(int $userId, array $task): void
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO tarefas (usuario_id, titulo, disciplina, prazo, status)
             VALUES (:usuario_id, :titulo, :disciplina, :prazo, :status)',
        );
        $statement->execute(['usuario_id' => $userId] + $task);
    }

    public function update(int $id, int $userId, array $task): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE tarefas SET titulo = :titulo, disciplina = :disciplina, prazo = :prazo, status = :status
             WHERE id = :id AND usuario_id = :usuario_id',
        );
        $statement->execute(['id' => $id, 'usuario_id' => $userId] + $task);

        return $statement->rowCount() > 0 || $this->find($id, $userId) !== null;
    }

    public function delete(int $id, int $userId): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM tarefas WHERE id = :id AND usuario_id = :usuario_id');
        $statement->execute(['id' => $id, 'usuario_id' => $userId]);

        return $statement->rowCount() > 0;
    }
}
