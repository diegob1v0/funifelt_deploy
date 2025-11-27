<?php

namespace Controllers;

use Classes\Email;
use Model\User;
use Model\CompanyUser;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {

        $alerts = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new User($_POST);
            $alerts = $auth->validateLogin();

            if (empty($alerts)) {
                // Verify if user exists

                $user = User::where('email', $auth->email);

                if (!$user || !$user->confirmed) {
                    User::setAlerts('error', translate('user_not_found'));
                } else {
                    if ($user->role_id != 1) {
                        $companyUser = CompanyUser::where('user_id', $user->id);
                    }

                    // User exists
                    if (password_verify($_POST['password'], $user->password)) {
                        session_start();
                        $_SESSION['id'] = $user->id;
                        $_SESSION['name'] = $user->name;
                        $_SESSION['email'] = $user->email;
                        $_SESSION['login'] = true;
                        $_SESSION['roleID'] = $user->role_id;

                        if (isset($companyUser)) {
                            $_SESSION['company_id'] = $companyUser->company_id;
                        } else {
                            $_SESSION['company_id'] = null;
                        }

                        // Redirección basada en el rol del usuario
                        if ($user->role_id === '3' || $user->role_id === '2') { // SuperAdmin o Admin
                            header('Location: /admin/apps');
                        } else { // Usuario normal
                            header('Location: /');
                        }
                        exit; // Es una buena práctica añadir exit después de una redirección
                    } else {
                        User::setAlerts('error', translate('user_password_error'));
                    }
                }
            }
        }

        $alerts = User::getAlerts();
        // Render to view
        $router->render('auth/login', [
            'title' => 'Sign In',
            'page' => 'login',
            'body' => 'body-login',
            'alerts' => $alerts,
            'page' => 'login'
            ]);
    }

    public static function logout()
    {
        session_start();
        $_SESSION = [];
        header('Location: /login');
    }

    public static function create(Router $router)
    {
        $alerts = [];
        $user = new User;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user->synchronize($_POST);

            $alerts = $user->validateNewAccount();

            if (empty($alerts)) {
                $userExists = User::where('email', $user->email);

                if ($userExists) {
                    User::setAlerts('error', translate('user_exists'));
                    $alerts = User::getAlerts();
                } else {

                    // hash the passsword
                    $user->hashPassword();

                    // Delete password 2
                    unset($user->password2);

                    // Generete a token
                    $user->createToken();

                    // create a new user
                    $result = $user->save();

                    // Sent confirmation email
                    $email = new Email($user->email, $user->name, $user->token);
                    $email->sentConfirmation();

                    if ($result) {
                        header('Location: /message');
                    }
                }
            }
        }

        // Render to view
        $router->render('auth/create', [
            'title' => 'Create Account',
            'page' => 'new-account',
            'body' => 'body-login',
            'user' => $user,
            'alerts' => $alerts
        ]);
    }

    public static function forget(Router $router)
    {

        $alerts = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $user = new User($_POST);
            $alerts = $user->validateEmail();

            if (empty($alerts)) {

                // Searh user
                $user = User::where('email', $user->email);

                if ($user && $user->confirmed === '1') {

                    // Generate token
                    $user->createToken();

                    // Update User
                    $user->save();

                    // Send Email
                    $email = new Email($user->email, $user->name, $user->token);
                    $email->sentInsructions();

                    // Print Alert
                    User::setAlerts('success', translate('reset_email_send'));
                } else {
                    User::setAlerts('error', translate('user_not_found'));
                }
            }
        }
        $alerts = User::getAlerts();
        // Render to view 
        $router->render('auth/forget', [
            'title' => 'Forget Password',
            'page' => 'forget',
            'body' => 'body-login',
            'alerts' => $alerts
        ]);
    }

    public static function reset(Router $router)
    {
        $token = s($_GET['token']);
        $show = true;
        $alerts = [];

        if (!$token) {
            header('Location: /');
        }

        $user = User::where('token', $token);

        if (empty($user)) {
            User::setAlerts('error', translate('token_invalid'));
            $show = false;
        }

        $alerts = User::getAlerts();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Add new pass
            $user->synchronize($_POST);

            // Validate Password

            $alerts = $user->validatePassword();

            if (empty($alerts)) {

                // Hash password
                $user->hashPassword();
                unset($user->password2);

                // Delete Token
                $user->token = '';

                // Save user in BD
                $result = $user->save();

                if ($result) {
                    header('Location: /login');
                }


                debuguear($user);
            }
        }

        // Render to view
        $router->render('auth/reset', [
            'title' => 'Reset Password',
            'body' => 'body-login',
            'page' => 'reset',
            'alerts' => $alerts,
            'show' => $show
        ]);
    }

    public static function message(Router $router)
    {
        // Render to view
        $router->render('auth/message', [
            'title' => 'Message sent',
            'page' => 'message',
            'body' => 'body-login'
        ]);
    }

    public static function confirm(Router $router)
    {

        $token = s($_GET['token']);

        if (!$token) {
            header('Location: /');
        }

        // Find user whit token
        $user = User::where('token', $token);

        if (empty($user)) {
            // User not find

            User::setAlerts('error', translate('token_invalid'));
        } else {

            // Confirm account
            $user->confirmed = 1;
            $user->token = '';
            unset($user->password2);

            // Save in the BD
            $user->save();

            User::setAlerts('success', translate('account_approve'));
        }

        $alerts = User::getAlerts();

        // Render to view
        $router->render('auth/confirm', [
            'title' => 'Confirm account in Funifelt Market',
            'page' => 'confirm',
            'body' => 'body-login',
            'alerts' => $alerts
        ]);
    }
}
