<header class="site-header">
    <div class="container site-header-inner">
        <a class="brand" href="/">
            <span class="brand-mark" aria-hidden="true">✓</span>
            Agenda de Estudos
        </a>
        <?php if (!empty($showLogout)): ?>
            <form action="/logout" method="post">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
                <button class="button button-secondary" type="submit">Sair</button>
            </form>
        <?php endif; ?>
    </div>
</header>
