<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Session;
use App\Models\UserRepositoryFactory;

final class AuthController
{
    public function __construct(private readonly UserRepositoryFactory $users)
    {
    }

    public function showLogin(): void
    {
        if ($this->guestOnly()) {
            $this->renderForm(false, notice: Session::takeNotice());
        }
    }

    public function showRegistration(): void
    {
        if ($this->guestOnly()) {
            $this->renderForm(true);
        }
    }

    public function register(): void
    {
        if (!$this->guestOnly() || !$this->validateCsrf()) {
            return;
        }

        $name = trim($this->field('nome'));
        $email = $this->email();
        $password = $this->field('senha');

        if (!preg_match('/\A[^\p{Cc}]{1,120}\z/u', $name)) {
            $this->renderForm(true, 'Informe um nome de até 120 caracteres.', $name, $email);
            return;
        }

        if (strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->renderForm(true, 'Informe um e-mail válido.', $name, $email);
            return;
        }

        if (strlen($password) < 8 || !$this->validPassword($password)) {
            $this->renderForm(true, 'Use uma senha de 8 a 72 bytes.', $name, $email);
            return;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        if (!$this->users->make()->create($name, $email, $hash)) {
            $this->renderForm(true, 'Já existe uma conta com este e-mail.', $name, $email);
            return;
        }

        Session::setNotice('Cadastro concluído. Entre com seu e-mail e senha.');
        $this->redirect('/login');
    }

    public function login(): void
    {
        if (!$this->guestOnly() || !$this->validateCsrf()) {
            return;
        }

        $email = $this->email();
        $password = $this->field('senha');
        $user = null;

        if (strlen($email) <= 254 && filter_var($email, FILTER_VALIDATE_EMAIL) && $this->validPassword($password)) {
            $user = $this->users->make()->findByEmail($email);
        }

        if ($user === null || !password_verify($password, $user['senha_hash'])) {
            $this->renderForm(false, 'E-mail ou senha inválidos.', email: $email);
            return;
        }

        Session::signIn((int) $user['id'], $user['nome']);
        $this->redirect('/');
    }

    public function logout(): void
    {
        if ($this->validateCsrf()) {
            Session::signOut();
            $this->redirect('/login');
        }
    }

    private function renderForm(
        bool $isRegistration,
        ?string $error = null,
        string $name = '',
        string $email = '',
        ?string $notice = null,
    ): void {
        if ($error !== null) {
            http_response_code(422);
        }

        $csrf = Session::csrfToken();
        require __DIR__ . '/../Views/auth.php';
    }

    private function field(string $name): string
    {
        $value = $_POST[$name] ?? '';

        return is_string($value) ? $value : '';
    }

    private function email(): string
    {
        return strtolower(trim($this->field('email')));
    }

    private function validPassword(string $password): bool
    {
        return $password !== '' && strlen($password) <= 72 && !str_contains($password, "\0");
    }

    private function guestOnly(): bool
    {
        if (Session::user() !== null) {
            $this->redirect('/');
            return false;
        }

        return true;
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

    private function redirect(string $path): void
    {
        header('Location: ' . $path, true, 303);
    }
}
