<?php

namespace Model;

class App extends ActiveRecord
{
    protected static $table = 'applications';
    protected static $columnsDB = ['id', 'name', 'image', 'description', 'version', 'price', 'size_mb', 'download_url', 'upload_date', 'company_id', 'category_id'];
    public $id;
    public $name;
    public $image;
    public $description;
    public $version;
    public $price;
    public $size_mb;
    public $download_url;
    public $upload_date;
    public $company_id;
    public $category_id;


    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->image = $args['image'] ?? '';
        $this->description = $args['description'] ?? '';
        $this->version = $args['version'] ?? '';
        $this->price = $args['price'] ?? 0.00;
        $this->size_mb = $args['size_mb'] ?? '';
        $this->download_url = $args['download_url'] ?? 'prueba.apk';
        $this->upload_date = $args['upload_date'] ?? date('Y-m-d');
        $this->company_id = $args['company_id'] ?? '';
        $this->category_id = $args['category_id'] ?? '';
    }


    public function validateApp($type)
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

        if (!$this->version) {
            self::$alerts['error'][] = translate('version_required');
        }

        if (!$this->size_mb) {
            self::$alerts['error'][] = translate('size_required');
        }

        if (!$type) {
            self::$alerts['error'][] = translate('type_required');
        }
        if (!$this->price && $type === 'pay') {
            self::$alerts['error'][] = translate('price_required');
        }

        return self::$alerts;
    }

    public function setImage($image)
    {
        if ($image) {
            $this->image = $image;
        }
    }

    public function setAPK($download_url)
    {
        if ($download_url) {
            $this->download_url = $download_url;
        }
    }

    /**
     * Busca registros donde el nombre o la descripción coincidan con un término.
     * @param string $term El término de búsqueda.
     * @return array Un arreglo de objetos.
     */
    public static function search(string $term)
    {
        $query = "SELECT * FROM " . static::$table . " WHERE name LIKE ? OR description LIKE ?";
        $stmt = self::$db->prepare($query);
        $searchTerm = "%" . $term . "%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $array = [];
        while ($register = $result->fetch_assoc()) {
            $array[] = static::createObject($register);
        }
        $stmt->close();
        return $array;
    }

    public static function findAppById(int $id) {
        return static::find($id);
    }
}
