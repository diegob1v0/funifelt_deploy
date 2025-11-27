<?php

namespace Controllers;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager as Image;
use Model\Company;
use Model\CompanyUser;
use Model\User;
use MVC\Router;

class CompanyController
{

    public static function create(Router $router)
    {
        $auth = getAuth();
        redirect($auth, ['3']);

        $allie = new Company();
        $alerts = [];

        $users = User::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $allie = new Company($_POST);

            // Upload image
            $imageName = md5(uniqid(rand(), true)) . ".png";

            // Error admin
            if (!$_POST['admins']) {
                Company::setAlerts('error', translate('admin_required'));
            }

            // Error image
            if (!$_FILES['allie']['tmp_name']['logo']) {
                Company::setAlerts('error', translate('image_required'));
            }

            // Validate form
            $alerts = $allie->validateCompany();

            if (empty($alerts)) {

                $companyExists = Company::where('name', $allie->name);
                if ($companyExists) {
                    Company::setAlerts('error', translate('company_exists'));
                    $alerts = Company::getAlerts();
                } else {

                    // Set image
                    if (!is_dir(FOLDER_ALLIES)) {
                        mkdir(FOLDER_ALLIES);
                    }

                    $companyFolder = FOLDER_ALLIES . safeFolderName($allie->name);
                    if (!is_dir($companyFolder)) {
                        mkdir($companyFolder);
                    }

                    // Set Image
                    $manager = new Image(Driver::class);
                    $image = $manager->read($_FILES['allie']['tmp_name']['logo'])->cover(800, 800);
                    $allie->setImage($imageName);

                    $adminIds = explode(',', $_POST['admins']);

                    foreach ($adminIds as $userId) {
                        $user = User::find($userId);
                        $companyUser = CompanyUser::where('user_id', $userId);

                        if ($companyUser) {
                            Company::setAlerts('error', $user->email . ' ' . translate('user_already_admin_allie'));
                            $alerts = Company::getAlerts();
                        }
                    }

                    if (empty($alerts)) {

                        $result = $allie->save();

                        foreach ($adminIds as $userId) {
                            $companyUser = new CompanyUser([
                                'company_id' => $result['id'],
                                'user_id' => $userId
                            ]);
                            $companyUser->save();

                            // Update user role 2
                            $user = User::find($userId);
                            if ($user && $user->role_id !== '3') { // Not SuperAdmin
                                $user->role_id = 2;
                                $user->save();
                            }
                        }

                        if (!$result) {
                            header('Location: /admin/allies?result=4');
                        }

                        header('Location: /admin/allies?result=1');
                        $image->save($companyFolder . '/' . $imageName);
                    }
                }
            }
        }


        // Render to view
        $router->render('market/allies/new-allie', [
            'title' => translate('create_allie'),
            'page' => 'new_allie',
            'isAuth' => $auth['isAuth'],
            'roleID' => $auth['roleID'],
            'alerts' => $alerts,
            'allie' => $allie,
            'users' => $users,
            'script' => '<script src="/build/js/userSearch.js"></script>'
        ]);
    }

    public static function update(Router $router)
    {
        $auth = getAuth();
        redirect($auth, ['3']);
        $id = validateOrRedirect('/admin/allies');

        // Get App
        $company = Company::find($id);

        if (!$company) {
            header('Location: /admin/allies');
            return;
        }

        $alerts = [];

        $users = User::all();

        $companyName = safeFolderName($company->name);
        $pathImage = $companyName . '/' . $company->logo;

        $assignedAdmins = CompanyUser::SQL("SELECT u.id, u.name, u.email
              FROM company_users cu
              INNER JOIN users u ON u.id = cu.user_id
              WHERE cu.company_id = $id");

        $assignedIds = array_map(fn($a) => $a->id, $assignedAdmins);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $args = $_POST;

            $company->synchronize($args);

            // validate form
            $alerts = $company->validateCompany();

            // Error admin
            if (!$_POST['admins']) {
                Company::setAlerts('error', translate('admin_required'));
                $alerts = Company::getAlerts();
            }

            // Get Company of app
            $companyName = safeFolderName($company->name);
            $folderOwner = $folderOwner = createCompanyImageFolder($companyName, 'allies');
            $pathImage = $companyName . '/' . $company->logo;

            if ($_FILES['allie']['tmp_name']['logo']) {

                // Upload image
                $imageName = md5(uniqid(rand(), true)) . ".png";
                $imageOld = $company->logo;

                $manager = new Image(Driver::class);
                $image = $manager->read($_FILES['allie']['tmp_name']['logo'])->cover(800, 800);
                $company->setImage($imageName);
            }

            $submittedAdmins = explode(',', $_POST['admins']);

            // Detect NEW admins
            $newAdmins = array_diff($submittedAdmins, $assignedIds);

            // Detect REMOVED admins
            $removedAdmins = array_diff($assignedIds, $submittedAdmins);

            // VALIDATE ONLY NEW ADMINS
            foreach ($newAdmins as $newAdminId) {
                $exists = CompanyUser::where('user_id', $newAdminId);

                if ($exists) {
                    $user = User::find($newAdminId);
                    Company::setAlerts('error', $user->email . ' ' . translate('user_already_admin_allie'));
                    $alerts = Company::getAlerts();
                }
            }

            if (empty($alerts)) {
                if ($_FILES['allie']['tmp_name']['logo']) {
                    $route = $folderOwner . $imageOld;

                    if (file_exists($route)) {
                        unlink($route);
                    }

                    // Save Image
                    $image->save($folderOwner . $imageName);
                }

                foreach ($removedAdmins as $idToRemove) {
                    $companyUser = CompanyUser::where('user_id', $idToRemove);
                    $companyUser->delete();


                    $user = User::find($idToRemove);
                    if ($user && $user->role_id !== '3') { // Not SuperAdmin
                        $user->role_id = 1;
                        $user->save();
                    }
                }

                // Save in database
                $result = $company->save();

                foreach ($newAdmins as $idToAdd) {
                    $companyUser = new CompanyUser([
                        'company_id' => $company->id,
                        'user_id' => $idToAdd
                    ]);
                    $companyUser->save();


                    $user = User::find($idToAdd);
                    if ($user && $user->role_id !== '3') { // Not SuperAdmin
                        $user->role_id = 2;
                        $user->save();
                    }
                }

                if (!$result) {
                    header('Location: /admin/allies?result=5');
                }

                header('Location: /admin/allies?result=2');
            }
        }

        // Render to view
        $router->render('market/allies/update-allies', [
            'title' => translate('update_allie'),
            'page' => 'update_allie',
            'isAuth' => $auth['isAuth'],
            'roleID' => $auth['roleID'],
            'alerts' => $alerts,
            'allie' => $company,
            'users' => $users,
            'pathImage' => $pathImage,
            'assignedAdmins' => $assignedAdmins,
            'script' => '<script src="/build/js/userSearch.js"></script>'
        ]);
    }

    public static function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if (!$id) {
                header('Location: /admin/allies');
                return;
            }

            $company = Company::find($id);

            if (!$company) {
                header('Location: /admin/allies');
                return;
            }

            $companyName = $company->name;
            $folderCompany = createCompanyImageFolder($companyName, 'allies');

            // Delete image
            $route = $folderCompany . $company->logo;

            if (file_exists($route)) {
                unlink($route);
            }

            // Delete Folder
            if (is_dir($folderCompany)) {
                rmdir($folderCompany);
            }


            $companyUsers = CompanyUser::querySQL("SELECT * FROM company_users WHERE company_id = {$id}");

            foreach ($companyUsers as $companyUser) {
                $user = User::find($companyUser->user_id);
                if ($user && $user->role_id !== '3') {
                    $user->role_id = 1;
                    $user->save();
                }
                $companyUser->delete();
            }

            $result = $company->delete();

            header('Location: /admin/allies?result=3');
        }
    }
}
