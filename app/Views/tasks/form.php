<?php
$title = $id === null ? 'Adicionar tarefa' : 'Editar tarefa';
$action = $id === null ? '/tarefas/nova' : '/tarefas/editar?id=' . $id;
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$showLogout = true;
require __DIR__ . '/../partials/head.php';
?>
<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>
    <main class="container page narrow stack">
        <h1><?= $title ?></h1>
        <?php if ($error !== null): ?>
            <p class="message message-error" role="alert"><?= $escape($error) ?></p>
        <?php endif; ?>
        <form class="panel stack" action="<?= $escape($action) ?>" method="post">
            <input type="hidden" name="csrf" value="<?= $escape($csrf) ?>">
            <div class="field">
                <label for="titulo">Título</label>
                <input id="titulo" name="titulo" value="<?= $escape($task['titulo']) ?>" maxlength="150" required>
            </div>
            <div class="field">
                <label for="disciplina">Disciplina</label>
                <input id="disciplina" name="disciplina" value="<?= $escape($task['disciplina']) ?>" maxlength="120" required>
            </div>
            <div class="field-grid">
                <div class="field">
                    <label for="prazo">Prazo (opcional)</label>
                    <input type="date" id="prazo" name="prazo" value="<?= $escape($task['prazo'] ?? '') ?>" min="1000-01-01" max="9999-12-31">
                </div>
                <div class="field">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <?php foreach ($statuses as $value => $label): ?>
                            <option value="<?= $escape($value) ?>"<?= $task['status'] === $value ? ' selected' : '' ?>><?= $escape($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="actions">
                <button class="button" type="submit">Salvar tarefa</button>
                <a class="button button-secondary" href="/tarefas">Cancelar</a>
            </div>
        </form>
    </main>
</body>
</html>
