<?php

declare(strict_types=1);

namespace App\Models;

final class UserRepository extends Repository
{
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
