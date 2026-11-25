<?php
$showLogout = true;
require __DIR__ . '/partials/head.php';
?>
<body>
    <?php require __DIR__ . '/partials/header.php'; ?>
    <main class="container page stack">
        <div class="page-heading">
            <div>
                <h1>Dashboard</h1>
                <p class="muted">Olá, <?= htmlspecialchars($user['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>.</p>
            </div>
            <div class="actions">
                <a class="button" href="/tarefas">Gerenciar tarefas</a>
            </div>
        </div>
        <section class="panel stack" aria-labelledby="minhas-tarefas">
            <h2 id="minhas-tarefas">Minhas tarefas</h2>
            <?php require __DIR__ . '/tasks/table.php'; ?>
        </section>
    </main>
</body>
</html>
