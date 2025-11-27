<?php

namespace Controllers;

use Model\App;
use Model\AppComCat;
use Model\Company;
use Model\User;

class APIController
{
    public static function index()
    {
        $apps = AppComCat::SQL('CALL SP_Get_Applications_With_Companies_and_Categories()');

        $companies = [];

        foreach ($apps as $row) {

            if ($row->app_id === null)
                continue;

            $companyId = $row->company_id;

            if (!isset($companies[$companyId])) {
                $companies[$companyId] = [
                    'company_id' => $row->company_id,
                    'company_name' => $row->company_name,
                    'company_description' => $row->company_description ?? '',
                    'applications' => []
                ];
            }

            $companies[$companyId]['applications'][] = [
                'app_id' => $row->app_id,
                'app_name' => $row->app_name,
                'app_description' => $row->app_description,
                'price' => $row->price,
                'version' => $row->version,
                'size_mb' => $row->size_mb,
                'image' => $row->image,
                'download_url' => $row->download_url,
                'upload_date' => $row->upload_date,
                'category_name' => $row->category_name
            ];
        }

        echo json_encode(array_values($companies));
    }

    public static function searchUsers()
    {
        $query = $_GET['query'] ?? '';

        if (strlen($query) < 2) {
            echo json_encode([]);
            return;
        }

        $users = User::SQL("SELECT id, name, email FROM users WHERE name LIKE '%$query%' OR email LIKE '%$query%' LIMIT 10;");
        echo json_encode($users);
    }


    public static function detalle()
    {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            self::jsonResponse(['error' => 'ID de aplicación no válido.'], 400);
            return;
        }

        // Usamos el método que ya existe en el modelo App para buscar por ID
        $aplicacion = App::where('id', $id);

        if (!$aplicacion) {
            self::jsonResponse(['error' => 'Aplicación no encontrada.'], 404);
            return;
        }

        self::jsonResponse($aplicacion, 200);
    }

    public static function list_companies()
    {
        $empresas = Company::all();
        $respuesta = [];
        foreach ($empresas as $empresa) {
            $respuesta[] = [
                'id' => $empresa->id,
                'name' => $empresa->name
            ];
        }
        echo json_encode($respuesta);
    }

    private static function jsonResponse($data, $status)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    // --------------------------------------------------------------
    // NUEVO MÉTODO DASHBOARD (CON SEGURIDAD POR ROLES)
    // --------------------------------------------------------------
    public static function dashboard()
    {
        session_start();
        if (!isset($_SESSION['login']) || !$_SESSION['login']) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
            return;
        }

        $role = $_SESSION['roleID'];
        $userCompanyId = $_SESSION['company_id'] ?? null;
        global $db;

        // 1. FILTRO EMPRESA (SuperAdmin)
        $filterCompanyId = null;
        if ($role == 3 && isset($_GET['company_id']) && !empty($_GET['company_id'])) {
            $filterCompanyId = intval($_GET['company_id']);
        } elseif ($role == 2) {
            $filterCompanyId = $userCompanyId;
        }

        // 2. FILTROS TABLA (Fechas y Límite)
        // Recibimos los parametros de la URL
        $limitParam = $_GET['limit'] ?? '10';
        $dateStart = $_GET['date_start'] ?? null;
        $dateEnd = $_GET['date_end'] ?? null;

        // Construimos el WHERE para la tabla
        $whereClause = "1=1"; // Base true
        if ($filterCompanyId) {
            $whereClause .= " AND a.company_id = $filterCompanyId";
        }
        if ($dateStart) {
            $whereClause .= " AND DATE(t.created_at) >= '$dateStart'";
        }
        if ($dateEnd) {
            $whereClause .= " AND DATE(t.created_at) <= '$dateEnd'";
        }

        // Construimos el LIMIT
        $limitClause = "LIMIT 10"; // Default
        if ($limitParam === 'all') {
            $limitClause = "LIMIT 1000"; // Tope de seguridad
        } else {
            $limitInt = intval($limitParam);
            $limitClause = "LIMIT $limitInt";
        }

        // 3. ETL (Solo SuperAdmin Global actualiza)
        if ($db && $role == 3 && !$filterCompanyId) {
            $db->query("CALL SP_Refresh_Dashboard_Summaries()");
            while ($db->more_results() && $db->next_result()) {
                if ($result = $db->store_result()) {
                    $result->free();
                }
            }
        }

        $response = [
            'status' => 'success',
            'role_detected' => $role,
            'view_mode' => $filterCompanyId ? 'company_specific' : 'global',
            'data' => []
        ];

        $fetchData = function ($query) use ($db) {
            $result = $db->query($query);
            return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        };
        $fetchOne = function ($query) use ($db) {
            $result = $db->query($query);
            return $result ? $result->fetch_assoc() : null;
        };

        // --- CONSULTAS ---

        // Consulta Dinámica de Transacciones (Aquí estaba el fallo, ahora es dinámica)
        $sqlTransactions = "
            SELECT t.id, u.email as user_email, a.name as app_name, t.amount, t.created_at, c.name as company_name
            FROM transactions t 
            JOIN users u ON t.user_id = u.id 
            JOIN applications a ON t.app_id = a.id 
            JOIN companies c ON a.company_id = c.id
            WHERE $whereClause
            ORDER BY t.created_at DESC
            $limitClause
        ";
        $response['data']['latest_transactions'] = $fetchData($sqlTransactions);

        if ($filterCompanyId) {
            // EMPRESA ESPECÍFICA
            $response['data']['sales_daily'] = $fetchData("SELECT DATE(t.created_at) as report_date, SUM(t.amount) as total_revenue FROM transactions t JOIN applications a ON t.app_id = a.id WHERE a.company_id = $filterCompanyId GROUP BY DATE(t.created_at) ORDER BY report_date ASC LIMIT 30");
            $response['data']['top_apps'] = $fetchData("SELECT a.name as app_name, COUNT(t.id) as total_sold FROM transactions t JOIN applications a ON t.app_id = a.id WHERE a.company_id = $filterCompanyId GROUP BY a.id, a.name ORDER BY total_sold DESC LIMIT 5");
            $response['data']['sales_monthly'] = $fetchData("SELECT DATE_FORMAT(t.created_at, '%Y-%m') as periodo, SUM(t.amount) as total_revenue FROM transactions t JOIN applications a ON t.app_id = a.id WHERE a.company_id = $filterCompanyId GROUP BY periodo ORDER BY periodo DESC LIMIT 12");
            $response['data']['sales_category'] = $fetchData("SELECT category_name, SUM(total_revenue) as total_revenue FROM summary_sales_by_category WHERE company_id = $filterCompanyId GROUP BY category_name ORDER BY total_revenue DESC");
            $response['data']['kpis'] = $fetchOne("SELECT 0 as total_users, (SELECT COUNT(*) FROM applications WHERE company_id = $filterCompanyId) as total_apps, (SELECT IFNULL(SUM(amount), 0) FROM transactions t JOIN applications a ON t.app_id = a.id WHERE a.company_id = $filterCompanyId) as total_revenue_historic");
        } else {
            // GLOBAL
            $response['data']['sales_daily'] = $fetchData("SELECT report_date, total_revenue FROM summary_sales_daily ORDER BY report_date ASC LIMIT 30");

            // Multilínea
            $response['data']['sales_daily_breakdown'] = $fetchData("SELECT DATE(t.created_at) as report_date, c.name as company_name, SUM(t.amount) as total_revenue FROM transactions t JOIN applications a ON t.app_id = a.id JOIN companies c ON a.company_id = c.id WHERE t.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY DATE(t.created_at), c.name ORDER BY report_date ASC");

            $response['data']['sales_by_company'] = $fetchData("SELECT company_name, total_revenue FROM summary_sales_by_company ORDER BY total_revenue DESC LIMIT 10");

            // MODIFICADO: Agregamos 'price' para saber si es Gratis o Pago
            $response['data']['top_apps'] = $fetchData("SELECT a.name as app_name, a.price, total_sold FROM summary_top_apps s JOIN applications a ON s.app_id = a.id ORDER BY total_sold DESC LIMIT 5");

            // MODIFICADO: Desglose mensual por empresa (Para la gráfica Apilada)
            $response['data']['sales_monthly_breakdown'] = $fetchData("
                SELECT CONCAT(YEAR(t.created_at), '-', LPAD(MONTH(t.created_at), 2, '0')) as periodo, 
                       c.name as company_name, 
                       SUM(t.amount) as total_revenue
                FROM transactions t
                JOIN applications a ON t.app_id = a.id
                JOIN companies c ON a.company_id = c.id
                GROUP BY periodo, c.name
                ORDER BY periodo DESC, c.name
            ");

            $response['data']['sales_category'] = $fetchData("SELECT category_name, SUM(total_revenue) as total_revenue FROM summary_sales_by_category GROUP BY category_name ORDER BY total_revenue DESC");
            $response['data']['kpis'] = $fetchOne("SELECT (SELECT COUNT(*) FROM users) as total_users, (SELECT COUNT(*) FROM applications) as total_apps, (SELECT IFNULL(SUM(total_revenue), 0) FROM summary_sales_daily) as total_revenue_historic");
        }

        echo json_encode($response);
    }
}
