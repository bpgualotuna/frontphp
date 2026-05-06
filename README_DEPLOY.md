# 🚀 Deploy Rápido en Render

## Pasos Simplificados

### 1. Subir a GitHub

```bash
git init
git add .
git commit -m "Deploy inicial"
git remote add origin https://github.com/TU-USUARIO/gestion-citas-medicas.git
git push -u origin main
```

### 2. Desplegar en Render

1. Ir a: https://dashboard.render.com/
2. Click en "New +" → "Blueprint"
3. Conectar GitHub y seleccionar el repositorio
4. Click en "Apply"
5. Esperar 5-10 minutos

### 3. Actualizar URLs

Una vez desplegado:

**Backend:**
- Ir al servicio de backend
- Environment → Editar:
  - `CORS_ALLOWED_ORIGINS`: URL del frontend
- Guardar

**Frontend:**
- Ir al static site
- Environment → Editar:
  - `VITE_API_BASE_URL`: URL del backend + `/api`
- Guardar

### 4. ¡Listo!

Abrir la URL del frontend y probar:
- Usuario: `1234567890`
- Contraseña: `password123`

---

## ✅ Lo que se Hace Automáticamente

- ✅ Instalar dependencias PHP (Composer)
- ✅ Crear tablas en Supabase
- ✅ Insertar datos de prueba (5 usuarios, 6 terapias)
- ✅ Compilar frontend React
- ✅ Configurar SSL/HTTPS
- ✅ Configurar health checks

---

## 📖 Documentación Completa

Ver: `DEPLOY_RENDER_AUTOMATICO.md`

---

**¡Deploy en 3 pasos!** 🎉
