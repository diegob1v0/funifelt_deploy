<?php

namespace Controllers;

use Model\User;
use MVC\Router;

class UserController
{
    public static function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if ($id) {
                $user = User::find($id);

                if (!$user) {
                    header('Location: /admin/users');
                    return;
                }

                $user->delete();

                header('Location: /admin/users?result=3');
            }
        }
    }
}
