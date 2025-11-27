<?php

namespace Model;

class AppCompany extends ActiveRecord
{
    protected static $table = 'applications';
    protected static $columnsDB = ['id', 'app_name', 'description', 'version', 'price', 'size_mb', 'image', 'download_url', 'upload_date', 'company_name', 'category_name'];
    public $id;
    public $app_name;
    public $description;
    public $version;
    public $price;
    public $size_mb;
    public $image;
    public $download_url;
    public $upload_date;
    public $company_name;
    public $category_name;
    public $pathImage;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->app_name = $args['app_name'] ?? '';
        $this->description = $args['description'] ?? '';
        $this->version = $args['version'] ?? '';
        $this->price = $args['price'] ?? 0.00;
        $this->size_mb = $args['size_mb'] ?? '';
        $this->image = $args['image'] ?? '';
        $this->download_url = $args['download_url'] ?? '';
        $this->upload_date = $args['upload_date'] ?? '';
        $this->company_name = $args['company_name'] ?? '';
        $this->category_name = $args['category_name'] ?? '';
        $this->pathImage = $args['pathImage'] ?? '';
    }
}
