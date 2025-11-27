<?php
// Datos para el layout
$titulo  = 'Apps';
$page    = 'apps';
$scripts = ['/build/js/apps.js']; // SOLO apps.js, NADA de header.js
?>

<div class="apps">
    <!-- 1. Slider de Banners (Ahora fuera del contenedor principal) -->
    <section class="hero-banner">
        <div class="slider-container" id="appSlider">
            <!-- Banners se insertan aquí desde JS -->
        </div>
        <div class="slider-controls">
            <button id="prevBtn" class="slider-btn">‹</button>
            <button id="nextBtn" class="slider-btn">›</button>
        </div>
    </section>

    <main class="apps-content">
        <!-- 2. Bloque de Apps de Funifelt -->
        <div class="apps-block apps-block--funifelt">
            <div class="container">
                <h2 class="section-title" id="primary-section-title">Nuestras Aplicaciones</h2>
                <p class="section-subtitle" id="primary-section-subtitle">
                    Desarrolladas con amor por el equipo de FUNIFELT.
                </p>
                <section class="app-gallery" id="funifeltApps">
                    <!-- Tarjetas de apps se insertan aquí -->
                </section>
            </div>
        </div>

        <!-- 3. Bloque del Banner Promocional -->
        <div class="apps-block apps-block--promo">
            <div class="container">
                <section class="promo-banner" id="promoBanner">
                    <!-- Contenido del banner se inserta aquí -->
                </section>
            </div>
        </div>

        <!-- 4. Bloque de Apps de Aliados -->
        <div class="apps-block apps-block--allies">
            <div class="container">
                <h2 class="section-title">Nuevas Apps de Aliados</h2>
                <p class="section-subtitle">Descubre herramientas increíbles de nuestros partners.</p>
                <section class="app-gallery" id="newApps">
                    <!-- Tarjetas de apps se insertan aquí -->
                </section>
            </div>
        </div>
    </main>
</div>