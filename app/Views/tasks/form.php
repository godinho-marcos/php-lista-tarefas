<?php
$title = $id === null ? 'Adicionar tarefa' : 'Editar tarefa';
$action = $id === null ? '/tarefas/nova' : '/tarefas/editar?id=' . $id;
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?> | Agenda de Estudos</title>
</head>
<body>
    <main>
        <h1><?= $title ?></h1>
        <?php if ($error !== null): ?>
            <p role="alert"><?= $escape($error) ?></p>
        <?php endif; ?>
        <form action="<?= $escape($action) ?>" method="post">
            <input type="hidden" name="csrf" value="<?= $escape($csrf) ?>">
            <p>
                <label for="titulo">Título</label><br>
                <input id="titulo" name="titulo" value="<?= $escape($task['titulo']) ?>" maxlength="150" required>
            </p>
            <p>
                <label for="disciplina">Disciplina</label><br>
                <input id="disciplina" name="disciplina" value="<?= $escape($task['disciplina']) ?>" maxlength="120" required>
            </p>
            <p>
                <label for="prazo">Prazo (opcional)</label><br>
                <input type="date" id="prazo" name="prazo" value="<?= $escape($task['prazo'] ?? '') ?>" min="1000-01-01" max="9999-12-31">
            </p>
            <p>
                <label for="status">Status</label><br>
                <select id="status" name="status" required>
                    <?php foreach ($statuses as $value => $label): ?>
                        <option value="<?= $escape($value) ?>"<?= $task['status'] === $value ? ' selected' : '' ?>><?= $escape($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <button type="submit">Salvar tarefa</button>
            <a href="/tarefas">Cancelar</a>
        </form>
    </main>
</body>
</html>
