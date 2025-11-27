<?php

namespace Controllers;

use Model\App;
use Model\Company;
use Model\Transactions;
use Model\AppCompany;
use MVC\Router;
use Model\User;

class MarketController
{

    public static function index(Router $router)
    {
        $auth = getAuth();

        $scripts = [
            '/build/js/apps.js',
            '/build/js/header.js'
        ];
        // Render to view
        $router->render('market/apps', [
            'title' => translate('Apps'),
            'page' => 'apps',
            'isAuth' => $auth['isAuth'],
            'roleID' => $auth['roleID'],
            'scripts' => $scripts
        ]);
    }


    public static function app_detail(Router $router)
    {
        $auth = getAuth();
        $id = validateOrRedirect('/');
        $userID = $_SESSION['id'] ?? null;

        if ($auth['isAuth'] === false) {
            $login = false;
        }

        // Get App
        $app = App::find($id);

        if (!$app) {
            header('Location: /');
        }

        $isMobile = isMobileDevice();

        $company = Company::find($app->company_id);
        $companyName = safeFolderName($company->name);
        $folderAPPS = "/build/img/apps/{$companyName}/{$app->image}";
        $folderAPK = "/build/apks/{$companyName}/{$app->download_url}";

        // Check if the user already has the application
        if ($userID) {
            $appPurchased = Transactions::SQL("CALL GetPayInfo($userID, $app->id);");
        }

        if (!empty($appPurchased)) {
            $isPurchased = true;
        } else {
            $isPurchased = false;
        }


        $scripts = [
            'https://www.paypal.com/sdk/js?client-id=Abv_vXVi7I1Ac2TA0_fNQNvx_Gp1rrzPFE4ixSr_fZOQ3MUYgojdF0XFRhVVJuvTinVKpEOz8wj9Ou7K&currency=USD',
            '/build/js/apps.js',
            '/build/js/header.js',
            '/build/js/paypal.js'
        ];

        // Render to view
        $router->render('market/apps-detail', [
            'title' => translate('Apps'),
            'page' => 'apps',
            'isAuth' => $auth['isAuth'],
            'roleID' => $auth['roleID'],
            'userID' => $userID,
            'app' => $app,
            'login' => $login ?? true,
            'isPurchased' => $isPurchased,
            'isMobile' => $isMobile,
            'pathImage' => $folderAPPS,
            'pathAPK' => $folderAPK,
            'company' => $company,
            'scripts' => $scripts
        ]);
    }


    public static function admin_apps(Router $router)
    {

        $auth = getAuth();
        redirect($auth, ['2', '3']);
        $result = $_GET['result'] ?? null;

        $companyId = $_SESSION['company_id'];
        $roleId = $_SESSION['roleID'];

        $apps = AppCompany::SQL("CALL SP_Get_Applications_ByCompany($roleId, $companyId);");

        foreach ($apps as &$app) {
            $companyName = safeFolderName($app->company_name);
            $app->pathImage = "/build/img/apps/{$companyName}/{$app->image}";
        }

        // Render to view
        $router->render('market/apps/admin-apps', [
            'title' => translate('admin_apps'),
            'page' => 'admin_apps',
            'isAuth' => $auth['isAuth'],
            'roleID' => $auth['roleID'],
            'apps' => $apps,
            'result' => $result,
            'scripts' => [
                '/build/js/searcherTables.js',
                '/build/js/confirmDelete.js',
                'https://cdn.jsdelivr.net/npm/sweetalert2@11'
            ],

        ]);
    }

    public static function admin_allies(Router $router)
    {

        $auth = getAuth();
        redirect($auth, ['3']);
        $result = $_GET['result'] ?? null;

        $allies = Company::SQL("CALL SP_Get_Allies()");


        foreach ($allies as &$allie) {
            $companyName = safeFolderName($allie->name);
            $allie->pathImage = "/build/img/allies/{$companyName}/{$allie->logo}";
        }

        // Render to view
        $router->render('market/allies/admin-allies', [
            'title' => translate('admin_allies'),
            'page' => 'admin_allies',
            'isAuth' => $auth['isAuth'],
            'roleID' => $auth['roleID'],
            'result' => $result,
            'allies' => $allies,
            'scripts' => [
                '/build/js/searcherTables.js',
                '/build/js/confirmDelete.js',
                'https://cdn.jsdelivr.net/npm/sweetalert2@11'
            ]
        ]);
    }

    public static function users(Router $router)
    {
        $auth = getAuth();
        redirect($auth, ['3']);
        $result = $_GET['result'] ?? null;

        // Get users
        $users = User::SQL('SELECT * FROM users ORDER BY name, email;');

        // Render to view
        $router->render('market/users/admin-users', [
            'title' => translate('admin_users'),
            'page' => 'admin_users',
            'isAuth' => $auth['isAuth'],
            'roleID' => $auth['roleID'],
            'result' => $result,
            'users' => $users,
            'scripts' => [
                '/build/js/searcherTables.js',
                '/build/js/confirmDelete.js',
                'https://cdn.jsdelivr.net/npm/sweetalert2@11'
            ]
        ]);
    }

    public static function ayuda(Router $router)
    {
        $auth = getAuth();

        // Render to view
        $router->render('templates/ayuda', [
            'title' => 'Centro de Ayuda',
            'page' => 'ayuda',
            'isAuth' => $auth['isAuth'],
            'roleID' => $auth['roleID'],
            'scripts' => ['/build/js/header.js']
        ]);
    }
    public static function admin_dashboard(Router $router)
    {
        $auth = getAuth();
        redirect($auth, ['2', '3']);

        $router->render('market/dashboard/dashboard', [
            'title' => translate('control_panel'),
            'page' => 'dashboard',
            'isAuth' => $auth['isAuth'],
            'roleID' => $auth['roleID'],
            // AQUÍ AGREGAMOS EL SCRIPT NUEVO
            'scripts' => [
                'https://cdn.jsdelivr.net/npm/chart.js', // Librería externa
                'https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0',
                '/build/js/dashboard/dashboard.js'       // Tu lógica (ajusta la ruta según tu gulp)
            ]
        ]);
    }
}
