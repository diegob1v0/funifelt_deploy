<aside class="sidebar">

    <a href="/">
        <img src="/build/img/branding/logo_white.png" alt="Logo Funifelt Market">
    </a>

    <nav class="sidebar-nav">
        <a
            class="<?php echo ($page === 'apps' ? 'active' : ''); ?>"
            href="/"><?php echo translate('Apps') ?? ''; ?>
        </a>


        <?php
        // NUEVO: Botón Dashboard (Visible para Rol 2 y 3)
        if ($roleID == '2' || $roleID == '3') {
        ?>
            <a
                class="<?php echo ($page === 'dashboard') ? 'active' : ''; ?>"
                href="/admin/dashboard">
                <?php echo translate('control_panel'); ?>
            </a>
        <?php
        }
        ?>

        <?php
        if ($roleID == '2' || $roleID == '3') {
        ?>
            <a
                class="<?php echo ($page === 'admin_apps' || $page === 'form_apps') ? 'active' : ''; ?>"
                href="/admin/apps">
                <?php echo translate('admin_apps') ?? ''; ?>
            </a>
        <?php
        }
        ?>

        <?php
        if ($roleID == '3') {
        ?>
            <a
                class="<?php echo ($page === 'admin_allies' ? 'active' : ''); ?>"
                href="/admin/allies">
                <?php echo translate('admin_allies') ?? ''; ?>
            </a>

            <a
                class="<?php echo ($page === 'admin_users' ? 'active' : ''); ?>"
                href="/admin/users">
                <?php echo translate('admin_users') ?? ''; ?>
            </a>
        <?php
        }
        ?>
    </nav>
</aside>