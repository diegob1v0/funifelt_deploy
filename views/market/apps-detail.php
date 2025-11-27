<div class="app-detail-page">
    <div class="app">
        <div class="app__container">

            <div class="app__header">

                <div class="app__image-wrapper">
                    <img src="<?= $pathImage ?>"
                        alt="<?= s($app->name) ?>"
                        class="app__image">
                </div>

                <div class="app__meta">
                    <h2 class="app__title"><?= s($app->name) ?></h2>
                    <p class="app__company"><?= s($company->name) ?></p>

                    <div class="app__buttons">
                        <?php

                        if ($login == false) {
                        ?>
                            <a href="/login" class="btn">
                                <?= translate('login'); ?>
                            </a>
                            <?php
                        } else {
                            if ($isPurchased || $app->price == 0): ?>
                                <?php if (!$isMobile): ?>
                                    <button class="btn btn-disabled"><?= translate('only_mobile_available'); ?></button>
                                <?php else: ?>
                                    <a href="<?= $pathAPK ?>" class="btn"><?= translate('install'); ?></a>
                                <?php endif; ?>
                            <?php else: ?>
                                <button id="btn-shop" class="btn">
                                    $<?= number_format($app->price, 2) ?>
                                </button>

                                <div id="paypal-button-container"
                                    style="display:none;"
                                    data-price="<?= number_format($app->price, 2) ?>"
                                    data-name="<?= s($app->name) ?>"
                                    data-id="<?= $app->id ?>"
                                    data-user="<?= $userID ?>">
                                </div>

                        <?php endif;
                        }
                        ?>
                    </div>

                </div>

            </div>

            <div class="app__info">
                <h3><?php echo translate('about_app') ?></h3>
                <p><?= s($app->description) ?></p>
            </div>

        </div>
    </div>

</div>