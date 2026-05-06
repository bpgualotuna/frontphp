# 🚀 Guía de Instalación Completa

## Sistema de Gestión de Citas Médicas
**Frontend React + Backend PHP + Supabase PostgreSQL**

---

## 📋 Requisitos Previos

### Software Necesario
- ✅ **Node.js 18+** y npm
- ✅ **PHP 8.1+**
- ✅ **Composer** (gestor de dependencias PHP)
- ✅ **Git**

### Verificar Instalaciones
```bash
node --version    # Debe ser v18 o superior
npm --version
php --version     # Debe ser 8.1 o superior
composer --version
```

---

## 🎯 Instalación Paso a Paso

### PASO 1: Clonar el Repositorio

```bash
git clone <repository-url>
cd gestion-citas-medicas
```

---

### PASO 2: Configurar el Backend PHP

#### 2.1 Instalar Dependencias PHP

```bash
cd backend
composer install
```

Esto instalará:
- Eloquent ORM
- Slim Framework
- JWT (Firebase PHP-JWT)
- DotEnv
- CORS Middleware

#### 2.2 Verificar Variables de Entorno

El archivo `backend/.env` ya está configurado con las credenciales de Supabase:

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

#### 2.3 Ejecutar Migraciones (Crear Tablas en Supabase)

```bash
php migrations/create_tables.php
```

**Salida esperada:**
```
🚀 Iniciando migración de base de datos...

📋 Creando tabla 'users'...
✅ Tabla 'users' creada exitosamente

📋 Creando tabla 'terapias'...
✅ Tabla 'terapias' creada exitosamente

📋 Creando tabla 'citas'...
✅ Tabla 'citas' creada exitosamente

📋 Creando tabla 'horarios_atencion'...
✅ Tabla 'horarios_atencion' creada exitosamente

📦 Insertando datos de prueba...
👤 Insertando usuarios...
✅ Usuarios insertados

💊 Insertando terapias...
✅ Terapias insertadas

🎉 ¡Migración completada exitosamente!
```

#### 2.4 Iniciar Servidor PHP

```bash
cd public
php -S localhost:8080
```

**Verificar que funciona:**
Abrir en el navegador: http://localhost:8080/api/health

Deberías ver:
```json
{
  "status": "ok",
  "message": "API funcionando correctamente",
  "timestamp": "2026-05-06 10:30:00"
}
```

---

### PASO 3: Configurar el Frontend React

#### 3.1 Volver a la raíz del proyecto

```bash
cd ../..  # Salir de backend/public
```

#### 3.2 Instalar Dependencias Node

```bash
npm install
```

Esto instalará:
- React 19
- Material-UI
- Redux Toolkit
- React Router
- Axios
- Y todas las demás dependencias

#### 3.3 Configurar Variables de Entorno

```bash
cp .env.example .env
```

El archivo `.env` debe contener:
```env
VITE_API_BASE_URL=http://localhost:8080/api
VITE_APP_NAME=Sistema de Gestión Médica

VITE_SUPABASE_URL=https://hdvmdtapjqqrwcqabqck.supabase.co
VITE_SUPABASE_PUBLISHABLE_KEY=sb_publishable_yNAiQg52qxw25Q9aYvltUw_2C6nDX6FSi
```

#### 3.4 Iniciar Servidor de Desarrollo

```bash
npm run dev
```

**Salida esperada:**
```
VITE v6.3.5  ready in 500 ms

➜  Local:   http://localhost:5173/
➜  Network: use --host to expose
```

---

## ✅ Verificación de la Instalación

### 1. Backend PHP (Puerto 8080)

Abrir en el navegador o Postman:

**Health Check:**
```
GET http://localhost:8080/api/health
```

**Obtener Terapias:**
```
GET http://localhost:8080/api/terapias
```

**Obtener Médicos:**
```
GET http://localhost:8080/api/medicos
```

### 2. Frontend React (Puerto 5173)

Abrir en el navegador:
```
http://localhost:5173
```

Deberías ver la página de login.

---

## 🔐 Credenciales de Prueba

### Paciente
- **Usuario:** `1234567890`
- **Contraseña:** `password123`

### Médico
- **Usuario:** `0987654321`
- **Contraseña:** `medico123`

### Administrador
- **Usuario:** `admin`
- **Contraseña:** `admin123`

---

## 🧪 Probar el Sistema Completo

### 1. Login
1. Ir a http://localhost:5173
2. Ingresar: `1234567890` / `password123`
3. Click en "Iniciar Sesión"

### 2. Ver Dashboard
- Deberías ver el dashboard con estadísticas

### 3. Reservar una Cita
1. Click en "Reservar Cita"
2. Seleccionar una terapia
3. Elegir fecha y hora
4. Completar formulario
5. Confirmar

### 4. Ver Mis Citas
- Click en "Mis Citas" en el menú lateral
- Deberías ver la cita recién creada

---

## 🐛 Solución de Problemas

### Error: "composer: command not found"

**Instalar Composer:**

**Windows:**
- Descargar desde: https://getcomposer.org/download/
- Ejecutar el instalador

**Linux/Mac:**
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Error: "Class not found" en PHP

```bash
cd backend
composer dump-autoload
```

### Error: "Connection refused" al conectar a Supabase

Verificar:
1. Las credenciales en `backend/.env` son correctas
2. Tienes conexión a internet
3. Supabase no está bloqueado por firewall

### Error: CORS en el navegador

Verificar que en `backend/.env`:
```env
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:5173
```

### Error: "Port 8080 already in use"

Cambiar el puerto del backend:
```bash
cd backend/public
php -S localhost:8081
```

Y actualizar `.env` del frontend:
```env
VITE_API_BASE_URL=http://localhost:8081/api
```

### Error: "Port 5173 already in use"

Vite usará automáticamente el siguiente puerto disponible (5174, 5175, etc.)

---

## 📊 Estructura de Puertos

| Servicio | Puerto | URL |
|----------|--------|-----|
| Frontend React | 5173 | http://localhost:5173 |
| Backend PHP API | 8080 | http://localhost:8080/api |
| Supabase PostgreSQL | 5432 | db.hdvmdtapjqqrwcqabqck.supabase.co |

---

## 🔄 Comandos Útiles

### Backend
```bash
# Instalar dependencias
cd backend && composer install

# Ejecutar migraciones
php migrations/create_tables.php

# Iniciar servidor
cd public && php -S localhost:8080

# Ver logs
tail -f /var/log/php_errors.log
```

### Frontend
```bash
# Instalar dependencias
npm install

# Modo desarrollo
npm run dev

# Compilar para producción
npm run build

# Previsualizar build
npm run preview

# Linter
npm run lint
```

---

## 📦 Datos Incluidos

### Usuarios (5)
- 1 Paciente
- 3 Médicos (Fisioterapia, Terapia Ocupacional, Psicología)
- 1 Administrador

### Terapias (6)
- Fisioterapia General ($45)
- Terapia Ocupacional ($50)
- Terapia Psicológica Individual ($60)
- Rehabilitación Deportiva ($65)
- Terapia de Lenguaje ($55)
- Masoterapia ($40)

---

## 🎉 ¡Listo!

Si todo funcionó correctamente, deberías tener:

✅ Backend PHP corriendo en puerto 8080  
✅ Frontend React corriendo en puerto 5173  
✅ Base de datos PostgreSQL en Supabase con tablas y datos  
✅ Sistema completamente funcional  

---

## 📚 Documentación Adicional

- **Backend:** Ver `backend/README.md`
- **Frontend:** Ver `README.md`
- **Especificaciones:** Ver `BACKEND_TECHNICAL_SPECIFICATION.md`

---

## 🆘 Soporte

Si encuentras algún problema:

1. Verificar que todos los servicios estén corriendo
2. Revisar los logs de PHP y del navegador (Console)
3. Verificar las credenciales de Supabase
4. Asegurarse de que los puertos no estén ocupados

---

**¡Disfruta del sistema! 🎊**
