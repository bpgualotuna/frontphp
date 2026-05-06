<?php

/**
 * Script de migración para crear todas las tablas en Supabase PostgreSQL
 * Ejecutar: php migrations/create_tables.php
 */

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Configurar conexión
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => $_ENV['DB_CONNECTION'],
    'host'      => $_ENV['DB_HOST'],
    'port'      => $_ENV['DB_PORT'],
    'database'  => $_ENV['DB_DATABASE'],
    'username'  => $_ENV['DB_USERNAME'],
    'password'  => $_ENV['DB_PASSWORD'],
    'charset'   => 'utf8',
    'prefix'    => '',
    'schema'    => 'public',
    'sslmode'   => 'prefer',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$schema = Capsule::schema();

echo "🚀 Iniciando migración de base de datos...\n\n";

// ============================================
// TABLA: users
// ============================================
echo "📋 Creando tabla 'users'...\n";

if ($schema->hasTable('users')) {
    echo "⚠️  La tabla 'users' ya existe. Eliminando...\n";
    $schema->drop('users');
}

$schema->create('users', function ($table) {
    $table->id();
    $table->string('cedula', 20)->unique();
    $table->string('username', 100)->unique();
    $table->string('password');
    $table->string('full_name');
    $table->enum('role', ['paciente', 'medico', 'admin'])->default('paciente');
    $table->string('email')->nullable();
    $table->string('direccion')->nullable();
    $table->integer('edad')->nullable();
    $table->enum('sexo', ['masculino', 'femenino', 'otro'])->nullable();
    $table->boolean('tiene_seguro')->default(false);
    $table->string('telefono', 20)->nullable();
    $table->string('avatar')->nullable();
    $table->string('foto_perfil')->nullable();
    
    // Campos específicos para médicos
    $table->string('especialidad')->nullable();
    $table->string('numero_licencia')->nullable();
    $table->decimal('calificacion', 3, 2)->nullable();
    $table->integer('pacientes_atendidos')->default(0);
    
    $table->timestamps();
    
    $table->index('cedula');
    $table->index('role');
});

echo "✅ Tabla 'users' creada exitosamente\n\n";

// ============================================
// TABLA: terapias
// ============================================
echo "📋 Creando tabla 'terapias'...\n";

if ($schema->hasTable('terapias')) {
    echo "⚠️  La tabla 'terapias' ya existe. Eliminando...\n";
    $schema->drop('terapias');
}

$schema->create('terapias', function ($table) {
    $table->id();
    $table->string('nombre');
    $table->text('descripcion');
    $table->integer('duracion'); // en minutos
    $table->decimal('precio', 10, 2);
    $table->string('imagen')->nullable();
    $table->string('especialidad');
    $table->boolean('activa')->default(true);
    $table->timestamps();
    
    $table->index('activa');
    $table->index('especialidad');
});

echo "✅ Tabla 'terapias' creada exitosamente\n\n";

// ============================================
// TABLA: citas
// ============================================
echo "📋 Creando tabla 'citas'...\n";

if ($schema->hasTable('citas')) {
    echo "⚠️  La tabla 'citas' ya existe. Eliminando...\n";
    $schema->drop('citas');
}

$schema->create('citas', function ($table) {
    $table->id();
    $table->foreignId('paciente_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('medico_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('terapia_id')->constrained('terapias')->onDelete('cascade');
    $table->date('fecha');
    $table->time('hora');
    $table->enum('estado', ['pendiente', 'confirmada', 'completada', 'cancelada'])->default('pendiente');
    $table->text('sintomas');
    $table->boolean('tiene_examenes')->default(false);
    $table->json('examenes')->nullable();
    $table->text('notas')->nullable();
    $table->text('motivo_cancelacion')->nullable();
    $table->timestamps();
    
    $table->index('paciente_id');
    $table->index('medico_id');
    $table->index('fecha');
    $table->index('estado');
    $table->index(['medico_id', 'fecha', 'hora']);
});

echo "✅ Tabla 'citas' creada exitosamente\n\n";

// ============================================
// TABLA: horarios_atencion
// ============================================
echo "📋 Creando tabla 'horarios_atencion'...\n";

if ($schema->hasTable('horarios_atencion')) {
    echo "⚠️  La tabla 'horarios_atencion' ya existe. Eliminando...\n";
    $schema->drop('horarios_atencion');
}

$schema->create('horarios_atencion', function ($table) {
    $table->id();
    $table->foreignId('medico_id')->constrained('users')->onDelete('cascade');
    $table->integer('dia_semana'); // 0 = Domingo, 6 = Sábado
    $table->time('hora_inicio');
    $table->time('hora_fin');
    $table->timestamps();
    
    $table->index('medico_id');
    $table->index('dia_semana');
});

echo "✅ Tabla 'horarios_atencion' creada exitosamente\n\n";

// ============================================
// INSERTAR DATOS DE PRUEBA
// ============================================
echo "📦 Insertando datos de prueba...\n\n";

// Usuarios
echo "👤 Insertando usuarios...\n";

Capsule::table('users')->insert([
    // Paciente
    [
        'cedula' => '1234567890',
        'username' => '1234567890',
        'password' => password_hash('password123', PASSWORD_BCRYPT),
        'full_name' => 'Juan Pérez García',
        'role' => 'paciente',
        'direccion' => 'Av. Principal 123, Quito',
        'edad' => 35,
        'sexo' => 'masculino',
        'tiene_seguro' => true,
        'telefono' => '0999888777',
        'email' => 'juan.perez@email.com',
        'created_at' => now(),
        'updated_at' => now()
    ],
    // Médico 1
    [
        'cedula' => '0987654321',
        'username' => '0987654321',
        'password' => password_hash('medico123', PASSWORD_BCRYPT),
        'full_name' => 'Dr. Carlos Mendoza',
        'role' => 'medico',
        'especialidad' => 'Fisioterapia',
        'numero_licencia' => 'MED-2024-001',
        'tiene_seguro' => true,
        'telefono' => '0999123456',
        'email' => 'carlos.mendoza@clinica.com',
        'calificacion' => 4.8,
        'pacientes_atendidos' => 150,
        'created_at' => now(),
        'updated_at' => now()
    ],
    // Médico 2
    [
        'cedula' => '1122334455',
        'username' => '1122334455',
        'password' => password_hash('medico123', PASSWORD_BCRYPT),
        'full_name' => 'Dra. María González',
        'role' => 'medico',
        'especialidad' => 'Terapia Ocupacional',
        'numero_licencia' => 'MED-2024-002',
        'tiene_seguro' => true,
        'telefono' => '0998765432',
        'email' => 'maria.gonzalez@clinica.com',
        'calificacion' => 4.9,
        'pacientes_atendidos' => 200,
        'created_at' => now(),
        'updated_at' => now()
    ],
    // Médico 3
    [
        'cedula' => '5566778899',
        'username' => '5566778899',
        'password' => password_hash('medico123', PASSWORD_BCRYPT),
        'full_name' => 'Dr. Roberto Silva',
        'role' => 'medico',
        'especialidad' => 'Psicología',
        'numero_licencia' => 'MED-2024-003',
        'tiene_seguro' => true,
        'telefono' => '0991234567',
        'email' => 'roberto.silva@clinica.com',
        'calificacion' => 4.7,
        'pacientes_atendidos' => 180,
        'created_at' => now(),
        'updated_at' => now()
    ],
    // Admin
    [
        'cedula' => 'admin',
        'username' => 'admin',
        'password' => password_hash('admin123', PASSWORD_BCRYPT),
        'full_name' => 'Administrador del Sistema',
        'role' => 'admin',
        'tiene_seguro' => true,
        'telefono' => '0999000000',
        'email' => 'admin@clinica.com',
        'created_at' => now(),
        'updated_at' => now()
    ]
]);

echo "✅ Usuarios insertados\n\n";

// Terapias
echo "💊 Insertando terapias...\n";

Capsule::table('terapias')->insert([
    [
        'nombre' => 'Fisioterapia General',
        'descripcion' => 'Tratamiento para lesiones musculares, rehabilitación postoperatoria y mejora de movilidad.',
        'duracion' => 60,
        'precio' => 45.00,
        'imagen' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=400',
        'especialidad' => 'Fisioterapia',
        'activa' => true,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'nombre' => 'Terapia Ocupacional',
        'descripcion' => 'Ayuda a personas a desarrollar, recuperar o mantener las habilidades necesarias para la vida diaria.',
        'duracion' => 60,
        'precio' => 50.00,
        'imagen' => 'https://images.unsplash.com/photo-1559757175-5700dde675bc?w=400',
        'especialidad' => 'Terapia Ocupacional',
        'activa' => true,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'nombre' => 'Terapia Psicológica Individual',
        'descripcion' => 'Sesiones personalizadas para manejo de ansiedad, depresión, estrés y otros trastornos emocionales.',
        'duracion' => 50,
        'precio' => 60.00,
        'imagen' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?w=400',
        'especialidad' => 'Psicología',
        'activa' => true,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'nombre' => 'Rehabilitación Deportiva',
        'descripcion' => 'Tratamiento especializado para lesiones deportivas y recuperación de atletas.',
        'duracion' => 75,
        'precio' => 65.00,
        'imagen' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400',
        'especialidad' => 'Fisioterapia',
        'activa' => true,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'nombre' => 'Terapia de Lenguaje',
        'descripcion' => 'Tratamiento para trastornos del habla, lenguaje y comunicación.',
        'duracion' => 45,
        'precio' => 55.00,
        'imagen' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=400',
        'especialidad' => 'Terapia de Lenguaje',
        'activa' => true,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'nombre' => 'Masoterapia',
        'descripcion' => 'Masajes terapéuticos para alivio del dolor, relajación muscular y mejora de circulación.',
        'duracion' => 60,
        'precio' => 40.00,
        'imagen' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=400',
        'especialidad' => 'Fisioterapia',
        'activa' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]
]);

echo "✅ Terapias insertadas\n\n";

echo "🎉 ¡Migración completada exitosamente!\n";
echo "📊 Resumen:\n";
echo "   - 5 usuarios creados (1 paciente, 3 médicos, 1 admin)\n";
echo "   - 6 terapias creadas\n";
echo "   - Tablas: users, terapias, citas, horarios_atencion\n\n";
echo "🔐 Credenciales de prueba:\n";
echo "   Paciente: 1234567890 / password123\n";
echo "   Médico: 0987654321 / medico123\n";
echo "   Admin: admin / admin123\n\n";
