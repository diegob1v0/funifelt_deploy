<?php

namespace Model;

class ActiveRecord
{

    // BD
    protected static $db;
    protected static $table = '';
    protected static $columnsDB = [];

    // Alerts and Messages
    protected static $alerts = [];

    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database)
    {
        self::$db = $database;
    }

    public static function setAlerts($type, $message)
    {
        static::$alerts[$type][] = $message;
    }
    // Validación
    public static function getAlerts()
    {
        return static::$alerts;
    }

    public function validate()
    {
        static::$alerts = [];
        return static::$alerts;
    }

    // registers - CRUD
    public function save()
    {
        $result = '';
        if (!is_null($this->id)) {
            // update
            $result = $this->update();
        } else {
            // Creando un nuevo register
            $result = $this->create();
        }
        return $result;
    }

    public static function all()
    {
        $query = "SELECT * FROM " . static::$table;
        $result = self::querySQL($query);
        return $result;
    }

    // Busca un registro por su id
    public static function find($id)
    {
        $query = "SELECT * FROM " . static::$table  . " WHERE id = {$id}";
        $result = self::querySQL($query);
        return array_shift($result);
    }

    // Obtener Registro
    public static function get($limit)
    {
        $query = "SELECT * FROM " . static::$table . " LIMIT {$limit}";
        $result = self::querySQL($query);
        return array_shift($result);
    }

    // Busqueda Where con column 
    public static function where($column, $value)
    {
        $query = "SELECT * FROM " . static::$table . " WHERE {$column} = '{$value}'";
        $result = self::querySQL($query);
        return array_shift($result);
    }

    // SQL para Consultas Avanzadas.
    public static function SQL($lookup)
    {
        $query = $lookup;
        $result = self::querySQL($query);
        return $result;
    }

    // crea un nuevo registro
    public function create()
    {
        // Sanitizar los datos
        $attributes = $this->sanitizeAttributes();

        // Insertar en la base de datos
        $query = " INSERT INTO " . static::$table . " ( ";
        $query .= join(', ', array_keys($attributes));
        $query .= " ) VALUES ('";
        $query .= join("', '", array_values($attributes));
        $query .= "') ";

        // result de la consulta
        $result = self::$db->query($query);

        return [
            'result' =>  $result,
            'id' => self::$db->insert_id
        ];
    }


    public function update()
    {
        // Sanitizar los datos
        $attributes = $this->sanitizeAttributes();

        // Iterar para ir agregando cada campo de la BD
        $values = [];
        foreach ($attributes as $key => $value) {
            $values[] = "{$key}='{$value}'";
        }

        $query = "UPDATE " . static::$table . " SET ";
        $query .=  join(', ', $values);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 ";

        // debuguear($query);
        $result = self::$db->query($query);

        return $result;
    }

    // delete un registro - Toma el ID de Active Record
    public function delete()
    {
        $query = "DELETE FROM "  . static::$table . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $result = self::$db->query($query);

        return $result;
    }

    public static function querySQL($query)
    {
        // Consultar la base de datos
        $result = self::$db->query($query);

        // Iterar los results
        $array = [];
        while ($register = $result->fetch_assoc()) {
            $array[] = static::createObject($register);
        }

        // liberar la memoria
        $result->free();

        // retornar los results
        return $array;
    }

    protected static function createObject($register)
    {
        $object = new static;

        foreach ($register as $key => $value) {
            if (property_exists($object, $key)) {
                $object->$key = $value;
            }
        }

        return $object;
    }

    // Identificar y unir los attributes de la BD
    public function attributes()
    {
        $attributes = [];
        foreach (static::$columnsDB as $column) {
            if ($column === 'id') continue;
            $attributes[$column] = $this->$column;
        }
        return $attributes;
    }

    public function sanitizeAttributes()
    {
        $attributes = $this->attributes();
        $sanitizado = [];
        foreach ($attributes as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    public function synchronize($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }


    /**
     * Busca un único registro por su ID.
     * @param int $id ID del registro a buscar.
     * @return mixed Objeto del modelo o null si no se encuentra.
     */
    public static function findAppById(int $id)
    {
        // 1. Usa la tabla de la clase hija (ej. 'aplicaciones')
        $tabla = static::$table;

        // 2. Consulta con placeholder (?) usando Sentencias Preparadas
        $query = "SELECT * FROM " . $tabla . " WHERE id = ?";

        // 3. Preparar la sentencia
        $stmt = self::$db->prepare($query);

        // 4. Enlazar el parámetro (i = integer)
        $stmt->bind_param("i", $id);

        // 5. Ejecutar la consulta y obtener el resultado
        $stmt->execute();
        $resultado = $stmt->get_result();

        // 6. Mapear el resultado al objeto del modelo
        $registro = $resultado->fetch_assoc();
        $stmt->close();

        return $registro ? static::createObject($registro) : null;
    }

    // Helper para procesar un mysqli_result en un array de objetos
    protected static function processResult($result)
    {
        $array = [];
        while ($register = $result->fetch_assoc()) {
            $array[] = static::createObject($register);
        }

        // liberar la memoria
        $result->free();

        // retornar los results
        return $array;
    }

    /**
     * Search for records where name or description match a term.
     * @param string $term The search term.
     * @return array An array of objects.
     */
    public static function search(string $term)
    {
        $query = "SELECT * FROM " . static::$table . " WHERE name LIKE ? OR description LIKE ?";
        $stmt = self::$db->prepare($query);
        $searchTerm = "%" . $term . "%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return static::processResult($result);
    }

    public static function getApps($limit)
    {
        $query = "SELECT " . static::$table . ".*, companies.name as company_name FROM " . static::$table . " 
                  JOIN companies ON " . static::$table . ".company_id = companies.id 
                  LIMIT " . self::$db->escape_string($limit);
        $result = self::querySQL($query);
        return $result;
    }
}
