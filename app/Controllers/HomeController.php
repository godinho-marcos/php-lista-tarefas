<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Session;

final class HomeController
{
    public function show(): void
    {
        $user = Session::user();
        if ($user === null) {
            header('Location: /login', true, 303);
            return;
        }

        $title = 'Agenda de Estudos';
        $csrf = Session::csrfToken();
        require __DIR__ . '/../Views/home.php';
    }
}
