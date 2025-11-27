<h3><?php echo translate('basic_information') ?></h3>

<div class="field">
    <label for="name"><?php echo translate('allie_name'); ?></label>
    <input type="text" id="name" name="name" placeholder="<?php echo translate('ex_allie_name'); ?>" value="<?php echo s($allie->name); ?>">
</div>

<div class="field">
    <label for="description"><?php echo translate('description'); ?></label>
    <textarea id="description" name="description" rows="4" placeholder="<?php echo translate('allie_description'); ?>"><?php echo s($allie->description); ?></textarea>
</div>

<h3><?php echo translate('administrators'); ?></h3>

<div class="field">
    <label for="searchUser"><?php echo translate('search_user'); ?></label>
    <input
        type="text"
        id="searchUser"
        placeholder="<?php echo translate('type_user_name_or_email'); ?>"
        autocomplete="off">

    <div id="searchResults" class="dropdown-results"></div>
</div>

<ul id="selectedAdmins" class="selected-list"></ul>

<h3><?php echo translate('visual_resources') ?></h3>

<div class="field">
    <label for="image"><?php echo translate('allie_image'); ?></label>
    <input type="file" id="image" name="allie[logo]" accept="image/*">
    <small><?php echo translate('format_permited'); ?></small>
    <?php if ($allie->logo): ?>
        <img src="/build/img/allies/<?php echo $pathImage; ?>" class="small-image">
    <?php endif; ?>
</div>

<input type="hidden" name="admins" id="adminsInput">


<script>
    const assignedAdmins = <?php echo json_encode($assignedAdmins); ?>;
    const allUsers = <?php echo json_encode($users); ?>;
</script>