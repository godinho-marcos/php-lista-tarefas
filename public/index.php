<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Core\Router;

require dirname(__DIR__) . '/app/bootstrap.php';

header('Content-Type: text/html; charset=UTF-8');

$router = new Router();
$router->get('/', [new HomeController(), 'show']);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
