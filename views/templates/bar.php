<div class="bar">
    <a
        href=<?php echo ($isAuth) ? '/logout' : '/login'; ?>
        class="logout">
        <?php echo ($isAuth) ? translate('logout') : translate('login'); ?>
    </a>
</div>