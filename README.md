# Sistema de Gestión de Citas Médicas

Sistema profesional completo de gestión de pacientes y citas médicas con **Frontend React + Backend PHP + Supabase PostgreSQL**.

## 🏥 Características

- ✅ **Autenticación completa** con login y registro (JWT)
- ✅ **Backend PHP** con Eloquent ORM
- ✅ **Base de datos PostgreSQL** en Supabase
- ✅ **API REST** completa (30+ endpoints)
- ✅ **Gestión de citas médicas** con flujo de 3 pasos
- ✅ **Selección de terapias** con cards visuales
- ✅ **Calendario de disponibilidad** de médicos
- ✅ **Formulario médico** con síntomas y exámenes
- ✅ **Dashboard interactivo** con estadísticas
- ✅ **Gestión de perfil** de usuario
- ✅ **Sistema de roles** (Paciente, Médico, Admin)
- ✅ **Diseño responsive** adaptado a móviles y desktop

## 🎨 Arquitectura

```
┌─────────────────┐         ┌──────────────────┐         ┌─────────────────┐
│                 │         │                  │         │                 │
│  Frontend React │ ◄─────► │  Backend PHP API │ ◄─────► │    Supabase     │
│  (Port 5173)    │  HTTP   │  (Port 8080)     │   SQL   │   PostgreSQL    │
│                 │         │  Eloquent ORM    │         │                 │
└─────────────────┘         └──────────────────┘         └─────────────────┘
```

### Stack Tecnológico

#### Frontend
- **React 19.2.0** con TypeScript
- **Vite 6.3.5** como build tool
- **Material-UI (MUI) 7.3.7** para componentes UI
- **Redux Toolkit 2.11.2** + RTK Query para estado global
- **React Router DOM 7.13.0** para navegación
- **React Hook Form 7.71.1** + Zod 4.3.6 para formularios
- **Axios 1.13.3** para peticiones HTTP
- **SweetAlert2 11.26.24** para notificaciones

#### Backend
- **PHP 8.1+**
- **Eloquent ORM** (Illuminate Database 10.0)
- **Slim Framework 4.12** (API REST)
- **JWT Authentication** (Firebase PHP-JWT 6.8)
- **PostgreSQL** (Supabase)
- **Composer** para dependencias

### Estructura del Proyecto
```
src/
├── app/                      # Configuración de la aplicación
│   ├── theme/               # Sistema de estilos MUI
│   │   ├── colors.ts        # Paleta de colores médicos
│   │   ├── typography.ts    # Configuración tipográfica
│   │   └── index.ts         # Tema MUI completo
│   ├── store.ts             # Store de Redux
│   ├── router.tsx           # Configuración de rutas
│   └── axiosClient.ts       # Cliente HTTP con interceptores
├── components/              # Componentes reutilizables
│   ├── auth/                # Componentes de autenticación
│   │   ├── ProtectedRoute.tsx
│   │   └── RoleGuard.tsx
│   └── layout/              # Componentes de layout
│       └── MainLayout.tsx   # Layout principal con sidebar
├── contexts/                # Contextos React
│   └── AuthContext.tsx      # Contexto de autenticación
├── pages/                   # Páginas de la aplicación
│   ├── auth/                # Login y Registro
│   ├── dashboard/           # Dashboard principal
│   ├── therapies/           # Selección de terapias
│   ├── appointments/        # Flujo de reserva de citas
│   ├── citas/               # Gestión de citas
│   └── profile/             # Perfil de usuario
├── services/                # Servicios API (RTK Query)
│   ├── authService.ts       # Servicio de autenticación
│   ├── citasApi.ts          # API de citas
│   ├── terapiasApi.ts       # API de terapias
│   ├── medicosApi.ts        # API de médicos
│   └── mocks/               # Datos mock para desarrollo
├── types/                   # Tipos TypeScript
│   └── index.ts             # Definiciones de tipos
└── utils/                   # Utilidades
```

## 🚀 Instalación y Ejecución

### Prerrequisitos
- **Node.js 18+** y npm
- **PHP 8.1+**
- **Composer**

### 📖 Guía Completa de Instalación

**Ver:** [`GUIA_INSTALACION.md`](GUIA_INSTALACION.md) para instrucciones detalladas paso a paso.

### Instalación Rápida

#### 1. Backend PHP

```bash
cd backend
composer install
php migrations/create_tables.php
cd public
php -S localhost:8080
```

#### 2. Frontend React

```bash
# En otra terminal, desde la raíz
npm install
cp .env.example .env
npm run dev
```

#### 3. Acceder al Sistema

- **Frontend:** http://localhost:5173
- **Backend API:** http://localhost:8080/api
- **Health Check:** http://localhost:8080/api/health

### Scripts Disponibles

#### Frontend
```bash
npm run dev          # Ejecutar en modo desarrollo
npm run build        # Compilar para producción
npm run build:check  # Compilar con verificación de tipos
npm run preview      # Previsualizar build de producción
npm run lint         # Ejecutar linter
```

#### Backend
```bash
cd backend
composer install                    # Instalar dependencias
php migrations/create_tables.php    # Crear tablas en Supabase
cd public && php -S localhost:8080  # Iniciar servidor
```

## 👤 Credenciales de Prueba

### Paciente
- **Usuario:** `1234567890`
- **Contraseña:** `password123`

### Médico
- **Usuario:** `0987654321`
- **Contraseña:** `medico123`

### Administrador
- **Usuario:** `admin`
- **Contraseña:** `admin123`

## 🎯 Flujo de Usuario (Paciente)

1. **Login/Registro**
   - Iniciar sesión con cédula y contraseña
   - O registrarse como nuevo paciente

2. **Dashboard**
   - Ver próximas citas
   - Estadísticas rápidas
   - Accesos directos

3. **Reservar Cita**
   - **Paso 1:** Seleccionar terapia
   - **Paso 2:** Elegir fecha y hora en calendario
   - **Paso 3:** Completar formulario médico (síntomas, exámenes)
   - **Paso 4:** Confirmar y crear cita

4. **Gestionar Citas**
   - Ver todas las citas (próximas, completadas, canceladas)
   - Cancelar citas pendientes
   - Ver detalles de cada cita

5. **Perfil**
   - Ver información personal
   - Actualizar datos de contacto

## 🎨 Sistema de Diseño

### Paleta de Colores
- **Primary:** #2196F3 (Azul médico profesional)
- **Secondary:** #00897B (Verde salud)
- **Estados de Citas:**
  - Pendiente: #FFA726 (Naranja)
  - Confirmada: #66BB6A (Verde)
  - Completada: #42A5F5 (Azul)
  - Cancelada: #EF5350 (Rojo)

### Componentes Principales
- **Cards:** Diseño elevado con sombras suaves
- **Botones:** Bordes redondeados, sin mayúsculas
- **Formularios:** Validación con Zod y React Hook Form
- **Notificaciones:** SweetAlert2 para mensajes importantes

## 📱 Responsive Design

El sistema está completamente adaptado para:
- 📱 **Móviles** (xs: 0-599px)
- 📱 **Tablets** (sm: 600-899px)
- 💻 **Desktop** (md: 900px+)

## 🔐 Autenticación y Seguridad

- **JWT Tokens** almacenados en sessionStorage
- **ProtectedRoute** para rutas privadas
- **RoleGuard** para control de acceso por roles
- **Interceptores Axios** para agregar tokens automáticamente
- **Manejo de sesiones expiradas** con redirección automática

## 🗂️ Gestión de Estado

### Redux Toolkit + RTK Query
- **citasApi:** Gestión de citas (CRUD, horarios disponibles)
- **terapiasApi:** Catálogo de terapias
- **medicosApi:** Información de médicos

### Context API
- **AuthContext:** Autenticación y usuario actual

## 📄 Documentación

### Documentación Principal
- **[GUIA_INSTALACION.md](GUIA_INSTALACION.md)** - Guía completa de instalación paso a paso
- **[IMPLEMENTACION_COMPLETADA.md](IMPLEMENTACION_COMPLETADA.md)** - Resumen de todo lo implementado
- **[INTEGRACION_SUPABASE.md](INTEGRACION_SUPABASE.md)** - Documentación técnica de la integración

### Documentación del Backend
- **[backend/README.md](backend/README.md)** - Documentación completa del backend PHP
- **[backend/COMANDOS_UTILES.md](backend/COMANDOS_UTILES.md)** - Comandos útiles para desarrollo
- **[BACKEND_TECHNICAL_SPECIFICATION.md](BACKEND_TECHNICAL_SPECIFICATION.md)** - Especificaciones técnicas

## 🗄️ Base de Datos

### Supabase PostgreSQL

**Credenciales:**
- Host: `db.hdvmdtapjqqrwcqabqck.supabase.co`
- Database: `postgres`
- Port: `5432`
- Project URL: `https://hdvmdtapjqqrwcqabqck.supabase.co`

### Tablas Creadas
- ✅ `users` - Usuarios (pacientes, médicos, admin)
- ✅ `terapias` - Catálogo de terapias
- ✅ `citas` - Registro de citas médicas
- ✅ `horarios_atencion` - Horarios de médicos

### Datos de Prueba
- 5 usuarios (1 paciente, 3 médicos, 1 admin)
- 6 terapias con precios
- Relaciones configuradas con foreign keys

## 🛠️ Tecnologías y Patrones

### Patrones Implementados
- **Component-Based Architecture**
- **Context API** para estado global de autenticación
- **RTK Query** para cache y sincronización de datos
- **Lazy Loading** de páginas para mejor performance
- **Protected Routes** con guards de autenticación y roles
- **Form Validation** con Zod schemas
- **Responsive Design** con Material-UI breakpoints

### Mejores Prácticas
- ✅ TypeScript estricto para type safety
- ✅ Componentes funcionales con hooks
- ✅ Separación de concerns (UI, lógica, datos)
- ✅ Código modular y reutilizable
- ✅ Manejo centralizado de errores
- ✅ Validación de formularios robusta

## 📚 Recursos Adicionales

- [Documentación de React](https://react.dev/)
- [Material-UI](https://mui.com/)
- [Redux Toolkit](https://redux-toolkit.js.org/)
- [React Hook Form](https://react-hook-form.com/)
- [Zod](https://zod.dev/)

## 🤝 Contribución

Este proyecto fue generado siguiendo las especificaciones técnicas del documento base, adaptado al dominio de gestión médica.

## 🔐 API REST

### Endpoints Públicos
```
GET  /api/health                    # Health check
POST /api/auth/login                # Login
POST /api/auth/register             # Registro
GET  /api/terapias                  # Listar terapias
GET  /api/medicos                   # Listar médicos
```

### Endpoints Protegidos (Requieren JWT)
```
GET  /api/auth/me                   # Usuario actual
PUT  /api/auth/profile              # Actualizar perfil
GET  /api/citas/paciente/{id}       # Citas del paciente
POST /api/citas                     # Crear cita
PUT  /api/citas/{id}/cancelar       # Cancelar cita
GET  /api/horarios/disponibles      # Horarios disponibles
```

Ver documentación completa en [`backend/README.md`](backend/README.md)

## 📝 Notas Importantes

1. **Backend Funcional:** El sistema ahora tiene un backend PHP completo conectado a Supabase PostgreSQL.

2. **Eloquent ORM:** Se usa Eloquent para todas las operaciones de base de datos, con modelos, relaciones y scopes.

3. **JWT Authentication:** Sistema de autenticación seguro con tokens JWT que expiran en 24 horas.

4. **Validaciones:** Implementadas tanto en frontend (Zod) como en backend (PHP).

5. **Roles:** El sistema soporta 3 roles (paciente, médico, admin). El flujo de paciente está completamente implementado.

## 🎓 Frase Inspiradora

> "Lo importante no es poder, lo importante es intentar"

---

**Desarrollado con ❤️ siguiendo arquitectura profesional y mejores prácticas**
