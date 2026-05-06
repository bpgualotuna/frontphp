# 🔒 Deploy Seguro en Render

## Guía Actualizada con Seguridad

---

## ✅ Archivos Seguros

Todos los archivos de configuración han sido limpiados y **NO contienen credenciales reales**:

- ✅ `.env.example` - Template limpio
- ✅ `backend/.env.example` - Template limpio
- ✅ `.env.production` - Template limpio
- ✅ `render.yaml` - Usa `sync: false` para credenciales

---

## 🔐 Credenciales Reales

Las credenciales reales están en:
- **`backend/.env`** (local, NO se sube)
- **`CREDENCIALES_SUPABASE.md`** (backup, NO se sube)

Ambos archivos están en `.gitignore` y **NO se subirán a GitHub**.

---

## 🚀 Pasos para Deploy Seguro

### PASO 1: Verificar Seguridad

```bash
# Ver qué archivos se van a subir
git status

# Verificar que NO aparezcan:
# - .env
# - backend/.env
# - CREDENCIALES_SUPABASE.md

# Verificar contenido de archivos públicos
cat .env.example
cat backend/.env.example
cat .env.production

# Deberías ver "your_supabase_url_here" y NO URLs reales
```

### PASO 2: Subir a GitHub

```bash
git init
git add .
git commit -m "Deploy inicial - configuración segura"
git remote add origin https://github.com/TU-USUARIO/gestion-citas-medicas.git
git push -u origin main
```

### PASO 3: Configurar Render

1. **Ir a:** https://dashboard.render.com/
2. **New +** → **Blueprint**
3. **Seleccionar repositorio**
4. **Configurar Variables de Entorno Manualmente**

---

## 🔧 Variables de Entorno en Render

### Backend Service

Render te pedirá configurar estas variables (usa las de `CREDENCIALES_SUPABASE.md`):

```
DB_HOST=db.hdvmdtapjqqrwcqabqck.supabase.co
DB_PASSWORD=bpg2000brayan
SUPABASE_URL=https://hdvmdtapjqqrwcqabqck.supabase.co
SUPABASE_PUBLISHABLE_KEY=sb_publishable_yNAiQg52qxw25Q9aYvltUw_2C6nDX6FSi
APP_URL=(dejar vacío, se llenará después)
CORS_ALLOWED_ORIGINS=(dejar vacío, se llenará después)
```

### Frontend Service

```
VITE_API_BASE_URL=(dejar vacío, se llenará después)
VITE_SUPABASE_URL=https://hdvmdtapjqqrwcqabqck.supabase.co
VITE_SUPABASE_PUBLISHABLE_KEY=sb_publishable_yNAiQg52qxw25Q9aYvltUw_2C6nDX6FSi
```

### PASO 4: Aplicar Blueprint

1. **Click en "Apply"**
2. **Esperar el deploy** (~10 minutos)
3. **Copiar las URLs** que Render te da

### PASO 5: Actualizar URLs

Una vez desplegado:

**Backend:**
- Editar `APP_URL`: URL del backend
- Editar `CORS_ALLOWED_ORIGINS`: URL del frontend

**Frontend:**
- Editar `VITE_API_BASE_URL`: URL del backend + `/api`

---

## ✅ Verificación de Seguridad

### Antes de hacer push:

```bash
# Verificar .gitignore
cat .gitignore | grep ".env"

# Debe mostrar:
# .env
# backend/.env
# CREDENCIALES_SUPABASE.md

# Verificar que archivos .example NO tengan credenciales
grep -r "bpg2000brayan" .env.example backend/.env.example .env.production

# NO debe encontrar nada
```

---

## 🐛 Si Accidentalmente Subiste Credenciales

### Solución Inmediata:

1. **Cambiar credenciales en Supabase:**
   - Dashboard → Settings → Database → Reset password
   - Dashboard → Settings → API → Regenerate keys

2. **Actualizar en Render:**
   - Editar variables de entorno con las nuevas credenciales

3. **Limpiar historial de Git:**
   ```bash
   # Eliminar archivo del historial
   git filter-branch --force --index-filter \
     "git rm --cached --ignore-unmatch backend/.env" \
     --prune-empty --tag-name-filter cat -- --all
   
   # Forzar push
   git push origin --force --all
   ```

---

## 📋 Checklist Final

```
[ ] .env NO está en git status
[ ] backend/.env NO está en git status
[ ] CREDENCIALES_SUPABASE.md NO está en git status
[ ] .env.example NO contiene credenciales reales
[ ] backend/.env.example NO contiene credenciales reales
[ ] .env.production NO contiene credenciales reales
[ ] render.yaml usa sync: false
[ ] Tengo backup de credenciales en CREDENCIALES_SUPABASE.md
```

---

## 📚 Documentación Relacionada

- **`SEGURIDAD_ANTES_DE_SUBIR.md`** - Checklist detallado
- **`CREDENCIALES_SUPABASE.md`** - Backup de credenciales (NO subir)
- **`DEPLOY_FACIL.md`** - Guía de deploy simplificada

---

**¡Deploy seguro sin exponer credenciales!** 🔒✨
