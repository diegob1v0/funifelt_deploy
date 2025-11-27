<?php include_once __DIR__ . '/../header-market.php'; ?>

<div class="admin-container">

    <?php
    if ($result) {
        $message = showNotification(intval($result));

        if ($message) {
    ?>
            <div class="alert success">
                <?php echo s($message); ?>
            </div>
    <?php
        }
    }
    ?>

    <div class="table-bar">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input
            id="searcher"
            type="text"
            placeholder="<?php echo translate('search_app'); ?>" />

        <a href="/admin/apps/new/app"><i class="fa-solid fa-plus"></i><?php echo translate('new_app'); ?></a>
    </div>

    <div class="admin">
        <table class="admin-content">
            <thead>
                <td><?php echo translate('photo'); ?></td>
                <td><?php echo translate('name'); ?></td>
                <td><?php echo translate('description'); ?></td>
                <td><?php echo translate('version'); ?></td>
                <td><?php echo translate('price'); ?></td>
                <td><?php echo translate('size') . ' (MB)'; ?></td>
                <?php
                if ($roleID === '3') {
                ?>
                    <td><?php echo translate('owner'); ?></td>
                <?php } ?>
                <td><?php echo translate('actions'); ?></td>
            </thead>
            <tbody>
                <?php
                foreach ($apps as $app) { ?>
                    <tr>
                        <td><img src="<?php echo s($app->pathImage); ?>" class="table-image"></td>
                        <td><?php echo s($app->app_name); ?></td>
                        <td><?php echo s($app->description); ?></td>
                        <td><?php echo s($app->version); ?></td>
                        <td><?php echo s($app->price); ?></td>
                        <td><?php echo s($app->size_mb); ?></td>
                        <?php
                        if ($roleID === '3') {
                        ?>
                            <td><?php echo s($app->company_name); ?></td>
                        <?php } ?>

                        <td>
                            <div class="actions-admin">
                                <a href="/admin/apps/update/app?id=<?php echo s($app->id); ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                <form id="deleteForm-<?php echo $app->id; ?>" method="POST" action="/admin/apps/delete/app">
                                    <input type="hidden" name="id" value="<?php echo $app->id; ?>">
                                    <button type="button" onclick="confirmDelete('<?php echo $app->id; ?>')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once __DIR__ . '/../footer-market.php'; ?>