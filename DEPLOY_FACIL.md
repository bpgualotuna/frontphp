# 🚀 Deploy Súper Fácil - 3 Pasos

## Sin instalar nada en tu computadora

---

## ✅ Lo que Necesitas

1. Cuenta en GitHub (gratis) - https://github.com
2. Cuenta en Render (gratis) - https://render.com

---

## 📝 PASO 1: Subir a GitHub

### 1.1 Crear repositorio en GitHub

1. Ir a: https://github.com/new
2. Nombre: `gestion-citas-medicas`
3. Público o Privado (tu elección)
4. Click en "Create repository"

### 1.2 Subir el código

Abrir terminal en la carpeta del proyecto:

```bash
git init
git add .
git commit -m "Deploy inicial"
git remote add origin https://github.com/TU-USUARIO/gestion-citas-medicas.git
git push -u origin main
```

**Nota:** Reemplaza `TU-USUARIO` con tu usuario de GitHub

---

## 🚀 PASO 2: Desplegar en Render

### 2.1 Conectar GitHub con Render

1. Ir a: https://dashboard.render.com/
2. Si es tu primera vez, crear cuenta con GitHub
3. Autorizar a Render para acceder a tus repositorios

### 2.2 Crear Blueprint

1. Click en "New +" → "Blueprint"
2. Seleccionar el repositorio `gestion-citas-medicas`
3. Render detectará automáticamente el archivo `render.yaml`
4. Click en "Apply"

### 2.3 Esperar

- Backend: ~5-7 minutos ⏳
- Frontend: ~3-5 minutos ⏳

**Verás los logs en tiempo real**

---

## 🔧 PASO 3: Configurar URLs

Una vez que termine el deploy:

### 3.1 Copiar URLs

Render te dará URLs como:
- Backend: `https://gestion-citas-backend-XXXX.onrender.com`
- Frontend: `https://gestion-citas-frontend-XXXX.onrender.com`

### 3.2 Actualizar Backend

1. Ir al servicio **backend** en Render
2. Click en "Environment" (menú izquierdo)
3. Editar estas variables:
   - `APP_URL`: Pegar la URL del backend
   - `CORS_ALLOWED_ORIGINS`: Pegar la URL del frontend
4. Click en "Save Changes"
5. Esperar redeploy (~2 minutos)

### 3.3 Actualizar Frontend

1. Ir al servicio **frontend** en Render
2. Click en "Environment"
3. Editar esta variable:
   - `VITE_API_BASE_URL`: Pegar la URL del backend + `/api`
   - Ejemplo: `https://gestion-citas-backend-XXXX.onrender.com/api`
4. Click en "Save Changes"
5. Esperar redeploy (~2 minutos)

---

## ✅ PASO 4: Probar

### 4.1 Verificar Backend

Abrir en el navegador:
```
https://gestion-citas-backend-XXXX.onrender.com/api/health
```

Deberías ver:
```json
{
  "status": "ok",
  "message": "API funcionando correctamente"
}
```

### 4.2 Abrir el Sistema

Abrir en el navegador:
```
https://gestion-citas-frontend-XXXX.onrender.com
```

### 4.3 Login

- Usuario: `1234567890`
- Contraseña: `password123`

---

## 🎉 ¡Listo!

Tu sistema está desplegado y funcionando en:
- ✅ Backend con API REST
- ✅ Frontend React
- ✅ Base de datos en Supabase
- ✅ SSL/HTTPS automático
- ✅ 5 usuarios de prueba
- ✅ 6 terapias disponibles

---

## 💡 Notas Importantes

### Primera Carga Lenta

En el plan free, la primera vez que accedas puede tardar ~30 segundos porque el backend se está "despertando". Esto es normal.

### Migraciones Automáticas

Las tablas y datos de prueba se crearon automáticamente durante el deploy. No necesitas hacer nada.

### Costos

- **Todo es GRATIS** 🎉
- Backend: 750 horas/mes gratis
- Frontend: Siempre gratis
- Base de datos: Plan free de Supabase

---

## 🐛 ¿Problemas?

### Error de CORS

**Solución:** Verificar que `CORS_ALLOWED_ORIGINS` en el backend tenga la URL correcta del frontend.

### Backend no responde

**Solución:** Esperar 30 segundos (se está despertando en el plan free).

### Login no funciona

**Solución:** 
1. Verificar que `VITE_API_BASE_URL` en el frontend tenga la URL correcta del backend
2. Debe terminar en `/api`

---

## 📞 Más Ayuda

Ver documentación completa: `DEPLOY_RENDER_AUTOMATICO.md`

---

**¡Deploy completado en 3 pasos!** 🚀✨
