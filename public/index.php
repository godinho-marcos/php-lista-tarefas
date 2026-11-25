<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\TaskController;
use App\Core\Router;
use App\Core\Session;
use App\Models\TaskRepositoryFactory;
use App\Models\UserRepositoryFactory;

require dirname(__DIR__) . '/app/bootstrap.php';

Session::start();
header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store');

$auth = new AuthController(new UserRepositoryFactory());
$tasks = new TaskRepositoryFactory();
$taskController = new TaskController($tasks);
$router = new Router();
$router->get('/', [new HomeController($tasks), 'show']);
$router->get('/tarefas', [$taskController, 'index']);
$router->get('/tarefas/nova', [$taskController, 'newForm']);
$router->post('/tarefas/nova', [$taskController, 'create']);
$router->get('/tarefas/editar', [$taskController, 'editForm']);
$router->post('/tarefas/editar', [$taskController, 'update']);
$router->post('/tarefas/excluir', [$taskController, 'delete']);
$router->get('/cadastro', [$auth, 'showRegistration']);
$router->post('/cadastro', [$auth, 'register']);
$router->get('/login', [$auth, 'showLogin']);
$router->post('/login', [$auth, 'login']);
$router->post('/logout', [$auth, 'logout']);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
