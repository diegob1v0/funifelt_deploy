<div class="login">
    <?php include_once __DIR__ . '/../templates/header-login.php'; ?>
    <div class="container-sm">
        <p class="tagline"><?php echo translate('welcome') ?? ''; ?></p>
        <p class="page-description"><?php echo translate('new_password') ?? ''; ?></p>

        <?php include_once __DIR__ . '/../templates/alerts.php'; ?>

        <?php if ($show) { ?>
            <form class="form" method="POST">
                <div class="field">
                    <label for="password"><?php echo translate('password') ?? 'New Password'; ?></label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="<?php echo translate('password_placeholder') ?? 'New Password'; ?>" />
                </div>
                <input type="submit" class="button" value="<?php echo translate('save_password') ?? 'Sent instructions'; ?>">
            </form>

        <?php } ?>
        <div class="actions">
            <a href="/login"><?php echo translate('login-rout') ?></a>
            <a href="/create"><?php echo translate('create-account-rout') ?></a>
        </div>
    </diV>
</div>