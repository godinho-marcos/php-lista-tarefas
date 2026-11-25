<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Session;
use App\Models\TaskRepositoryFactory;

final class HomeController
{
    public function __construct(private readonly TaskRepositoryFactory $tasks)
    {
    }

    public function show(): void
    {
        $user = Session::requireUser();
        if ($user === null) {
            return;
        }

        $title = 'Agenda de Estudos';
        $csrf = Session::csrfToken();
        $tasks = $this->tasks->make()->findByUserId($user['id']);
        require __DIR__ . '/../Views/home.php';
    }
}
