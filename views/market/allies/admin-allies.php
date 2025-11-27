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
            placeholder="<?php echo translate('search_allie'); ?>" />

        <a href="/admin/allies/new/allie"><i class="fa-solid fa-plus"></i><?php echo translate('new_allie'); ?></a>
    </div>

    <div class="admin">
        <table class="admin-content">
            <thead>
                <td><?php echo translate('photo'); ?></td>
                <td><?php echo translate('name'); ?></td>
                <td><?php echo translate('description'); ?></td>
                <td><?php echo translate('admin'); ?></td>
                <td><?php echo translate('actions'); ?></td>
            </thead>
            <tbody>
                <?php
                foreach ($allies as $allie) { ?>
                    <tr>
                        <td><img src="<?php echo s($allie->pathImage); ?>" class="table-image"></td>
                        <td><?php echo s($allie->name); ?></td>
                        <td><?php echo s($allie->description); ?></td>
                        <td><?php echo s($allie->admin_emails); ?></td>
                        <td>
                            <div class="actions-admin">
                                <a href="/admin/allies/update/allie?id=<?php echo s($allie->id); ?>">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form method="POST" class="form-delete" action="/admin/allies/delete/allie">
                                    <input type="hidden" name="id" value="<?php echo s($allie->id); ?>">
                                    <button type="button" onclick="confirmDelete('<?php echo s($allie->id); ?>')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


<?php include_once __DIR__ . '/../footer-market.php'; ?>