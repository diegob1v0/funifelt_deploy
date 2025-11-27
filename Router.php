<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];

    public function get($url, $fn)
    {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->postRoutes[$url] = $fn;
    }

    /**
     * Igual que antes: usar REQUEST_URI y strtok para obtener la ruta
     */
    public function checkRoutes()
    {
        // /admin/allies?foo=1  -> /admin/allies
        $currentUrl = strtok($_SERVER['REQUEST_URI'], '?') ?: '/';
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $fn = $this->getRoutes[$currentUrl] ?? null;
        } else {
            $fn = $this->postRoutes[$currentUrl] ?? null;
        }

        if ($fn) {
            // Igual que antes
            call_user_func($fn, $this);
        } else {
            echo "Página No Encontrada o Ruta no válida";
        }
    }

    /**
     * Render como el viejo, pero con soporte opcional para header del market
     */
    public function render($view, $datos = [])
    {
        // 1. Variables dinámicas desde el controlador
        foreach ($datos as $key => $value) {
            $$key = $value;
        }

        // 2. Renderizamos primero la vista para que ella pueda definir $scripts, $titulo, etc.
        ob_start();
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean();

        // 3. Header especial para vistas del market
        $headerHtml = '';
        if (strpos($view, 'market/') === 0) {
            // Capturamos el header en $headerHtml para usarlo en layout.php
            ob_start();
            include_once __DIR__ . '/views/templates/apps-header.php';
            $headerHtml = ob_get_clean();

            // Aseguramos que $scripts exista como array
            if (!isset($scripts) || !is_array($scripts)) {
                $scripts = [];
            }

            // Solo agregamos header.js si NO está ya en la lista
            if (!in_array('/build/js/header.js', $scripts, true)) {
                $scripts[] = '/build/js/header.js';
            }
        }

        // 4. Cargamos el layout con $contenido, $headerHtml, $scripts, etc.
        include_once __DIR__ . '/views/layout.php';
    }
}
