<?php

declare(strict_types=1);

namespace App\Controllers;

final class HomeController
{
    public function show(): void
    {
        $title = 'Agenda de Estudos';
        require __DIR__ . '/../Views/home.php';
    }
}
