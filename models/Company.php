<?php

namespace Model;

class Company extends ActiveRecord
{
    protected static $table = 'companies';
    protected static $columnsDB = ['id', 'name', 'description', 'logo'];
    public $id;
    public $name;
    public $description;
    public $logo;
    public $admin_id;
    public $admin_emails;
    public $pathImage;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->description = $args['description'] ?? '';
        $this->logo = $args['logo'] ?? '';
        $this->admin_emails = $args['admin_emails'] ?? '';
        $this->pathImage = $args['pathImage'] ?? '';
    }

    public function validateCompany()
    {
        if (!$this->name) {
            self::$alerts['error'][] = translate('name_error');
        }

        if (!$this->description) {

            self::$alerts['error'][] = translate('descripcion_required');
        }

        if ($this->description && strlen($this->description) < 50) {
            self::$alerts['error'][] = translate('descripcion_lower_50');
        }

        if (strlen($this->description) > 500) {
            self::$alerts['error'][] = translate('descripcion_most_500');
        }

        return self::$alerts;
    }


    public function setImage($logo)
    {
        if ($logo) {
            $this->logo = $logo;
        }
    }
}
