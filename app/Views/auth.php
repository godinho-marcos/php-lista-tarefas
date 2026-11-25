<?php
$title = $isRegistration ? 'Criar conta' : 'Entrar';
$action = $isRegistration ? '/cadastro' : '/login';
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $escape($title) ?> | Agenda de Estudos</title>
</head>
<body>
    <main>
        <h1><?= $escape($title) ?></h1>
        <?php if ($notice !== null): ?>
            <p role="status"><?= $escape($notice) ?></p>
        <?php endif; ?>
        <?php if ($error !== null): ?>
            <p role="alert"><?= $escape($error) ?></p>
        <?php endif; ?>
        <form action="<?= $escape($action) ?>" method="post">
            <input type="hidden" name="csrf" value="<?= $escape($csrf) ?>">
            <?php if ($isRegistration): ?>
                <p>
                    <label for="nome">Nome</label><br>
                    <input id="nome" name="nome" value="<?= $escape($name) ?>" autocomplete="name" maxlength="120" required>
                </p>
            <?php endif; ?>
            <p>
                <label for="email">E-mail</label><br>
                <input type="email" id="email" name="email" value="<?= $escape($email) ?>" autocomplete="username" maxlength="254" required>
            </p>
            <p>
                <label for="senha">Senha</label><br>
                <input type="password" id="senha" name="senha" autocomplete="<?= $isRegistration ? 'new-password' : 'current-password' ?>" <?= $isRegistration ? 'minlength="8"' : '' ?> required>
            </p>
            <button type="submit"><?= $escape($title) ?></button>
        </form>
        <?php if ($isRegistration): ?>
            <p>Já tem conta? <a href="/login">Entrar</a></p>
        <?php else: ?>
            <p>Ainda não tem conta? <a href="/cadastro">Criar conta</a></p>
        <?php endif; ?>
    </main>
</body>
</html>
