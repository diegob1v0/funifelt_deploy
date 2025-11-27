<div class="login">
    <?php include_once __DIR__ . '/../templates/header-login.php'; ?>
    <div class="container-sm">
        <p class="tagline"><?php echo translate('welcome') ?? ''; ?></p>
        <p class="page-description"><?php echo translate('login') ?? ''; ?></p>

        <?php include_once __DIR__ . '/../templates/alerts.php'; ?>

        <form class="form" method="POST" action="/login" novalidate>
            <div class="field">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="<?php echo translate('email_placeholder') ?? 'Email'; ?>" />
            </div>
            <div class="field">
                <label for="password"><?php echo translate('password') ?? 'Password'; ?></label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="<?php echo translate('password_placeholder') ?? 'Password'; ?>" />
            </div>

            <input type="submit" class="button" value="<?php echo translate('login') ?? 'Login'; ?>">
        </form>
        <div class="actions">
            <a href="/create"><?php echo translate('create-account-rout') ?></a>
            <a href="/forget"><?php echo translate('forget-password-rout') ?></a>
        </div>
    </diV>
</div>