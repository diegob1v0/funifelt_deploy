<h3><?php echo translate('basic_information') ?></h3>

<div class="field">
    <label for="name"><?php echo translate('app_name'); ?></label>
    <input type="text" id="name" name="name" placeholder="<?php echo translate('ex_app_name'); ?>" value="<?php echo s($app->name); ?>">
</div>

<div class="field">
    <label for="description"><?php echo translate('description'); ?></label>
    <textarea id="description" name="description" rows="4" placeholder="<?php echo translate('app_description'); ?>"><?php echo s($app->description); ?></textarea>
</div>

<div class="field">
    <label for="category_id"><?php echo translate('category'); ?></label>
    <select id="category_id" name="category_id">
        <option value="" disabled selected>-- <?php echo translate('select_option'); ?> --</option>
        <?php
        foreach ($categories as $category):
        ?>
            <option
                <?php echo $app->category_id === $category->id ? 'selected' : ''; ?>
                value="<?php echo s($category->id); ?>">
                <?php echo
                translate($category->name);
                ?>
            </option>
        <?php
        endforeach;
        ?>

    </select>
</div>

<div class="field">
    <label for="version"><?php echo translate('version'); ?></label>
    <input id="version" name="version" placeholder="<?php echo translate('ex_app_version'); ?>" value="<?php echo s($app->version); ?>">
</div>

<div class="field">
    <label for="size_mb"><?php echo translate('size'); ?></label>
    <input id="size_mb" name="size_mb" placeholder="<?php echo translate('ex_app_size'); ?>" value="<?php echo s($app->size_mb); ?>">
</div>

<h3><?php echo translate('pay') ?></h3>

<div class="field">
    <label for="type"><?php echo translate('app_type'); ?></label>
    <select id="type" name="type">
        <option value="" disabled selected>-- <?php echo translate('select_option'); ?> --</option>
        <option value="free" <?php echo $type === 'free' ? 'selected' : '';  ?>><?php echo translate('free'); ?></option>
        <option value="pay" <?php echo $type === 'pay' ? 'selected' : '';  ?>><?php echo translate('of_pay'); ?></option>
    </select>
</div>
<div class="field" id="group-price">
    <label for="price"><?php echo translate('price'); ?></label>
    <input type="number" id="price" name="price" min="0" step="0.01" placeholder="<?php echo translate('ex_app_price'); ?>" value="<?php echo s($app->price); ?>">
</div>

<h3><?php echo translate('visual_resources') ?></h3>

<div class="field">
    <label for="image"><?php echo translate('app_image'); ?></label>
    <input type="file" id="image" name="app[image]" accept="image/*">
    <small><?php echo translate('format_permited'); ?></small>
    <?php if ($app->image): ?>
        <img src="/build/img/apps/<?php echo $pathImage; ?>" class="small-image">
    <?php endif; ?>
</div>

<h3><?php echo translate('resources') ?></h3>

<div class="field">
    <label for="download_url"><?php echo translate('apk'); ?></label>
    <input type="file" id="download_url" name="app[download_url]" accept=".apk">
</div>