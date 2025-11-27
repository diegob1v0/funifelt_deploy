<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funifelt Market | <?php echo $title ?? ''; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300..700&family=Kelly+Slab&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/build/css/app.css">
</head>

<body class="<?php echo $body ?? ''; ?>">

    <?php
    // Header público SOLO en páginas públicas
    // (apps, allies, detalle de app, ayuda, etc.)
    if (isset($page) && in_array($page, ['apps', 'allies', 'app_detail', 'ayuda'])) {
        echo $headerHtml ?? '';
    }
    ?>


    <?php echo $contenido; ?>


    <?php
    if ($title === translate('Apps') || $page === 'app_detail') {
        include_once __DIR__ . '/templates/footer.php';
    }
    ?>


    <?php
    // Inyección dinámica de scripts al final del body
    if (isset($script)) { // Support for old single script
        echo $script;
    }
    if (isset($scripts)) { // Support for new script array
        foreach ($scripts as $script_url) {
            echo '<script src="' . $script_url . '"></script>';
        }
    }
    ?>
</body>

</html>