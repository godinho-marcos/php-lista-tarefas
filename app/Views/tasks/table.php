<?php if ($tasks === []): ?>
    <p>Você ainda não tem tarefas cadastradas.</p>
<?php else: ?>
    <?php
    $escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $statuses = [
        'pendente' => 'Pendente',
        'em_andamento' => 'Em andamento',
        'concluida' => 'Concluída',
    ];
    ?>
    <table>
        <caption>Tarefas da sua conta</caption>
        <thead>
            <tr>
                <th scope="col">Título</th>
                <th scope="col">Disciplina</th>
                <th scope="col">Prazo</th>
                <th scope="col">Status</th>
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
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
