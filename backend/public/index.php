<?php

/**
 * API Backend - Sistema de Gestión de Citas Médicas
 * Entry point de la aplicación
 */

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Tuupola\Middleware\CorsMiddleware;
use App\Config\Database;
use App\Middleware\AuthMiddleware;

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Inicializar conexión a base de datos
Database::init();

// Crear aplicación Slim
$app = AppFactory::create();

// Middleware para parsear JSON
$app->addBodyParsingMiddleware();

// Middleware de errores
$errorMiddleware = $app->addErrorMiddleware(
    $_ENV['APP_DEBUG'] === 'true',
    true,
    true
);

// Middleware CORS
$app->add(new CorsMiddleware([
    "origin" => explode(',', $_ENV['CORS_ALLOWED_ORIGINS']),
    "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE", "OPTIONS"],
    "headers.allow" => ["Authorization", "Content-Type", "Accept"],
    "headers.expose" => ["Authorization"],
    "credentials" => true,
    "cache" => 86400
]));

// ============================================
// RUTAS PÚBLICAS (Sin autenticación)
// ============================================

// Health check
$app->get('/api/health', function ($request, $response) {
    $response->getBody()->write(json_encode([
        'status' => 'ok',
        'message' => 'API funcionando correctamente',
        'timestamp' => date('Y-m-d H:i:s')
    ]));
    return $response->withHeader('Content-Type', 'application/json');
});

// Autenticación
$app->post('/api/auth/login', '\App\Controllers\AuthController:login');
$app->post('/api/auth/register', '\App\Controllers\AuthController:register');

// Terapias (público)
$app->get('/api/terapias', '\App\Controllers\TerapiaController:index');
$app->get('/api/terapias/{id}', '\App\Controllers\TerapiaController:show');

// Médicos (público)
$app->get('/api/medicos', '\App\Controllers\MedicoController:index');
$app->get('/api/medicos/{id}', '\App\Controllers\MedicoController:show');
$app->get('/api/medicos/especialidad/{especialidad}', '\App\Controllers\MedicoController:byEspecialidad');

// ============================================
// RUTAS PROTEGIDAS (Requieren autenticación)
// ============================================

$app->group('/api', function ($group) {
    
    // Usuario actual
    $group->get('/auth/me', '\App\Controllers\AuthController:me');
    $group->put('/auth/profile', '\App\Controllers\AuthController:updateProfile');
    
    // Citas
    $group->get('/citas/paciente/{pacienteId}', '\App\Controllers\CitaController:byPaciente');
    $group->get('/citas/proximas/{pacienteId}', '\App\Controllers\CitaController:proximasCitas');
    $group->get('/citas/{id}', '\App\Controllers\CitaController:show');
    $group->post('/citas', '\App\Controllers\CitaController:create');
    $group->put('/citas/{id}/cancelar', '\App\Controllers\CitaController:cancelar');
    
    // Horarios disponibles
    $group->get('/horarios/disponibles', '\App\Controllers\HorarioController:disponibles');
    
})->add(new AuthMiddleware());

// Ejecutar aplicación
$app->run();
