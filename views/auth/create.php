<div class="login">
    <?php include_once __DIR__ . '/../templates/header-login.php'; ?>
    <div class="container-sm">
        <p class="tagline"><?php echo translate('welcome') ?? ''; ?></p>
        <p class="page-description"><?php echo translate('create_account') ?? ''; ?></p>

        <?php include_once __DIR__ . '/../templates/alerts.php'; ?>

        <form class="form" method="POST" action="/create">
            <div class="field">
                <label for="name"><?php echo translate('name') ?? 'Name'; ?></label>
                <input
                    type="name"
                    id="name"
                    name="name"
                    placeholder="<?php echo translate('name_placeholder') ?? 'Your Name'; ?>"
                    value="<?php echo s($user->name); ?>" />
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="<?php echo translate('email_placeholder') ?? 'Email'; ?>"
                    value="<?php echo s($user->email); ?>" />
            </div>
            <div class="field">
                <label for="password"><?php echo translate('password') ?? 'Password'; ?></label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="<?php echo translate('password_placeholder') ?? 'Password'; ?>" />
            </div>
            <div class="field">
                <label for="password2"><?php echo translate('repeat_password') ?? 'Confirm Password'; ?></label>
                <input
                    type="password"
                    id="password2"
                    name="password2"
                    placeholder="<?php echo translate('repeat_password') ?? 'Confirm Password'; ?>" />
            </div>

            <input type="submit" class="button" value="<?php echo translate('create_account') ?? 'Login'; ?>">
        </form>
        <div class="actions">
            <a href="/login"><?php echo translate('login-rout') ?></a>
            <a href="/forget"><?php echo translate('forget-password-rout') ?></a>
        </div>
    </diV>
</div>