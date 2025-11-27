<?php

namespace Model;

class CompanyUser extends ActiveRecord
{
    protected static $table = 'company_users';
    protected static $columnsDB = ['id', 'company_id', 'user_id'];

    public $id;
    public $company_id;
    public $user_id;
    public $name;
    public $email;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->company_id = $args['company_id'] ?? null;
        $this->user_id = $args['user_id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->email = $args['email'] ?? '';
    }
}
