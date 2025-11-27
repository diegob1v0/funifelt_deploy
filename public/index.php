<?php

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\LoginController;
use Controllers\MarketController;
use Controllers\AppController;
use Controllers\APIController;
use Controllers\CompanyController;
use Controllers\PayController;
use Controllers\UserController;

$router = new Router();

// --- ZONA DE LOGIN Y AUTENTICACIÓN ---
$router->get('/login', [LoginController::class, 'login']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

// Crear Cuenta
$router->get('/create', [LoginController::class, 'create']);
$router->post('/create', [LoginController::class, 'create']);

// Resetear Password
$router->get('/forget', [LoginController::class, 'forget']);
$router->post('/forget', [LoginController::class, 'forget']);
$router->get('/reset', [LoginController::class, 'reset']);
$router->post('/reset', [LoginController::class, 'reset']);

// Confirmar Cuenta
$router->get('/message', [LoginController::class, 'message']);
$router->get('/confirm', [LoginController::class, 'confirm']);


// --- ZONA DEL MARKETPLACE (VISTAS PÚBLICAS) ---
$router->get('/', [MarketController::class, 'index']); // Redirige a /apps
$router->get('/app_detail', [MarketController::class, 'app_detail']); // Vista de detalle de una app
$router->get('/allies', [MarketController::class, 'allies']);
$router->get('/ayuda', [MarketController::class, 'ayuda']);
$router->get('/profile', [MarketController::class, 'profile']);


// --- ZONA DE ADMINISTRACIÓN (VISTAS PRIVADAS) ---
$router->get('/admin/apps', [MarketController::class, 'admin_apps']);
$router->get('/admin/allies', [MarketController::class, 'admin_allies']);
// --- Cosas para el dashboard ---
$router->get('/admin/dashboard', [MarketController::class, 'admin_dashboard']); 

// CRUD de Aplicaciones
$router->get('/admin/apps/new/app', [AppController::class, 'create']);
$router->post('/admin/apps/new/app', [AppController::class, 'create']);
$router->get('/admin/apps/update/app', [AppController::class, 'update']);
$router->post('/admin/apps/update/app', [AppController::class, 'update']);
$router->post('/admin/apps/delete/app', [AppController::class, 'delete']);

// CRUD de Aliadios
$router->get('/admin/allies/new/allie', [CompanyController::class, 'create']);
$router->post('/admin/allies/new/allie', [CompanyController::class, 'create']);
$router->get('/admin/allies/update/allie', [CompanyController::class, 'update']);
$router->post('/admin/allies/update/allie', [CompanyController::class, 'update']);
$router->post('/admin/allies/delete/allie', [CompanyController::class, 'delete']);

// Users Managment
$router->get('/admin/users', [MarketController::class, 'users']);
$router->post('/admin/users/delete/user', [UserController::class, 'delete']);

// --- ZONA DE API (PARA COMUNICACIÓN CON JAVASCRIPT) ---
$router->get('/api/apps', [APIController::class, 'index']); // Búsqueda de apps
$router->get('/api/users/search', [APIController::class, 'searchUsers']);
$router->get('/api/aplicacion/detalle', [APIController::class, 'detalle']); // Detalle de una app
$router->get('/api/empresas/listar', [APIController::class, 'list_companies']); // Listar empresas
// API Pay
$router->post('/api/pays', [PayController::class, 'pay']);
// 5. NUEVA RUTA PARA EL DASHBOARD 
$router->get('/api/dashboard', [APIController::class, 'dashboard']);


// Comprueba y valida las rutas
$router->checkRoutes();
