# ⚡ Inicio Rápido - Backend PHP

## 🎯 Objetivo

Poner en marcha el backend PHP con Eloquent ORM conectado a Supabase en **5 minutos**.

---

## ✅ Checklist Previo

Antes de empezar, verifica que tienes instalado:

```bash
php --version    # Debe ser 8.1 o superior
composer --version
```

Si no tienes Composer:
- **Windows:** https://getcomposer.org/download/
- **Linux/Mac:** `curl -sS https://getcomposer.org/installer | php && sudo mv composer.phar /usr/local/bin/composer`

---

## 🚀 Pasos de Instalación

### 1. Instalar Dependencias (2 minutos)

```bash
cd backend
composer install
```

**Salida esperada:**
```
Loading composer repositories with package information
Installing dependencies from lock file
...
Generating autoload files
```

### 2. Crear Tablas en Supabase (1 minuto)

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

### 3. Iniciar Servidor (30 segundos)

```bash
cd public
php -S localhost:8080
```

**Salida esperada:**
```
PHP 8.1.x Development Server (http://localhost:8080) started
```

---

## ✅ Verificar que Funciona

### Opción 1: Navegador

Abre en tu navegador:
```
http://localhost:8080/api/health
```

Deberías ver:
```json
{
  "status": "ok",
  "message": "API funcionando correctamente",
  "timestamp": "2026-05-06 10:30:00"
}
```

### Opción 2: Terminal (curl)

```bash
# Health check
curl http://localhost:8080/api/health

# Listar terapias
curl http://localhost:8080/api/terapias

# Listar médicos
curl http://localhost:8080/api/medicos

# Login
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"cedula":"1234567890","password":"password123"}'
```

---

## 🎉 ¡Listo!

Si ves las respuestas JSON, **el backend está funcionando correctamente**.

Ahora puedes:
1. Dejar el servidor corriendo
2. Abrir otra terminal
3. Iniciar el frontend React

---

## 🐛 Problemas Comunes

### Error: "composer: command not found"

**Solución:** Instalar Composer
```bash
# Windows: Descargar desde https://getcomposer.org/download/
# Linux/Mac:
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Error: "Class 'Dotenv\Dotenv' not found"

**Solución:** Reinstalar dependencias
```bash
cd backend
rm -rf vendor
composer install
```

### Error: "Connection refused" al conectar a Supabase

**Solución:** Verificar credenciales en `backend/.env`
```env
DB_HOST=db.hdvmdtapjqqrwcqabqck.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=bpg2000brayan
```

### Error: "Port 8080 already in use"

**Solución:** Usar otro puerto
```bash
cd backend/public
php -S localhost:8081
```

Y actualizar `.env` del frontend:
```env
VITE_API_BASE_URL=http://localhost:8081/api
```

### Error: "SQLSTATE[08006] Connection refused"

**Causas posibles:**
1. No tienes conexión a internet
2. Firewall bloqueando Supabase
3. Credenciales incorrectas

**Solución:** Verificar conexión
```bash
ping db.hdvmdtapjqqrwcqabqck.supabase.co
```

---

## 📚 Siguiente Paso

Una vez que el backend esté funcionando, inicia el frontend:

```bash
# En otra terminal, desde la raíz del proyecto
npm install
npm run dev
```

Luego accede a: http://localhost:5173

---

## 🔐 Credenciales de Prueba

- **Paciente:** `1234567890` / `password123`
- **Médico:** `0987654321` / `medico123`
- **Admin:** `admin` / `admin123`

---

## 📖 Documentación Completa

- **Guía de Instalación:** [`GUIA_INSTALACION.md`](../GUIA_INSTALACION.md)
- **Backend README:** [`backend/README.md`](README.md)
- **Comandos Útiles:** [`backend/COMANDOS_UTILES.md`](COMANDOS_UTILES.md)
- **Integración Supabase:** [`INTEGRACION_SUPABASE.md`](../INTEGRACION_SUPABASE.md)

---

**¡Backend listo en 5 minutos!** ⚡
