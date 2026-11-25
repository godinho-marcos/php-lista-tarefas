<?php

declare(strict_types=1);

namespace App\Models;

use PDOException;

final class UserRepository extends Repository
{
    public function create(string $name, string $email, string $passwordHash): bool
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO usuarios (nome, email, senha_hash) VALUES (:nome, :email, :senha_hash)',
        );

        try {
            $statement->execute(['nome' => $name, 'email' => $email, 'senha_hash' => $passwordHash]);
            return true;
        } catch (PDOException $error) {
            if ((int) ($error->errorInfo[1] ?? 0) === 1062) {
                return false;
            }

            throw $error;
        }
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, nome, email, senha_hash FROM usuarios WHERE email = :email',
        );
        $statement->execute(['email' => $email]);

        $user = $statement->fetch();
        return $user === false ? null : $user;
    }
}
