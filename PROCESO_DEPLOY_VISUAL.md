# 🎨 Proceso de Deploy Visual

## Diagrama del Proceso Completo

---

## 📊 Flujo de Deploy

```
┌─────────────────────────────────────────────────────────────────────┐
│                         TU COMPUTADORA                              │
└─────────────────────────────────────────────────────────────────────┘
                              │
                              │ git push
                              ↓
┌─────────────────────────────────────────────────────────────────────┐
│                            GITHUB                                   │
│                                                                     │
│  📁 Repositorio: gestion-citas-medicas                              │
│     ├── backend/                                                    │
│     ├── src/                                                        │
│     ├── render.yaml  ← Archivo clave                                │
│     └── ...                                                         │
└─────────────────────────────────────────────────────────────────────┘
                              │
                              │ Render detecta cambios
                              ↓
┌─────────────────────────────────────────────────────────────────────┐
│                            RENDER                                   │
│                                                                     │
│  🔍 Lee render.yaml                                                 │
│  📦 Crea 2 servicios automáticamente:                               │
│                                                                     │
│  ┌────────────────────────────────────────────────────────────┐    │
│  │  BACKEND (Web Service)                                     │    │
│  ├────────────────────────────────────────────────────────────┤    │
│  │  1. Ejecuta: backend/build.sh                              │    │
│  │     ├── composer install                                   │    │
│  │     └── php migrations/create_tables.php ← MIGRACIONES     │    │
│  │                                                            │    │
│  │  2. Ejecuta: backend/start.sh                              │    │
│  │     └── php -S 0.0.0.0:$PORT                               │    │
│  │                                                            │    │
│  │  3. URL: https://gestion-citas-backend.onrender.com       │    │
│  └────────────────────────────────────────────────────────────┘    │
│                              │                                      │
│                              │ Conecta con                          │
│                              ↓                                      │
│  ┌────────────────────────────────────────────────────────────┐    │
│  │  FRONTEND (Static Site)                                    │    │
│  ├────────────────────────────────────────────────────────────┤    │
│  │  1. Ejecuta: npm install                                   │    │
│  │  2. Ejecuta: npm run build                                 │    │
│  │  3. Publica: dist/                                         │    │
│  │                                                            │    │
│  │  4. URL: https://gestion-citas-frontend.onrender.com      │    │
│  └────────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────────┘
                              │
                              │ Backend conecta con
                              ↓
┌─────────────────────────────────────────────────────────────────────┐
│                          SUPABASE                                   │
│                                                                     │
│  🗄️ PostgreSQL Database                                             │
│                                                                     │
│  Durante el deploy, las migraciones crean:                          │
│  ✅ Tabla: users (5 usuarios)                                       │
│  ✅ Tabla: terapias (6 terapias)                                    │
│  ✅ Tabla: citas                                                    │
│  ✅ Tabla: horarios_atencion                                        │
│                                                                     │
│  Host: db.hdvmdtapjqqrwcqabqck.supabase.co                          │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 🔄 Línea de Tiempo del Deploy

```
┌─────────────────────────────────────────────────────────────────────┐
│                      LÍNEA DE TIEMPO                                │
└─────────────────────────────────────────────────────────────────────┘

Minuto 0:00
  │
  ├─ Subes código a GitHub
  │  └─ git push
  │
Minuto 0:30
  │
  ├─ Conectas Render con GitHub
  │  └─ Seleccionas repositorio
  │
Minuto 1:00
  │
  ├─ Render detecta render.yaml
  │  └─ Crea servicios automáticamente
  │
Minuto 1:30
  │
  ├─ BACKEND: Inicia build
  │  ├─ Instala dependencias PHP (2 min)
  │  ├─ Ejecuta migraciones (30 seg) ← AQUÍ SE CREAN LAS TABLAS
  │  └─ Inicia servidor (10 seg)
  │
Minuto 4:00
  │
  ├─ BACKEND: ✅ Desplegado
  │  └─ URL: https://...backend.onrender.com
  │
Minuto 4:30
  │
  ├─ FRONTEND: Inicia build
  │  ├─ Instala dependencias Node (2 min)
  │  ├─ Compila React (1 min)
  │  └─ Publica archivos (30 seg)
  │
Minuto 8:00
  │
  ├─ FRONTEND: ✅ Desplegado
  │  └─ URL: https://...frontend.onrender.com
  │
Minuto 8:30
  │
  ├─ Actualizas variables de entorno
  │  ├─ Backend: CORS_ALLOWED_ORIGINS
  │  └─ Frontend: VITE_API_BASE_URL
  │
Minuto 10:00
  │
  └─ 🎉 ¡SISTEMA COMPLETO FUNCIONANDO!
```

---

## 📦 Qué se Instala Automáticamente

### Backend PHP

```
composer install
  ├─ illuminate/database (Eloquent ORM)
  ├─ illuminate/events
  ├─ vlucas/phpdotenv
  ├─ firebase/php-jwt
  ├─ slim/slim
  ├─ slim/psr7
  └─ tuupola/cors-middleware
```

### Frontend React

```
npm install
  ├─ react
  ├─ react-dom
  ├─ @mui/material
  ├─ @reduxjs/toolkit
  ├─ react-router-dom
  ├─ axios
  └─ ... (15+ paquetes)
```

---

## 🗄️ Migraciones Automáticas

Durante el deploy, el script `backend/build.sh` ejecuta:

```bash
php migrations/create_tables.php
```

Esto crea:

```
┌─────────────────────────────────────────────────────────────────────┐
│                    SUPABASE POSTGRESQL                              │
└─────────────────────────────────────────────────────────────────────┘

📋 Tabla: users
   ├─ 5 registros insertados
   ├─ 1 Paciente: 1234567890 / password123
   ├─ 3 Médicos: 0987654321, 1122334455, 5566778899 / medico123
   └─ 1 Admin: admin / admin123

📋 Tabla: terapias
   ├─ 6 registros insertados
   ├─ Fisioterapia General ($45)
   ├─ Terapia Ocupacional ($50)
   ├─ Terapia Psicológica ($60)
   ├─ Rehabilitación Deportiva ($65)
   ├─ Terapia de Lenguaje ($55)
   └─ Masoterapia ($40)

📋 Tabla: citas
   └─ 0 registros (vacía, lista para usar)

📋 Tabla: horarios_atencion
   └─ 0 registros (vacía, lista para usar)
```

---

## 🔐 Variables de Entorno

### Backend

```
DB_CONNECTION=pgsql
DB_HOST=db.hdvmdtapjqqrwcqabqck.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=bpg2000brayan

SUPABASE_URL=https://hdvmdtapjqqrwcqabqck.supabase.co
SUPABASE_PUBLISHABLE_KEY=sb_publishable_yNAiQg52qxw25Q9aYvltUw_2C6nDX6FSi

JWT_SECRET=auto-generado-por-render
JWT_EXPIRATION=86400

APP_ENV=production
APP_DEBUG=false
APP_URL=https://gestion-citas-backend.onrender.com

CORS_ALLOWED_ORIGINS=https://gestion-citas-frontend.onrender.com
```

### Frontend

```
VITE_API_BASE_URL=https://gestion-citas-backend.onrender.com/api
VITE_APP_NAME=Sistema de Gestión Médica
VITE_SUPABASE_URL=https://hdvmdtapjqqrwcqabqck.supabase.co
VITE_SUPABASE_PUBLISHABLE_KEY=sb_publishable_yNAiQg52qxw25Q9aYvltUw_2C6nDX6FSi
```

---

## ✅ Checklist Visual

```
┌─────────────────────────────────────────────────────────────────────┐
│                         CHECKLIST                                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  PREPARACIÓN                                                        │
│  [ ] Código en GitHub                                               │
│  [ ] Cuenta en Render                                               │
│                                                                     │
│  DEPLOY                                                             │
│  [ ] Blueprint aplicado                                             │
│  [ ] Backend desplegado (5-7 min)                                   │
│  [ ] Frontend desplegado (3-5 min)                                  │
│                                                                     │
│  CONFIGURACIÓN                                                      │
│  [ ] URLs copiadas                                                  │
│  [ ] Variables de entorno actualizadas                              │
│  [ ] Servicios redesplegados                                        │
│                                                                     │
│  VERIFICACIÓN                                                       │
│  [ ] Health check funciona                                          │
│  [ ] Login funciona                                                 │
│  [ ] Se pueden ver terapias                                         │
│  [ ] Se puede crear una cita                                        │
│                                                                     │
│  BASE DE DATOS                                                      │
│  [ ] 5 usuarios creados                                             │
│  [ ] 6 terapias creadas                                             │
│  [ ] Tablas visibles en Supabase                                    │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 🎯 URLs Finales

```
┌─────────────────────────────────────────────────────────────────────┐
│                         TUS URLs                                    │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Frontend (Usuarios):                                               │
│  🌐 https://gestion-citas-frontend-XXXX.onrender.com               │
│                                                                     │
│  Backend API:                                                       │
│  🔌 https://gestion-citas-backend-XXXX.onrender.com/api            │
│                                                                     │
│  Health Check:                                                      │
│  ✅ https://gestion-citas-backend-XXXX.onrender.com/api/health     │
│                                                                     │
│  Supabase Dashboard:                                                │
│  🗄️ https://supabase.com/dashboard                                 │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 💰 Costos

```
┌─────────────────────────────────────────────────────────────────────┐
│                         PLAN FREE                                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Backend:              $0/mes                                       │
│  Frontend:             $0/mes                                       │
│  Base de Datos:        $0/mes                                       │
│  ─────────────────────────────                                      │
│  TOTAL:                $0/mes 🎉                                    │
│                                                                     │
│  Limitaciones:                                                      │
│  • Backend se duerme después de 15 min                              │
│  • Primera carga: ~30 segundos                                      │
│  • 750 horas/mes (suficiente para desarrollo)                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 🎉 Resultado Final

```
┌─────────────────────────────────────────────────────────────────────┐
│                                                                     │
│                    ✅ SISTEMA DESPLEGADO                            │
│                                                                     │
│  • Backend PHP con Eloquent ORM                                     │
│  • Frontend React profesional                                       │
│  • Base de datos PostgreSQL en Supabase                             │
│  • API REST completa (15 endpoints)                                 │
│  • SSL/HTTPS automático                                             │
│  • 5 usuarios de prueba                                             │
│  • 6 terapias disponibles                                           │
│  • Migraciones ejecutadas automáticamente                           │
│                                                                     │
│                    🚀 LISTO PARA USAR                               │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

**¡Todo el proceso visualizado!** 🎨✨
