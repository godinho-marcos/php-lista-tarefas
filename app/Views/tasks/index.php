<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerenciar tarefas | Agenda de Estudos</title>
</head>
<body>
    <main>
        <h1>Gerenciar tarefas</h1>
        <nav aria-label="Tarefas">
            <a href="/">Voltar ao dashboard</a>
            <a href="/tarefas/nova">Adicionar tarefa</a>
        </nav>
        <?php if ($notice !== null): ?>
            <p role="status"><?= htmlspecialchars($notice, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
        <?php endif; ?>
        <?php $showActions = true; ?>
        <?php require __DIR__ . '/table.php'; ?>
    </main>
</body>
</html>
