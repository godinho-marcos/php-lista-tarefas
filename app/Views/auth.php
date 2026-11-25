<?php
$title = $isRegistration ? 'Criar conta' : 'Entrar';
$action = $isRegistration ? '/cadastro' : '/login';
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<?php require __DIR__ . '/partials/head.php'; ?>
<body>
    <?php require __DIR__ . '/partials/header.php'; ?>
    <main class="container page sign-in">
        <div class="panel stack">
            <h1><?= $escape($title) ?></h1>
            <?php if ($notice !== null): ?>
                <p class="message message-success" role="status"><?= $escape($notice) ?></p>
            <?php endif; ?>
            <?php if ($error !== null): ?>
                <p class="message message-error" role="alert"><?= $escape($error) ?></p>
            <?php endif; ?>
            <form class="stack" action="<?= $escape($action) ?>" method="post">
                <input type="hidden" name="csrf" value="<?= $escape($csrf) ?>">
                <?php if ($isRegistration): ?>
                    <div class="field">
                        <label for="nome">Nome</label>
                        <input id="nome" name="nome" value="<?= $escape($name) ?>" autocomplete="name" maxlength="120" required>
                    </div>
                <?php endif; ?>
                <div class="field">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="<?= $escape($email) ?>" autocomplete="username" maxlength="254" required>
                </div>
                <div class="field">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" autocomplete="<?= $isRegistration ? 'new-password' : 'current-password' ?>" <?= $isRegistration ? 'minlength="8"' : '' ?> required>
                </div>
                <button class="button button-wide" type="submit"><?= $escape($title) ?></button>
            </form>
            <?php if ($isRegistration): ?>
                <p>Já tem conta? <a href="/login">Entrar</a></p>
            <?php else: ?>
                <p>Ainda não tem conta? <a href="/cadastro">Criar conta</a></p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
