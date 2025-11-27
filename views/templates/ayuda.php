<?php
// Incluimos el header específico de la tienda de aplicaciones
include_once __DIR__ . '/apps-header.php';
?>

<main class="ayuda">
    <div class="ayuda__contenedor">
        <h1 class="ayuda__titulo"><?php echo translate('center_help') ?></h1>

        <div class="ayuda__seccion">
            <h2 class="ayuda__subtitulo"><?php echo translate('faq_title') ?></h2>

            <div class="ayuda__pregunta">
                <h3><?php echo translate('faq_how_download') ?></h3>
                <p><?php echo translate('faq_how_download_desc') ?></p>
            </div>

            <div class="ayuda__pregunta">
                <h3><?php echo translate('faq_apps_free') ?></h3>
                <p><?php echo translate('faq_apps_free_desc') ?></p>
            </div>

            <div class="ayuda__pregunta">
                <h3><?php echo translate('faq_app_not_working') ?></h3>
                <p><?php echo translate('faq_app_not_working_desc') ?></p>
            </div>
        </div>

        <div class="ayuda__seccion">
            <h2 class="ayuda__subtitulo"><?php echo translate('support_contact_title') ?></h2>
            <p><?php echo translate('support_contact_desc') ?></p>
            <ul>
                <li><strong><?php echo translate('email') ?>:</strong> <a href="mailto:soporte@funifelt.com">soporte@funifelt.com</a></li>
                <li><strong><?php echo translate('phone') ?>:</strong> +57 3229507529 (CO) - +31 6 87623593 (NL)</li>
            </ul>
        </div>
    </div>
</main>


<?php
// Incluimos el footer general del sitio
include_once __DIR__ . '/footer.php';
?>