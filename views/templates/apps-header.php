<header class="apps-header" id="apps-header">
    <div class="apps-header__content">
        <div class="apps-header__logo">
            <a href="/">
                <picture>
                    <source srcset="/build/img/branding/logo_blue_header.webp" type="image/webp">
                    <img loading="lazy" src="/build/img/branding/logo_blue_header.png" alt="Logotipo Funifelt">
                </picture>
            </a>
        </div>

        <button class="apps-header__mobile-menu-btn" id="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </button>

        <div class="apps-header__menu" id="header-menu">
            <nav class="apps-header__nav">
                <a href="/" class="apps-header__link active">Apps</a>
                <a href="#" class="apps-header__link" id="allied-companies-link">
                    <?php echo translate('allie_companies'); ?> <i class="fas fa-chevron-down apps-header__chevron-icon"></i>
                </a>
            </nav>

            <div class="apps-header__spacer"></div>

            <div class="apps-header__actions">
                <div class="apps-header__icons">
                    <button id="search-icon" class="apps-header__icon-btn">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="/ayuda" class="apps-header__icon-btn">
                        <i class="fas fa-question-circle"></i>
                    </a>
                </div>

                <!-- 🔍 Contenedor de búsqueda ahora dentro de las acciones -->
                <div class="apps-header__search-container">
                    <button id="close-search-btn" class="apps-header__icon-btn apps-header__icon-btn--back">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <input type="search" id="search-input" class="apps-header__search-input" placeholder="Buscar apps">
                </div>

                <div class="apps-header__auth">
                    <?php if ($isAuth) {
                        $nombre = isset($_SESSION['name']) && !empty(trim($_SESSION['name'])) ? trim($_SESSION['name']) : null;
                        $email = isset($_SESSION['email']) ? trim($_SESSION['email']) : '';
                        $iniciales = null;

                        if ($nombre) {
                            $palabras = explode(' ', $nombre);
                            if (count($palabras) >= 2) {
                                $iniciales = strtoupper(substr($palabras[0], 0, 1) . substr($palabras[count($palabras) - 1], 0, 1));
                            } else {
                                $iniciales = strtoupper(substr($nombre, 0, 2));
                            }
                        }

                        // ===== Menú por rol =====
                        $role = $roleID ?? null;
                        $role = (int) $role; // nos aseguramos que sea entero
                        $profileMenuItems = [];

                        // roleID 3 -> Super Admin
                        if ($role === 3) {
                            $profileMenuItems = [
                                ['href' => '/', 'label' => translate('Apps')],
                                ['href' => '/admin/dashboard', 'label' => translate('Control_Panel')],
                                ['href' => '/admin/apps', 'label' => translate('admin_apps')],
                                ['href' => '/admin/allies', 'label' => translate('admin_allies')],
                                ['href' => '/admin/users', 'label' => translate('Admin_Users')],
                            ];
                            // roleID 2 -> Admin Empresa
                        } elseif ($role === 2) {
                            $profileMenuItems = [
                                ['href' => '/', 'label' => translate('Apps')],
                                ['href' => '/admin/dashboard', 'label' => translate('Control_Panel')],
                                ['href' => '/admin/apps', 'label' => translate('admin_apps')],
                            ];
                            // roleID 1 u otro -> Usuario común
                        } else {
                            $profileMenuItems = [
                                ['href' => '/', 'label' => translate('Apps')],
                            ];
                        }
                    ?>
                        <!-- Botón con iniciales -->
                        <button type="button" id="profile-menu-toggle" class="apps-header__initials-circle"
                            aria-haspopup="true" aria-expanded="false">
                            <?php echo $iniciales ? $iniciales : '<i class="fas fa-user"></i>'; ?>
                        </button>

                        <!-- Menú desplegable de perfil -->
                        <div class="profile-menu" id="profile-menu" aria-hidden="true">
                            <div class="profile-menu__header">
                                <div class="profile-menu__avatar">
                                    <?php echo $iniciales ? $iniciales : '<i class="fas fa-user"></i>'; ?>
                                </div>
                                <div class="profile-menu__info">
                                    <p class="profile-menu__name">
                                        <?php echo htmlspecialchars($nombre ?? ''); ?>
                                    </p>
                                    <?php if (!empty($email)): ?>
                                        <p class="profile-menu__email">
                                            <?php echo htmlspecialchars($email); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="profile-menu__items">
                                <?php foreach ($profileMenuItems as $item): ?>
                                    <a href="<?php echo $item['href']; ?>" class="profile-menu__item">
                                        <span class="profile-menu__item-label">
                                            <?php echo $item['label']; ?>
                                        </span>
                                    </a>
                                <?php endforeach; ?>
                            </div>

                            <div class="profile-menu__footer">
                                <a href="/logout" class="profile-menu__item profile-menu__item--logout">
                                    <span class="profile-menu__item-label">
                                        <?php echo translate('Logout') ?? 'Cerrar sesión'; ?>
                                    </span>
                                </a>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="apps-header__auth-links">
                            <a href="/login" class="apps-header__link"><?php echo translate('login'); ?></a>
                            <a href="/create" class="apps-header__link apps-header__link--button"><?php echo translate('create_account'); ?></a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="allied-companies-dropdown" id="allied-companies-dropdown"></div>
</header>