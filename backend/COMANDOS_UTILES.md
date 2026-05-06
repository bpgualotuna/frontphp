# 🛠️ Comandos Útiles - Backend PHP

## Instalación y Configuración

```bash
# Instalar Composer (si no está instalado)
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Instalar dependencias del proyecto
cd backend
composer install

# Actualizar dependencias
composer update

# Regenerar autoload
composer dump-autoload
```

## Migraciones y Base de Datos

```bash
# Ejecutar migraciones (crear tablas)
php migrations/create_tables.php

# Verificar conexión a Supabase
php -r "
require 'vendor/autoload.php';
\$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
\$dotenv->load();
\$pdo = new PDO(
  'pgsql:host='.\$_ENV['DB_HOST'].';port='.\$_ENV['DB_PORT'].';dbname='.\$_ENV['DB_DATABASE'],
  \$_ENV['DB_USERNAME'],
  \$_ENV['DB_PASSWORD']
);
echo 'Conexión exitosa a Supabase!';
"
```

## Servidor de Desarrollo

```bash
# Iniciar servidor PHP built-in (puerto 8080)
cd backend/public
php -S localhost:8080

# Iniciar en otro puerto
php -S localhost:8081

# Iniciar con logs visibles
php -S localhost:8080 -t . 2>&1 | tee server.log

# Iniciar en todas las interfaces (accesible desde red local)
php -S 0.0.0.0:8080
```

## Testing de Endpoints

```bash
# Health check
curl http://localhost:8080/api/health

# Login
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"cedula":"1234567890","password":"password123"}'

# Obtener terapias
curl http://localhost:8080/api/terapias

# Obtener médicos
curl http://localhost:8080/api/medicos

# Crear cita (requiere token)
curl -X POST http://localhost:8080/api/citas \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "pacienteId": 1,
    "medicoId": 2,
    "terapiaId": 1,
    "fecha": "2026-05-10",
    "hora": "10:00",
    "sintomas": "Dolor en la rodilla",
    "tieneExamenes": false
  }'
```

## Debugging

```bash
# Ver logs de PHP
tail -f /var/log/php_errors.log

# Ver logs en tiempo real (si usaste tee)
tail -f server.log

# Verificar sintaxis de un archivo PHP
php -l src/Controllers/AuthController.php

# Ejecutar un script PHP directamente
php -f migrations/create_tables.php

# Modo interactivo (REPL)
php -a
```

## Eloquent Console (Testing de Modelos)

```bash
# Crear archivo test.php
cat > test.php << 'EOF'
<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

App\Config\Database::init();

// Probar modelos
$users = App\Models\User::all();
echo "Total usuarios: " . $users->count() . "\n";

$terapias = App\Models\Terapia::activas()->get();
echo "Terapias activas: " . $terapias->count() . "\n";

$citas = App\Models\Cita::proximas()->get();
echo "Próximas citas: " . $citas->count() . "\n";
EOF

# Ejecutar
php test.php
```

## Gestión de Usuarios

```bash
# Crear usuario de prueba
php -r "
require 'vendor/autoload.php';
\$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
\$dotenv->load();
App\Config\Database::init();

App\Models\User::create([
  'cedula' => '9999999999',
  'username' => '9999999999',
  'password' => password_hash('test123', PASSWORD_BCRYPT),
  'full_name' => 'Usuario de Prueba',
  'role' => 'paciente',
  'tiene_seguro' => true
]);

echo 'Usuario creado exitosamente!';
"

# Listar todos los usuarios
php -r "
require 'vendor/autoload.php';
\$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
\$dotenv->load();
App\Config\Database::init();

\$users = App\Models\User::all();
foreach (\$users as \$user) {
  echo \$user->id . ' - ' . \$user->full_name . ' (' . \$user->role . ')' . PHP_EOL;
}
"
```

## Limpieza y Mantenimiento

```bash
# Limpiar cache de Composer
composer clear-cache

# Eliminar vendor y reinstalar
rm -rf vendor
composer install

# Verificar dependencias obsoletas
composer outdated

# Actualizar una dependencia específica
composer update illuminate/database

# Verificar seguridad de dependencias
composer audit
```

## Producción

```bash
# Instalar solo dependencias de producción
composer install --no-dev --optimize-autoloader

# Optimizar autoloader
composer dump-autoload --optimize --classmap-authoritative

# Verificar configuración de PHP
php -i | grep -E "(memory_limit|max_execution_time|upload_max_filesize)"

# Generar archivo de configuración optimizado
php -r "
require 'vendor/autoload.php';
\$config = [
  'db' => [
    'host' => getenv('DB_HOST'),
    'database' => getenv('DB_DATABASE')
  ]
];
file_put_contents('config.cache.php', '<?php return ' . var_export(\$config, true) . ';');
"
```

## Docker (Opcional)

```bash
# Crear Dockerfile
cat > Dockerfile << 'EOF'
FROM php:8.1-apache

RUN docker-php-ext-install pdo pdo_pgsql

COPY . /var/www/html
WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader

EXPOSE 80
EOF

# Construir imagen
docker build -t gestion-citas-backend .

# Ejecutar contenedor
docker run -p 8080:80 gestion-citas-backend
```

## Herramientas de Desarrollo

```bash
# Instalar PHP CodeSniffer (linter)
composer require --dev squizlabs/php_codesniffer

# Ejecutar linter
./vendor/bin/phpcs src/

# Instalar PHPUnit (testing)
composer require --dev phpunit/phpunit

# Ejecutar tests
./vendor/bin/phpunit tests/

# Instalar PHP-CS-Fixer (formatter)
composer require --dev friendsofphp/php-cs-fixer

# Formatear código
./vendor/bin/php-cs-fixer fix src/
```

## Monitoreo

```bash
# Ver procesos PHP activos
ps aux | grep php

# Ver conexiones activas
netstat -an | grep 8080

# Monitorear uso de memoria
watch -n 1 'ps aux | grep php | grep -v grep'

# Ver logs en tiempo real con filtro
tail -f /var/log/php_errors.log | grep ERROR
```

## Backup de Base de Datos

```bash
# Exportar base de datos desde Supabase
pg_dump -h db.hdvmdtapjqqrwcqabqck.supabase.co \
  -U postgres \
  -d postgres \
  -f backup_$(date +%Y%m%d).sql

# Restaurar backup
psql -h db.hdvmdtapjqqrwcqabqck.supabase.co \
  -U postgres \
  -d postgres \
  -f backup_20260506.sql
```

## Variables de Entorno

```bash
# Ver todas las variables de entorno
php -r "print_r(\$_ENV);"

# Verificar variable específica
php -r "
require 'vendor/autoload.php';
\$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
\$dotenv->load();
echo 'DB_HOST: ' . \$_ENV['DB_HOST'] . PHP_EOL;
"

# Cambiar variable temporalmente
DB_HOST=localhost php public/index.php
```

---

**Guía completa de comandos para desarrollo y producción** 🚀
