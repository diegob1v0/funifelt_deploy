<div class="login">
    <?php include_once __DIR__ . '/../templates/header-login.php'; ?>
    <div class="container-sm">
        <p class="tagline"><?php echo translate('welcome') ?? ''; ?></p>
        <p class="page-description"><?php echo translate('forget') ?? ''; ?></p>

        <?php include_once __DIR__ . '/../templates/alerts.php'; ?>

        <form class="form" method="POST" action="/forget" novalidate>
            <div class="field">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="<?php echo translate('email_placeholder') ?? 'Email'; ?>" />
            </div>
            <input type="submit" class="button" value="<?php echo translate('sent_instructions') ?? 'Sent instructions'; ?>">
        </form>
        <div class="actions">
            <a href="/login"><?php echo translate('login-rout') ?></a>
            <a href="/create"><?php echo translate('create-account-rout') ?></a>
        </div>
    </diV>
</div>