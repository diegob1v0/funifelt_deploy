<?php

namespace Model;

class AppComCat extends ActiveRecord
{
    protected static $table = 'companies';
    protected static $columnsDB = [
        'company_id',
        'company_name',
        'company_description',
        'app_id',
        'app_name',
        'app_description',
        'price',
        'version',
        'size_mb',
        'image',
        'download_url',
        'upload_date',
        'category_name'
    ];
    public $company_id;
    public $company_name;
    public $company_description;
    public $app_id;
    public $app_name;
    public $app_description;
    public $price;
    public $version;
    public $size_mb;
    public $image;
    public $download_url;
    public $upload_date;
    public $category_name;

    public function __construct($args = [])
    {
        $this->company_id = $args['company_id'] ?? null;
        $this->company_name = $args['company_name'] ?? '';
        $this->company_description = $args['company_description'] ?? '';
        $this->app_id = $args['app_id'] ?? '';
        $this->app_name = $args['app_name'] ?? '';
        $this->app_description = $args['app_description'] ?? '';
        $this->price = $args['price'] ?? 0.00;
        $this->version = $args['version'] ?? '';
        $this->size_mb = $args['size_mb'] ?? '';
        $this->image = $args['image'] ?? '';
        $this->download_url = $args['download_url'] ?? '';
        $this->upload_date = $args['upload_date'] ?? '';
        $this->category_name = $args['category_name'] ?? '';
    }
}
