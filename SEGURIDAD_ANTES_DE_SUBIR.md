# 🔒 Checklist de Seguridad - Antes de Subir a GitHub

## ⚠️ IMPORTANTE: Leer antes de hacer `git push`

---

## ✅ Archivos Seguros para Subir

Estos archivos **NO contienen credenciales reales** y es seguro subirlos:

- ✅ `.env.example` - Template sin credenciales
- ✅ `backend/.env.example` - Template sin credenciales
- ✅ `.env.production` - Template sin credenciales
- ✅ `render.yaml` - Configuración de Render (sin credenciales)
- ✅ Todos los archivos `.md` de documentación
- ✅ Código fuente (`.ts`, `.tsx`, `.php`)

---

## ❌ Archivos que NO se Deben Subir

Estos archivos están en `.gitignore` y **NO se subirán**:

- ❌ `.env` - Contiene credenciales locales
- ❌ `backend/.env` - Contiene credenciales de Supabase
- ❌ `CREDENCIALES_SUPABASE.md` - Contiene todas las credenciales
- ❌ `node_modules/` - Dependencias de Node
- ❌ `backend/vendor/` - Dependencias de PHP

---

## 🔍 Verificación Antes de Subir

### Paso 1: Verificar .gitignore

```bash
# Ver qué archivos se van a subir
git status

# Verificar que NO aparezcan:
# - .env
# - backend/.env
# - CREDENCIALES_SUPABASE.md
```

### Paso 2: Verificar Contenido de Archivos

```bash
# Verificar que .env.example NO tenga credenciales reales
cat .env.example

# Verificar que backend/.env.example NO tenga credenciales reales
cat backend/.env.example

# Verificar que .env.production NO tenga credenciales reales
cat .env.production
```

**Deberías ver:**
- ✅ `your_supabase_url_here`
- ✅ `your_supabase_publishable_key_here`
- ✅ `your_database_password_here`

**NO deberías ver:**
- ❌ `db.hdvmdtapjqqrwcqabqck.supabase.co`
- ❌ `sb_publishable_yNAiQg52qxw25Q9aYvltUw_2C6nDX6FSi`
- ❌ `bpg2000brayan`

### Paso 3: Verificar render.yaml

```bash
# Ver el contenido de render.yaml
cat render.yaml
```

**Verificar que las credenciales estén como:**
- ✅ `sync: false` (se configurarán en Render)
- ✅ `generateValue: true` (Render las genera)

---

## 🚀 Comandos Seguros para Subir

Una vez verificado todo:

```bash
# 1. Ver qué se va a subir
git status

# 2. Agregar archivos (el .gitignore protege los sensibles)
git add .

# 3. Verificar nuevamente
git status

# 4. Hacer commit
git commit -m "Deploy inicial - sin credenciales"

# 5. Subir a GitHub
git push -u origin main
```

---

## 🔐 Dónde Están las Credenciales Reales

### Localmente (NO se suben):

1. **`backend/.env`** - Credenciales de Supabase
2. **`CREDENCIALES_SUPABASE.md`** - Backup de credenciales

Estos archivos están en `.gitignore` y **NO se subirán a GitHub**.

### En Render (Se configuran manualmente):

Las credenciales se configurarán directamente en el dashboard de Render:
- Dashboard → Tu servicio → Environment → Add Environment Variable

---

## ⚠️ Si Accidentalmente Subiste Credenciales

### Opción 1: Eliminar el commit (si no has hecho push)

```bash
git reset HEAD~1
```

### Opción 2: Si ya hiciste push

1. **Cambiar las credenciales en Supabase:**
   - Ir a Supabase Dashboard
   - Regenerar las claves
   - Actualizar en Render

2. **Limpiar el historial de Git:**
   ```bash
   # Usar BFG Repo-Cleaner o git filter-branch
   # Consultar: https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/removing-sensitive-data-from-a-repository
   ```

---

## ✅ Checklist Final

Antes de hacer `git push`, verificar:

```
[ ] .env NO está en git status
[ ] backend/.env NO está en git status
[ ] CREDENCIALES_SUPABASE.md NO está en git status
[ ] .env.example NO contiene credenciales reales
[ ] backend/.env.example NO contiene credenciales reales
[ ] .env.production NO contiene credenciales reales
[ ] render.yaml usa sync: false para credenciales
[ ] .gitignore incluye todos los archivos sensibles
```

---

## 📝 Resumen

### ✅ Seguro Subir:
- Código fuente
- Archivos `.example`
- Documentación
- `render.yaml` (sin credenciales hardcodeadas)

### ❌ NO Subir:
- Archivos `.env` con credenciales reales
- `CREDENCIALES_SUPABASE.md`
- Dependencias (`node_modules`, `vendor`)

---

**¡Verifica todo antes de hacer push!** 🔒✨
