<?php
$title = 'Gerenciar tarefas';
$showLogout = true;
$showActions = true;
require __DIR__ . '/../partials/head.php';
?>
<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>
    <main class="container page stack">
        <div class="page-heading">
            <h1>Gerenciar tarefas</h1>
            <nav class="actions" aria-label="Tarefas">
                <a class="button button-secondary" href="/">Voltar ao dashboard</a>
                <a class="button" href="/tarefas/nova">Adicionar tarefa</a>
            </nav>
        </div>
        <?php if ($notice !== null): ?>
            <p class="message message-success" role="status"><?= htmlspecialchars($notice, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
        <?php endif; ?>
        <div class="panel">
            <?php require __DIR__ . '/table.php'; ?>
        </div>
    </main>
</body>
</html>
