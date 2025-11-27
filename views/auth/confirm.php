<div class="login confirm">
    <?php include_once __DIR__ . '/../templates/header-login.php'; ?>
    <div class="container-sm">

        <?php include_once __DIR__ . '/../templates/alerts.php'; ?>

        <?php
        if (array_key_exists('success', $alerts)) {

        ?>
            <div class="actions">
                <a href="/login"><?php echo translate('login') ?? ''; ?></a>
            </div>
        <?php
        }
        ?>
    </diV>
</div>