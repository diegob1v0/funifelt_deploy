<?php

namespace Model;

class User extends ActiveRecord
{
    protected static $table = 'users';
    protected static $columnsDB = ['id', 'name', 'email', 'password', 'token', 'confirmed', 'role_id'];
    public $id;
    public $name;
    public $email;
    public $password;
    public $password2;
    public $token;
    public $confirmed;
    public $role_id;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmed = $args['confirmed'] ?? 0;
        $this->role_id = $args['role_id'] ?? 1;
    }

    // Validate Login
    public function validateLogin()
    {
        if (!$this->email) {
            self::$alerts['error'][] = translate('email_error');
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL) && $this->email) {
            self::$alerts['error'][] = translate('email_error_validate');
        }

        if (!$this->password) {
            self::$alerts['error'][] = translate('password_error');
        }

        return self::$alerts;
    }

    // Validations for account creation
    public function validateNewAccount()
    {
        if (!$this->name) {
            self::$alerts['error'][] = translate('name_error');
        }

        if (!$this->email) {
            self::$alerts['error'][] = translate('email_error');
        }

        if (!$this->password) {
            self::$alerts['error'][] = translate('password_error');
        }

        if ($this->password && strlen($this->password) < 6) {
            self::$alerts['error'][] = translate('password_error_length');
        }

        if ($this->password !== $this->password2) {
            self::$alerts['error'][''] = translate('password_error_same');
        }

        return self::$alerts;
    }

    // Validate Email

    public function validateEmail()
    {
        if (!$this->email) {
            self::$alerts['error'][] = translate('email_error');
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL) && $this->email) {
            self::$alerts['error'][] = translate('email_error_validate');
        }

        return self::$alerts;
    }

    // Validate Password

    public function validatePassword()
    {

        if (!$this->password) {
            self::$alerts['error'][] = translate('password_error');
        }

        if ($this->password && strlen($this->password) < 6) {
            self::$alerts['error'][] = translate('password_error_length');
        }
        return self::$alerts;
    }

    // Hash the password
    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Generete a token
    public function createToken()
    {
        $this->token = md5(uniqid());
    }
}
