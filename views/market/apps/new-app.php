<?php include_once __DIR__ . '/../header-market.php'; ?>

<form class="form-admin" action="/admin/apps/new/app" method="POST" enctype="multipart/form-data">

    <?php include_once __DIR__ . '/../../templates/alerts.php'; ?>

    <?php include_once __DIR__ . '/form.php'; ?>


    <div class="form-actions">
        <a href="/admin/apps">
            <i class="fa-solid fa-arrow-left"></i> <?php echo translate('back'); ?>
        </a>

        <button type="submit">
            <i class="fa-solid fa-plus"></i> <?php echo translate('create_app'); ?>
        </button>
    </div>
</form>

<?php include_once __DIR__ . '/../footer-market.php'; ?>