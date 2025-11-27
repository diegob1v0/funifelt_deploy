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
    </div>


    <div class="admin">
        <table class="admin-content">
            <thead>
                <td><?php echo translate('name'); ?></td>
                <td><?php echo translate('email'); ?></td>
                <td><?php echo translate('role'); ?></td>
                <td><?php echo translate('actions'); ?></td>
            </thead>
            <tbody>
                <?php
                foreach ($users as $user) {
                ?>
                    <tr>
                        <td><?php echo s($user->name); ?></td>
                        <td><?php echo s($user->email); ?></td>
                        <?php $roleText = $user->role_id === '3' ? translate('admin') : ($user->role_id === '2' ? translate('Allie') : translate('user')); ?>
                        <td><?php echo s($roleText); ?></td>
                        <td>
                            <div class="actions-admin">
                                <form id="deleteForm-<?php echo $user->id; ?>" method="POST" action="/admin/users/delete/user">
                                    <input type="hidden" name="id" value="<?php echo $user->id; ?>">
                                    <button type="button" onclick="confirmDelete('<?php echo $user->id; ?>')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<?php include_once __DIR__ . '/../footer-market.php'; ?>