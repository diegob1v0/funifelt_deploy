<?php

namespace Controllers;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager as Image;
use Model\App;
use MVC\Router;
use Model\Category;
use Model\Company;
use Model\CompanyUser;

class AppController
{

    public static function create(Router $router)
    {

        $auth = getAuth();
        redirect($auth, ['2', '3']);
        $companyUser = CompanyUser::where('user_id', $_SESSION['id']);

        if (!$companyUser) {
            header('Location: /');
        }

        $alerts = [];

        $type = $_POST['type'] ?? null;

        // Model App

        $app = new App;

        // Get All Categoriess
        $categories = Category::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $app = new App($_POST);
            // Upload image
            $imageName = md5(uniqid(rand(), true)) . ".png";
            $apkName = md5(uniqid(rand(), true)) . ".apk";

            // Set image
            if (!$_FILES['app']['tmp_name']['image']) {
                App::setAlerts('error', translate('image_required'));
            }

            if (!$app->category_id) {
                App::setAlerts('error', translate('category_required'));
            }

            // Validate APK
            if (!$_FILES['app']['tmp_name']['download_url']) {
                App::setAlerts('error', translate('download_required'));
            }

            // Validate form
            $alerts = $app->validateApp($type);


            if (empty($alerts)) {

                // Create folder for images
                if (!is_dir(FOLDER_IMAGES)) {
                    mkdir(FOLDER_IMAGES);
                }

                // Create folder for APK
                if (!is_dir(FOLDER_APK)) {
                    mkdir(FOLDER_APK);
                }

                $app->company_id = $_SESSION['company_id'];

                // Set Image
                $manager = new Image(Driver::class);
                $image = $manager->read($_FILES['app']['tmp_name']['image'])->cover(800, 800);
                $app->setImage($imageName);

                // Create Folder by company
                $company = Company::find($app->company_id);
                $companyName = safeFolderName($company->name);
                $folderOwner = createCompanyImageFolder($companyName, 'apps');
                $folderAPK = createCompanyImageFolder($companyName, 'apks');

                $apkPath = $folderAPK . $apkName;

                // Save APK
                if (move_uploaded_file($_FILES['app']['tmp_name']['download_url'], $apkPath)) {
                    $app->setApk($apkName);
                } else {
                    App::setAlerts('error', translate('apk_failed'));
                }


                if ($type === 'free') {
                    $app->price = 0.00;
                }

                $alerts = App::getAlerts();

                if (empty($alerts)) {

                    // Save in database
                    $result = $app->save();

                    if (!$result) {
                        header('Location: /admin/apps?result=4');
                    }

                    header('Location: /admin/apps?result=1');

                    // Save image in the folder
                    $image->save($folderOwner . $imageName);
                }
            }
        }

        $alerts = App::getAlerts();

        // Render to view
        $router->render('market/apps/new-app', [
            'title' => translate('new_app'),
            'page' => 'form_apps',
            'isAuth' => $auth['isAuth'],
            'roleID' => $auth['roleID'],
            'alerts' => $alerts,
            'type' => $type,
            'app' => $app,
            'categories' => $categories,
            'script' => '<script src="/build/js/menuPay.js"></script>'
        ]);
    }

    public static function update(Router $router)
    {

        $auth = getAuth();
        redirect($auth, ['2', '3']);
        $alerts = [];
        $id = validateOrRedirect('/admin/apps');

        // Get App
        $app = App::find($id);

        if (!$app) {
            header('Location: /admin/apps');
            return;
        }

        if ($_SESSION['roleID'] != '3' && $app->company_id !== $_SESSION['company_id']) {
            header('Location: /admin/apps');
            return;
        }

        $type = $app->price > 0 ? 'pay' : 'free';

        // Get All Categorires
        $categories = Category::all();

        // Get All Companies
        $companies = Company::all();

        // Get Company of app
        $company = Company::find($app->company_id);
        $companyName = safeFolderName($company->name);
        $folderOwner = createCompanyImageFolder($companyName, 'apps');
        $pathImage = $companyName . '/' . $app->image;

        $folderAPK = createCompanyImageFolder($companyName, 'apks');


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $args = $_POST;
            $app->synchronize($args);
            $alerts = $app->validateApp($type);

            if ($type === 'free') {
                $app->price = 0.00;
            }

            // Set image
            if ($_FILES['app']['tmp_name']['image']) {

                // Upload image
                $imageName = md5(uniqid(rand(), true)) . ".png";
                $imageOld = $app->image;

                $manager = new Image(Driver::class);
                $image = $manager->read($_FILES['app']['tmp_name']['image'])->cover(800, 800);
                $app->setImage($imageName);
            }

            if ($_FILES['app']['tmp_name']['download_url']) {
                $apkName = md5(uniqid(rand(), true)) . ".apk";
                $apkOld = $app->download_url;
            }

            $alerts = App::getAlerts();

            if (empty($alerts)) {

                if ($_FILES['app']['tmp_name']['image']) {
                    $route = $folderOwner . $imageOld;

                    if (file_exists($route)) {
                        unlink($route);
                    }

                    // Save Image
                    $image->save($folderOwner . $imageName);
                }

                if ($_FILES['app']['tmp_name']['download_url']) {

                    $route = $folderAPK . $apkOld;
                    $apkPath = $folderAPK . $apkName;

                    if (file_exists($route)) {
                        unlink($route);
                    }

                    // Save APK
                    if (move_uploaded_file($_FILES['app']['tmp_name']['download_url'], $apkPath)) {
                        $app->setApk($apkName);
                    } else {
                        App::setAlerts('error', translate('apk_failed'));
                    }
                }
                // Save in database
                $result = $app->save();

                if (!$result) {
                    header('Location: /admin/apps?result=5');
                }

                header('Location: /admin/apps?result=2');
            }
        }

        $alerts = App::getAlerts();

        // Render to view
        $router->render('market/apps/update-app', [
            'title' => translate('new_app'),
            'page' => 'form_apps',
            'isAuth' => $auth['isAuth'],
            'roleID' => $auth['roleID'],
            'alerts' => $alerts,
            'type' => $type,
            'app' => $app,
            'categories' => $categories,
            'companies' => $companies,
            'pathImage' => $pathImage,
            'script' => '<script src="/build/js/menuPay.js"></script>'
        ]);
    }

    public static function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if ($id) {

                // Find App
                $app = App::find($id);

                if (!$app) {
                    header('Location: /admin/apps');
                    return;
                }

                // Find Company
                $company = Company::find($app->company_id);
                $companyName = $company->name;
                $folderApps = createCompanyImageFolder($companyName, 'apps');
                $folderApks = createCompanyImageFolder($companyName, 'apks');

                // Delete image
                $route = $folderApps . $app->image;

                if (file_exists($route)) {
                    unlink($route);
                }

                // Delete APK
                $route = $folderApks . $app->download_url;
                if (file_exists($route)) {
                    unlink($route);
                }

                $app->delete();

                header('Location: /admin/apps?result=3');
            }
        }
    }

    public static function detalle()
    {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            self::jsonResponse(['error' => 'ID de aplicación no válido.'], 400);
            return;
        }

        $aplicacion = App::findAppById($id);

        if (!$aplicacion) {
            self::jsonResponse(['error' => 'Aplicación no encontrada.'], 404);
            return;
        }

        self::jsonResponse($aplicacion, 200);
    }

    private static function jsonResponse($data, $status)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
