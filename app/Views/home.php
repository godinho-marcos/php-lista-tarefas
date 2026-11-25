<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
    <main>
        <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
        <p>Olá, <?= htmlspecialchars($user['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>.</p>
        <p><a href="/tarefas">Gerenciar tarefas</a></p>
        <section aria-labelledby="minhas-tarefas">
            <h2 id="minhas-tarefas">Minhas tarefas</h2>
            <?php require __DIR__ . '/tasks/table.php'; ?>
        </section>
        <form action="/logout" method="post">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit">Sair</button>
        </form>
    </main>
</body>
</html>
