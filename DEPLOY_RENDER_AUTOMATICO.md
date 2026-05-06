# 🚀 Deploy Automático en Render

## Guía Simplificada - Sin Instalación Local

Esta guía te permite desplegar el sistema completo en Render **sin necesidad de instalar PHP, Composer ni ejecutar migraciones localmente**. Todo se hace automáticamente en Render.

---

## ✨ Lo que se Desplegará Automáticamente

✅ **Backend PHP** con todas las dependencias  
✅ **Migraciones de base de datos** (tablas + datos de prueba)  
✅ **Frontend React** compilado  
✅ **Configuración de CORS**  
✅ **SSL/HTTPS** automático  

---

## 📋 Requisitos Previos

1. **Cuenta en GitHub** (gratis)
2. **Cuenta en Render** (gratis) - https://render.com
3. **Este proyecto** subido a GitHub

---

## 🎯 PASO 1: Subir el Proyecto a GitHub

### 1.1 Crear Repositorio en GitHub

1. Ir a: https://github.com/new
2. Nombre del repositorio: `gestion-citas-medicas`
3. Dejar en **Público** o **Privado** (tu elección)
4. **NO** marcar "Initialize with README"
5. Click en "Create repository"

### 1.2 Subir el Código

Abrir terminal en la carpeta del proyecto y ejecutar:

```bash
# Inicializar Git (si no está inicializado)
git init

# Agregar todos los archivos
git add .

# Hacer commit
git commit -m "Deploy inicial a Render"

# Conectar con GitHub (reemplaza con tu URL)
git remote add origin https://github.com/TU-USUARIO/gestion-citas-medicas.git

# Subir código
git branch -M main
git push -u origin main
```

**Nota:** Reemplaza `TU-USUARIO` con tu nombre de usuario de GitHub.

---

## 🚀 PASO 2: Desplegar en Render

### Opción A: Deploy con Blueprint (Automático - Recomendado)

1. **Ir a Render Dashboard:**
   - https://dashboard.render.com/

2. **Crear Nuevo Blueprint:**
   - Click en "New +" → "Blueprint"
   - Conectar tu cuenta de GitHub (si no lo has hecho)
   - Seleccionar el repositorio `gestion-citas-medicas`
   - Render detectará automáticamente el archivo `render.yaml`

3. **Configurar Variables Sensibles:**
   
   Render te pedirá configurar estas variables:
   
   **Para el Backend:**
   - `APP_URL`: Dejar vacío por ahora (se llenará después)
   
   **Para el Frontend:**
   - `VITE_API_BASE_URL`: Dejar vacío por ahora (se llenará después)

4. **Aplicar Blueprint:**
   - Click en "Apply"
   - Render comenzará a desplegar ambos servicios automáticamente

5. **Esperar el Deploy:**
   - Backend: ~5-7 minutos
   - Frontend: ~3-5 minutos
   
   Verás los logs en tiempo real.

### Opción B: Deploy Manual (Paso a Paso)

Si prefieres hacerlo manualmente:

#### Backend

1. **Crear Web Service:**
   - Dashboard → "New +" → "Web Service"
   - Conectar repositorio de GitHub
   - Seleccionar `gestion-citas-medicas`

2. **Configurar:**
   - **Name:** `gestion-citas-backend`
   - **Runtime:** `PHP`
   - **Build Command:** `cd backend && chmod +x build.sh && ./build.sh`
   - **Start Command:** `cd backend && chmod +x start.sh && ./start.sh`
   - **Plan:** `Free`

3. **Variables de Entorno:**
   
   Agregar estas variables en la sección "Environment":
   
   ```
   DB_CONNECTION=pgsql
   DB_HOST=db.hdvmdtapjqqrwcqabqck.supabase.co
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=postgres
   DB_PASSWORD=bpg2000brayan
   SUPABASE_URL=https://hdvmdtapjqqrwcqabqck.supabase.co
   SUPABASE_PUBLISHABLE_KEY=sb_publishable_yNAiQg52qxw25Q9aYvltUw_2C6nDX6FSi
   JWT_SECRET=gestion_citas_medicas_secret_key_2026_production
   JWT_EXPIRATION=86400
   APP_ENV=production
   APP_DEBUG=false
   CORS_ALLOWED_ORIGINS=*
   ```

4. **Crear Service:**
   - Click en "Create Web Service"
   - Esperar a que termine el deploy

#### Frontend

1. **Crear Static Site:**
   - Dashboard → "New +" → "Static Site"
   - Seleccionar el mismo repositorio

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

4. **Crear Site:**
   - Click en "Create Static Site"

---

## 🔧 PASO 3: Configurar URLs Finales

Una vez que ambos servicios estén desplegados:

### 3.1 Obtener URLs

Render te dará URLs como:
- **Backend:** `https://gestion-citas-backend.onrender.com`
- **Frontend:** `https://gestion-citas-frontend.onrender.com`

### 3.2 Actualizar Variables de Entorno

#### En el Backend:

1. Ir a tu servicio de backend en Render
2. Environment → Editar variables:
   - `APP_URL`: `https://gestion-citas-backend.onrender.com`
   - `CORS_ALLOWED_ORIGINS`: `https://gestion-citas-frontend.onrender.com`
3. Guardar cambios (se redesplegará automáticamente)

#### En el Frontend:

1. Ir a tu static site en Render
2. Environment → Editar variable:
   - `VITE_API_BASE_URL`: `https://gestion-citas-backend.onrender.com/api`
3. Guardar cambios (se redesplegará automáticamente)

---

## ✅ PASO 4: Verificar el Deploy

### 4.1 Verificar Backend

Abrir en el navegador:
```
https://gestion-citas-backend.onrender.com/api/health
```

Deberías ver:
```json
{
  "status": "ok",
  "message": "API funcionando correctamente",
  "timestamp": "2026-05-06 10:30:00"
}
```

### 4.2 Verificar Base de Datos

El script de migración se ejecutó automáticamente. Para verificar:

```bash
# Probar login
curl -X POST https://gestion-citas-backend.onrender.com/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"cedula":"1234567890","password":"password123"}'
```

Deberías recibir un token JWT.

### 4.3 Verificar Frontend

Abrir en el navegador:
```
https://gestion-citas-frontend.onrender.com
```

Deberías ver la página de login.

### 4.4 Probar el Sistema Completo

1. **Login:**
   - Usuario: `1234567890`
   - Contraseña: `password123`

2. **Explorar:**
   - Dashboard
   - Terapias
   - Crear una cita
   - Ver "Mis Citas"

---

## 📊 Logs y Debugging

### Ver Logs del Backend

1. Ir a tu servicio de backend en Render
2. Click en "Logs"
3. Ver logs en tiempo real

**Buscar:**
- ✅ "Migración completada exitosamente"
- ✅ "Usuarios insertados"
- ✅ "Terapias insertadas"

### Ver Logs del Frontend

1. Ir a tu static site en Render
2. Click en "Logs"
3. Ver el proceso de build

---

## 🐛 Solución de Problemas

### Error: "Build failed"

**Causa:** Error en el script de build

**Solución:**
1. Ver los logs completos en Render
2. Verificar que todos los archivos estén en GitHub
3. Verificar que `build.sh` tenga permisos de ejecución

### Error: "Connection refused" en el frontend

**Causa:** URL del backend incorrecta

**Solución:**
1. Verificar `VITE_API_BASE_URL` en el frontend
2. Debe ser: `https://gestion-citas-backend.onrender.com/api`
3. Redeploy del frontend

### Error: "CORS policy"

**Causa:** CORS no configurado correctamente

**Solución:**
1. Ir al backend en Render
2. Editar `CORS_ALLOWED_ORIGINS`
3. Debe incluir la URL del frontend
4. Redeploy del backend

### Backend se duerme (Plan Free)

**Comportamiento normal:** En el plan free, el backend se duerme después de 15 minutos de inactividad.

**Solución temporal:**
- Esperar ~30 segundos en la primera petición
- El backend se despertará automáticamente

**Solución permanente:**
- Upgrade a plan Starter ($7/mes)
- O usar un servicio de "ping" como UptimeRobot

### Migraciones no se ejecutaron

**Verificar:**
1. Ver logs del backend durante el build
2. Buscar: "Ejecutando migraciones"

**Solución:**
1. Ir al backend en Render
2. Manual Deploy → "Clear build cache & deploy"
3. Esto forzará la ejecución de las migraciones

---

## 💰 Costos

### Plan Free (Actual)

- **Backend:** $0/mes
  - 750 horas/mes gratis
  - Se duerme después de 15 minutos
  - Tarda ~30 segundos en despertar

- **Frontend:** $0/mes
  - Siempre gratis
  - CDN global
  - SSL automático

- **Base de Datos (Supabase):** $0/mes
  - Plan free hasta 500MB

**Total:** $0/mes

### Plan Starter (Recomendado para Producción)

- **Backend:** $7/mes
  - Siempre activo
  - No se duerme
  - Mejor performance

- **Frontend:** $0/mes (siempre gratis)

- **Base de Datos:** $0/mes (o $25/mes para plan Pro)

**Total:** $7/mes

---

## 🎉 URLs Finales

Una vez completado el deploy:

- **Frontend:** `https://gestion-citas-frontend.onrender.com`
- **Backend API:** `https://gestion-citas-backend.onrender.com/api`
- **Health Check:** `https://gestion-citas-backend.onrender.com/api/health`
- **Supabase Dashboard:** `https://supabase.com/dashboard`

---

## 📝 Checklist Final

```
Preparación:
[✅] Código subido a GitHub
[✅] Archivos de configuración creados (render.yaml, build.sh, start.sh)

Deploy:
[ ] Blueprint aplicado en Render (o servicios creados manualmente)
[ ] Backend desplegado exitosamente
[ ] Frontend desplegado exitosamente
[ ] Variables de entorno configuradas

Verificación:
[ ] Health check del backend funciona
[ ] Login funciona desde el frontend
[ ] Se pueden ver terapias
[ ] Se puede crear una cita
[ ] No hay errores de CORS

Base de Datos:
[ ] Migraciones ejecutadas automáticamente
[ ] 5 usuarios creados
[ ] 6 terapias creadas
[ ] Tablas visibles en Supabase Dashboard
```

---

## 🔄 Actualizaciones Futuras

Para actualizar el sistema:

```bash
# 1. Hacer cambios en el código
# 2. Commit y push a GitHub
git add .
git commit -m "Descripción de los cambios"
git push

# 3. Render detectará los cambios y redesplegará automáticamente
```

---

## 🆘 Soporte

Si tienes problemas:

1. **Ver logs en Render:** Dashboard → Tu servicio → Logs
2. **Verificar variables de entorno:** Dashboard → Tu servicio → Environment
3. **Forzar redeploy:** Dashboard → Tu servicio → Manual Deploy → "Clear build cache & deploy"

---

## 🎓 Credenciales de Prueba

Una vez desplegado, puedes usar:

- **Paciente:** `1234567890` / `password123`
- **Médico:** `0987654321` / `medico123`
- **Admin:** `admin` / `admin123`

---

**¡Sistema listo para producción en Render sin instalación local!** 🚀✨

## 📌 Notas Importantes

1. **Primera carga lenta:** En el plan free, la primera petición puede tardar ~30 segundos (el backend se está despertando)

2. **Migraciones automáticas:** Las migraciones se ejecutan automáticamente en el primer deploy. No necesitas hacer nada.

3. **SSL/HTTPS:** Render proporciona SSL automáticamente. Todas las URLs serán HTTPS.

4. **Dominio personalizado:** Puedes agregar tu propio dominio en Render (Settings → Custom Domain)

5. **Monitoreo:** Render proporciona métricas básicas de uso y logs en tiempo real.
