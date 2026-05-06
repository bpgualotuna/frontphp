# 🚀 Despliegue en Render

## Guía Completa para Desplegar en Render.com

---

## 📋 Requisitos Previos

- Cuenta en Render.com (gratis)
- Cuenta en GitHub
- Proyecto subido a GitHub

---

## 🔧 PASO 1: Preparar el Backend para Render

### 1.1 Crear archivo de configuración

Crear `backend/render.yaml`:

```yaml
services:
  - type: web
    name: gestion-citas-backend
    env: php
    buildCommand: composer install --no-dev --optimize-autoloader
    startCommand: php -S 0.0.0.0:$PORT -t public
    envVars:
      - key: DB_CONNECTION
        value: pgsql
      - key: DB_HOST
        value: db.hdvmdtapjqqrwcqabqck.supabase.co
      - key: DB_PORT
        value: 5432
      - key: DB_DATABASE
        value: postgres
      - key: DB_USERNAME
        value: postgres
      - key: DB_PASSWORD
        sync: false
      - key: SUPABASE_URL
        value: https://hdvmdtapjqqrwcqabqck.supabase.co
      - key: SUPABASE_PUBLISHABLE_KEY
        value: sb_publishable_yNAiQg52qxw25Q9aYvltUw_2C6nDX6FSi
      - key: JWT_SECRET
        generateValue: true
      - key: JWT_EXPIRATION
        value: 86400
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
      - key: CORS_ALLOWED_ORIGINS
        value: https://tu-frontend.onrender.com
```

### 1.2 Crear Dockerfile (Alternativa)

Crear `backend/Dockerfile`:

```dockerfile
FROM php:8.1-apache

# Instalar extensiones de PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copiar archivos
COPY . /var/www/html

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader

# Configurar Apache
RUN a2enmod rewrite
COPY backend/.htaccess /var/www/html/.htaccess

# Exponer puerto
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]
```

---

## 🚀 PASO 2: Desplegar Backend en Render

### Opción A: Desde GitHub (Recomendado)

1. **Subir código a GitHub:**
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git remote add origin https://github.com/tu-usuario/tu-repo.git
   git push -u origin main
   ```

2. **Crear Web Service en Render:**
   - Ir a: https://dashboard.render.com/
   - Click en "New +" → "Web Service"
   - Conectar tu repositorio de GitHub
   - Configurar:
     - **Name:** `gestion-citas-backend`
     - **Environment:** `PHP`
     - **Build Command:** `composer install --no-dev --optimize-autoloader`
     - **Start Command:** `php -S 0.0.0.0:$PORT -t public`
     - **Root Directory:** `backend`

3. **Configurar Variables de Entorno:**
   - En la sección "Environment"
   - Agregar todas las variables del `.env`:
     ```
     DB_CONNECTION=pgsql
     DB_HOST=db.hdvmdtapjqqrwcqabqck.supabase.co
     DB_PORT=5432
     DB_DATABASE=postgres
     DB_USERNAME=postgres
     DB_PASSWORD=bpg2000brayan
     SUPABASE_URL=https://hdvmdtapjqqrwcqabqck.supabase.co
     SUPABASE_PUBLISHABLE_KEY=sb_publishable_yNAiQg52qxw25Q9aYvltUw_2C6nDX6FSi
     JWT_SECRET=tu_clave_secreta_muy_segura
     JWT_EXPIRATION=86400
     APP_ENV=production
     APP_DEBUG=false
     CORS_ALLOWED_ORIGINS=https://tu-frontend.onrender.com
     ```

4. **Deploy:**
   - Click en "Create Web Service"
   - Esperar a que termine el deploy (~5 minutos)
   - Tu backend estará en: `https://gestion-citas-backend.onrender.com`

### Opción B: Con Docker

1. **Crear Web Service:**
   - Seleccionar "Docker"
   - **Dockerfile Path:** `backend/Dockerfile`
   - Configurar variables de entorno

2. **Deploy:**
   - Click en "Create Web Service"

---

## 🎨 PASO 3: Desplegar Frontend en Render

### 3.1 Actualizar Variables de Entorno del Frontend

Editar `.env.production`:

```env
VITE_API_BASE_URL=https://gestion-citas-backend.onrender.com/api
VITE_APP_NAME=Sistema de Gestión Médica
VITE_SUPABASE_URL=https://hdvmdtapjqqrwcqabqck.supabase.co
VITE_SUPABASE_PUBLISHABLE_KEY=sb_publishable_yNAiQg52qxw25Q9aYvltUw_2C6nDX6FSi
```

### 3.2 Crear Static Site en Render

1. **Ir a Render Dashboard:**
   - Click en "New +" → "Static Site"
   - Conectar tu repositorio

2. **Configurar:**
   - **Name:** `gestion-citas-frontend`
   - **Build Command:** `npm install && npm run build`
   - **Publish Directory:** `dist`

3. **Variables de Entorno:**
   ```
   VITE_API_BASE_URL=https://gestion-citas-backend.onrender.com/api
   VITE_APP_NAME=Sistema de Gestión Médica
   VITE_SUPABASE_URL=https://hdvmdtapjqqrwcqabqck.supabase.co
   VITE_SUPABASE_PUBLISHABLE_KEY=sb_publishable_yNAiQg52qxw25Q9aYvltUw_2C6nDX6FSi
   ```

4. **Deploy:**
   - Click en "Create Static Site"
   - Tu frontend estará en: `https://gestion-citas-frontend.onrender.com`

---

## 🔄 PASO 4: Actualizar CORS

Una vez desplegado, actualizar el CORS del backend:

1. **Ir a tu Backend en Render**
2. **Environment → Editar `CORS_ALLOWED_ORIGINS`:**
   ```
   https://gestion-citas-frontend.onrender.com
   ```
3. **Guardar y Redeploy**

---

## ✅ PASO 5: Verificar Despliegue

### Backend

```bash
# Health check
curl https://gestion-citas-backend.onrender.com/api/health

# Login
curl -X POST https://gestion-citas-backend.onrender.com/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"cedula":"1234567890","password":"password123"}'
```

### Frontend

Abrir en el navegador:
```
https://gestion-citas-frontend.onrender.com
```

---

## 💰 Costos en Render

### Plan Free (Gratis)

**Backend (Web Service):**
- ✅ 750 horas/mes gratis
- ✅ Se duerme después de 15 minutos de inactividad
- ✅ Tarda ~30 segundos en despertar
- ⚠️ Suficiente para desarrollo/demo

**Frontend (Static Site):**
- ✅ 100% gratis
- ✅ CDN global
- ✅ SSL automático
- ✅ Sin límite de tráfico

**Total:** $0/mes

### Plan Paid (Producción)

**Backend (Web Service):**
- 💰 $7/mes
- ✅ Siempre activo (no se duerme)
- ✅ 512MB RAM
- ✅ Mejor performance

**Frontend (Static Site):**
- ✅ Gratis (siempre)

**Total:** $7/mes

---

## 🔧 Alternativas a Render

### Backend PHP

1. **Heroku**
   - $7/mes (Eco Dynos)
   - Fácil de usar
   - Buildpacks de PHP

2. **Railway**
   - $5/mes
   - Muy fácil de configurar
   - Soporte PHP nativo

3. **DigitalOcean App Platform**
   - $5/mes
   - Más control
   - Droplets disponibles

### Frontend React

1. **Vercel** (Recomendado)
   - ✅ Gratis
   - ✅ Deploy automático desde GitHub
   - ✅ CDN global
   - ✅ SSL automático

2. **Netlify**
   - ✅ Gratis
   - ✅ Similar a Vercel
   - ✅ Funciones serverless

3. **GitHub Pages**
   - ✅ Gratis
   - ⚠️ Solo sitios estáticos

---

## 📝 Checklist de Despliegue

```
Backend:
[ ] Código subido a GitHub
[ ] Web Service creado en Render
[ ] Variables de entorno configuradas
[ ] Build exitoso
[ ] Health check funciona
[ ] Login funciona

Frontend:
[ ] Variables de entorno actualizadas
[ ] Static Site creado en Render
[ ] Build exitoso
[ ] Sitio accesible
[ ] Login funciona desde el frontend
[ ] Puede crear citas

CORS:
[ ] CORS_ALLOWED_ORIGINS actualizado
[ ] No hay errores de CORS en el navegador

Base de Datos:
[ ] Supabase funcionando
[ ] Tablas creadas
[ ] Datos de prueba insertados
```

---

## 🎉 URLs Finales

Una vez desplegado:

- **Frontend:** `https://gestion-citas-frontend.onrender.com`
- **Backend API:** `https://gestion-citas-backend.onrender.com/api`
- **Health Check:** `https://gestion-citas-backend.onrender.com/api/health`

---

## 🆘 Problemas Comunes

### Backend se duerme (Plan Free)

**Solución:** Usar un servicio de "ping" como:
- UptimeRobot (gratis)
- Cron-job.org (gratis)

Configurar para hacer ping cada 10 minutos a:
```
https://gestion-citas-backend.onrender.com/api/health
```

### Error de Build en Render

**Solución:** Verificar logs en Render Dashboard

### CORS Error

**Solución:** Verificar que `CORS_ALLOWED_ORIGINS` incluye la URL del frontend

---

**¡Listo para producción en Render!** 🚀
