# Backend API - Sistema de Gestión de Citas Médicas

Backend PHP con Eloquent ORM conectado a Supabase PostgreSQL.

## 🏗️ Arquitectura

```
Frontend React → Backend PHP API → Supabase PostgreSQL
                 (Eloquent ORM)
```

## 📦 Stack Tecnológico

- **PHP 8.1+**
- **Eloquent ORM** (Illuminate Database)
- **Slim Framework 4** (Micro framework para API REST)
- **JWT** (Firebase PHP-JWT para autenticación)
- **PostgreSQL** (Supabase)
- **Composer** (Gestor de dependencias)

## 🚀 Instalación

### 1. Instalar Composer

Si no tienes Composer instalado:
```bash
# Windows: Descargar desde https://getcomposer.org/download/
# Linux/Mac:
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 2. Instalar Dependencias

```bash
cd backend
composer install
```

### 3. Configurar Variables de Entorno

El archivo `.env` ya está configurado con las credenciales de Supabase:

```env
DB_CONNECTION=pgsql
DB_HOST=db.hdvmdtapjqqrwcqabqck.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=bpg2000brayan

SUPABASE_URL=https://hdvmdtapjqqrwcqabqck.supabase.co
SUPABASE_PUBLISHABLE_KEY=sb_publishable_yNAiQg52qxw25Q9aYvltUw_2C6nDX6FSi

JWT_SECRET=gestion_citas_medicas_secret_key_2026
JWT_EXPIRATION=86400

APP_URL=http://localhost:8080
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:5173
```

### 4. Ejecutar Migraciones

Crear las tablas en Supabase:

```bash
php migrations/create_tables.php
```

Esto creará:
- ✅ Tabla `users` (pacientes, médicos, admin)
- ✅ Tabla `terapias`
- ✅ Tabla `citas`
- ✅ Tabla `horarios_atencion`
- ✅ Datos de prueba (5 usuarios, 6 terapias)

### 5. Iniciar Servidor

```bash
# Opción 1: PHP Built-in Server
cd public
php -S localhost:8080

# Opción 2: Con Apache/Nginx
# Configurar DocumentRoot a /backend/public
```

La API estará disponible en: `http://localhost:8080/api`

## 📚 Endpoints de la API

### 🔓 Públicos (Sin autenticación)

#### Health Check
```http
GET /api/health
```

#### Autenticación
```http
POST /api/auth/login
Content-Type: application/json

{
  "cedula": "1234567890",
  "password": "password123"
}
```

```http
POST /api/auth/register
Content-Type: application/json

{
  "cedula": "1234567890",
  "nombresCompletos": "Juan Pérez",
  "password": "password123",
  "direccion": "Av. Principal 123",
  "edad": 35,
  "sexo": "masculino",
  "tieneSeguro": true,
  "telefono": "0999888777",
  "email": "juan@email.com"
}
```

#### Terapias
```http
GET /api/terapias
GET /api/terapias/{id}
```

#### Médicos
```http
GET /api/medicos
GET /api/medicos/{id}
GET /api/medicos/especialidad/{especialidad}
```

### 🔒 Protegidos (Requieren JWT Token)

Agregar header en todas las peticiones:
```
Authorization: Bearer {token}
```

#### Usuario Actual
```http
GET /api/auth/me
PUT /api/auth/profile
```

#### Citas
```http
GET /api/citas/paciente/{pacienteId}
GET /api/citas/proximas/{pacienteId}
GET /api/citas/{id}

POST /api/citas
Content-Type: application/json

{
  "pacienteId": 1,
  "medicoId": 2,
  "terapiaId": 1,
  "fecha": "2026-05-10",
  "hora": "10:00",
  "sintomas": "Dolor en la rodilla",
  "tieneExamenes": false
}

PUT /api/citas/{id}/cancelar
Content-Type: application/json

{
  "motivo": "No puedo asistir"
}
```

#### Horarios Disponibles
```http
GET /api/horarios/disponibles?fecha=2026-05-10&terapiaId=1
```

## 🗂️ Estructura del Proyecto

```
backend/
├── public/
│   └── index.php              # Entry point de la API
├── src/
│   ├── Config/
│   │   └── Database.php       # Configuración de Eloquent
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── CitaController.php
│   │   ├── TerapiaController.php
│   │   ├── MedicoController.php
│   │   └── HorarioController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Cita.php
│   │   ├── Terapia.php
│   │   └── HorarioAtencion.php
│   └── Middleware/
│       └── AuthMiddleware.php
├── migrations/
│   └── create_tables.php      # Script de migración
├── .env                       # Variables de entorno
├── .env.example
├── composer.json
└── README.md
```

## 🔐 Autenticación JWT

### Login
1. Usuario envía credenciales a `/api/auth/login`
2. Backend valida y genera JWT token
3. Frontend guarda token en sessionStorage
4. Frontend envía token en header `Authorization: Bearer {token}`

### Middleware
El `AuthMiddleware` valida el token en todas las rutas protegidas.

## 🗄️ Modelos Eloquent

### User
```php
User::where('role', 'medico')->get();
User::find(1)->citasComoPaciente;
```

### Cita
```php
Cita::proximas()->get();
Cita::where('paciente_id', 1)->with(['medico', 'terapia'])->get();
```

### Terapia
```php
Terapia::activas()->get();
```

## 🧪 Datos de Prueba

### Usuarios
- **Paciente:** `1234567890` / `password123`
- **Médico 1:** `0987654321` / `medico123` (Fisioterapia)
- **Médico 2:** `1122334455` / `medico123` (Terapia Ocupacional)
- **Médico 3:** `5566778899` / `medico123` (Psicología)
- **Admin:** `admin` / `admin123`

### Terapias
- Fisioterapia General ($45)
- Terapia Ocupacional ($50)
- Terapia Psicológica Individual ($60)
- Rehabilitación Deportiva ($65)
- Terapia de Lenguaje ($55)
- Masoterapia ($40)

## 🔧 Comandos Útiles

```bash
# Instalar dependencias
composer install

# Actualizar dependencias
composer update

# Ejecutar migraciones
php migrations/create_tables.php

# Iniciar servidor
cd public && php -S localhost:8080

# Ver logs de PHP
tail -f /var/log/php_errors.log
```

## 🐛 Troubleshooting

### Error: "Class not found"
```bash
composer dump-autoload
```

### Error de conexión a Supabase
Verificar que las credenciales en `.env` sean correctas.

### Error CORS
Agregar el origen del frontend en `CORS_ALLOWED_ORIGINS` en `.env`.

### Error JWT
Verificar que `JWT_SECRET` esté configurado en `.env`.

## 📝 Notas Importantes

1. **Seguridad:** En producción, cambiar `JWT_SECRET` por una clave más segura
2. **HTTPS:** Usar HTTPS en producción para proteger las credenciales
3. **Rate Limiting:** Implementar rate limiting para prevenir ataques
4. **Validaciones:** Agregar más validaciones según necesidades
5. **Logs:** Implementar sistema de logs para debugging

## 🚀 Despliegue

### Opción 1: Servidor VPS (Linux)
```bash
# Instalar PHP 8.1+
sudo apt install php8.1 php8.1-pgsql php8.1-mbstring php8.1-xml

# Configurar Apache/Nginx
# DocumentRoot: /path/to/backend/public

# Instalar dependencias
composer install --no-dev --optimize-autoloader
```

### Opción 2: Heroku
```bash
# Crear Procfile
echo "web: cd public && php -S 0.0.0.0:\$PORT" > Procfile

# Deploy
git push heroku main
```

### Opción 3: Docker
```dockerfile
FROM php:8.1-apache
RUN docker-php-ext-install pdo pdo_pgsql
COPY . /var/www/html
RUN composer install --no-dev
```

## 📚 Recursos

- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Slim Framework](https://www.slimframework.com/)
- [JWT PHP](https://github.com/firebase/php-jwt)
- [Supabase Docs](https://supabase.com/docs)

---

**Desarrollado con ❤️ usando PHP + Eloquent ORM + Supabase**
