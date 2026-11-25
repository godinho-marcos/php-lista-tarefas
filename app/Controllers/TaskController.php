<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Session;
use App\Models\TaskRepository;
use App\Models\TaskRepositoryFactory;

final class TaskController
{
    public function __construct(private readonly TaskRepositoryFactory $tasks)
    {
    }

    public function index(): void
    {
        $user = Session::requireUser();
        if ($user === null) {
            return;
        }

        $tasks = $this->tasks->make()->findByUserId($user['id']);
        $csrf = Session::csrfToken();
        $notice = Session::takeNotice();
        require __DIR__ . '/../Views/tasks/index.php';
    }

    public function newForm(): void
    {
        if (Session::requireUser() !== null) {
            $this->renderForm(null, ['titulo' => '', 'disciplina' => '', 'prazo' => null, 'status' => 'pendente']);
        }
    }

    public function editForm(): void
    {
        $user = Session::requireUser();
        if ($user === null) {
            return;
        }

        $id = $this->taskId($_GET['id'] ?? null);
        $task = $id === null ? null : $this->tasks->make()->find($id, $user['id']);
        if ($task === null) {
            $this->notFound();
            return;
        }

        $this->renderForm($id, $task);
    }

    public function create(): void
    {
        $this->save(false);
    }

    public function update(): void
    {
        $this->save(true);
    }

    public function delete(): void
    {
        $user = Session::requireUser();
        if ($user === null || !$this->validateCsrf()) {
            return;
        }

        $id = $this->taskId($_POST['id'] ?? null);
        if ($id === null || !$this->tasks->make()->delete($id, $user['id'])) {
            $this->notFound();
            return;
        }

        $this->redirectWithNotice('Tarefa excluída.');
    }

    private function save(bool $editing): void
    {
        $user = Session::requireUser();
        if ($user === null || !$this->validateCsrf()) {
            return;
        }

        $repository = $this->tasks->make();
        $id = $editing ? $this->taskId($_GET['id'] ?? null) : null;
        if ($editing && ($id === null || $repository->find($id, $user['id']) === null)) {
            $this->notFound();
            return;
        }

        $task = [
            'titulo' => $this->field('titulo'),
            'disciplina' => $this->field('disciplina'),
            'prazo' => $this->field('prazo'),
            'status' => $this->field('status'),
        ];
        $error = $this->validateTask($task);
        if ($error !== null) {
            http_response_code(422);
            $this->renderForm($id, $task, $error);
            return;
        }

        $task['prazo'] = $task['prazo'] === '' ? null : $task['prazo'];
        if ($id === null) {
            $repository->create($user['id'], $task);
            $this->redirectWithNotice('Tarefa adicionada.');
            return;
        }

        if (!$repository->update($id, $user['id'], $task)) {
            $this->notFound();
            return;
        }

        $this->redirectWithNotice('Tarefa atualizada.');
    }

    private function validateTask(array $task): ?string
    {
        if (!preg_match('/\A[^\p{Cc}]{1,150}\z/u', $task['titulo'])) {
            return 'Informe um título de até 150 caracteres.';
        }

        if (!preg_match('/\A[^\p{Cc}]{1,120}\z/u', $task['disciplina'])) {
            return 'Informe uma disciplina de até 120 caracteres.';
        }

        $date = $task['prazo'];
        if ($date !== '' && (
            !preg_match('/\A([1-9][0-9]{3})-([0-9]{2})-([0-9]{2})\z/', $date, $parts)
            || !checkdate((int) $parts[2], (int) $parts[3], (int) $parts[1])
        )) {
            return 'Informe uma data de prazo válida.';
        }

        if (!isset(TaskRepository::STATUSES[$task['status']])) {
            return 'Selecione um status válido.';
        }

        return null;
    }

    private function renderForm(?int $id, array $task, ?string $error = null): void
    {
        $csrf = Session::csrfToken();
        $statuses = TaskRepository::STATUSES;
        require __DIR__ . '/../Views/tasks/form.php';
    }

    private function field(string $name): string
    {
        $value = $_POST[$name] ?? '';
        return is_string($value) ? trim($value) : '';
    }

    private function taskId(mixed $value): ?int
    {
        if (!is_string($value)) {
            return null;
        }

        $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 4294967295]]);
        return $id === false ? null : $id;
    }

    private function validateCsrf(): bool
    {
        if (Session::checkCsrf($_POST['csrf'] ?? null)) {
            return true;
        }

        http_response_code(403);
        echo 'Formulário inválido ou expirado. Volte à página e tente novamente.';
        return false;
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo 'Tarefa não encontrada. <a href="/tarefas">Voltar para tarefas</a>.';
    }

    private function redirectWithNotice(string $message): void
    {
        Session::setNotice($message);
        header('Location: /tarefas', true, 303);
    }
}
