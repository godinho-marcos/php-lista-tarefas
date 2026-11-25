<?php if ($tasks === []): ?>
    <p>Você ainda não tem tarefas cadastradas.</p>
<?php else: ?>
    <?php
    $escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $statuses = \App\Models\TaskRepository::STATUSES;
    ?>
    <table>
        <caption>Tarefas da sua conta</caption>
        <thead>
            <tr>
                <th scope="col">Título</th>
                <th scope="col">Disciplina</th>
                <th scope="col">Prazo</th>
                <th scope="col">Status</th>
                <?php if ($showActions ?? false): ?>
                    <th scope="col">Ações</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= $escape($task['titulo']) ?></td>
                    <td><?= $escape($task['disciplina']) ?></td>
                    <td>
                        <?php if ($task['prazo'] === null): ?>
                            Sem prazo
                        <?php else: ?>
                            <time datetime="<?= $escape($task['prazo']) ?>"><?= (new DateTimeImmutable($task['prazo']))->format('d/m/Y') ?></time>
                        <?php endif; ?>
                    </td>
                    <td><?= $escape($statuses[$task['status']]) ?></td>
                    <?php if ($showActions ?? false): ?>
                        <td>
                            <a href="/tarefas/editar?id=<?= (int) $task['id'] ?>">Editar</a>
                            <form action="/tarefas/excluir" method="post" onsubmit="return confirm('Excluir esta tarefa?')">
                                <input type="hidden" name="csrf" value="<?= $escape($csrf) ?>">
                                <input type="hidden" name="id" value="<?= (int) $task['id'] ?>">
                                <button type="submit">Excluir</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
