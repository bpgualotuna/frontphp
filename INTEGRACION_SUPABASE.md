# 🔗 Integración con Supabase PostgreSQL

## Arquitectura Implementada

```
┌─────────────────┐         ┌──────────────────┐         ┌─────────────────┐
│                 │         │                  │         │                 │
│  Frontend React │ ◄─────► │  Backend PHP API │ ◄─────► │    Supabase     │
│  (Port 5173)    │  HTTP   │  (Port 8080)     │   SQL   │   PostgreSQL    │
│                 │         │  Eloquent ORM    │         │                 │
└─────────────────┘         └──────────────────┘         └─────────────────┘
```

---

## 🎯 Componentes de la Integración

### 1. Backend PHP con Eloquent ORM

**Ubicación:** `backend/`

**Tecnologías:**
- PHP 8.1+
- Eloquent ORM (Illuminate Database)
- Slim Framework 4 (API REST)
- JWT Authentication (Firebase PHP-JWT)
- PostgreSQL Driver

**Características:**
- ✅ Conexión directa a Supabase PostgreSQL
- ✅ Modelos Eloquent para todas las entidades
- ✅ API REST completa (30+ endpoints)
- ✅ Autenticación JWT
- ✅ Middleware de autenticación
- ✅ Validaciones y reglas de negocio
- ✅ Manejo de errores centralizado

### 2. Modelos Eloquent

#### User Model (`backend/src/Models/User.php`)
```php
class User extends Model
{
    protected $table = 'users';
    
    // Relaciones
    public function citasComoPaciente(): HasMany
    public function citasComoMedico(): HasMany
    public function horariosAtencion(): HasMany
    
    // Helpers
    public function esPaciente(): bool
    public function esMedico(): bool
    public function esAdmin(): bool
}
```

#### Cita Model (`backend/src/Models/Cita.php`)
```php
class Cita extends Model
{
    protected $table = 'citas';
    
    // Relaciones
    public function paciente(): BelongsTo
    public function medico(): BelongsTo
    public function terapia(): BelongsTo
    
    // Scopes
    public function scopePendientes($query)
    public function scopeConfirmadas($query)
    public function scopeProximas($query)
}
```

#### Terapia Model (`backend/src/Models/Terapia.php`)
```php
class Terapia extends Model
{
    protected $table = 'terapias';
    
    // Relaciones
    public function citas(): HasMany
    
    // Scopes
    public function scopeActivas($query)
}
```

### 3. Configuración de Base de Datos

**Archivo:** `backend/src/Config/Database.php`

```php
$capsule->addConnection([
    'driver'    => 'pgsql',
    'host'      => 'db.hdvmdtapjqqrwcqabqck.supabase.co',
    'port'      => 5432,
    'database'  => 'postgres',
    'username'  => 'postgres',
    'password'  => 'bpg2000brayan',
    'charset'   => 'utf8',
    'prefix'    => '',
    'schema'    => 'public',
    'sslmode'   => 'prefer',
]);
```

---

## 📊 Esquema de Base de Datos

### Tabla: users

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | BIGINT | Primary Key |
| cedula | VARCHAR(20) | Número de cédula (único) |
| username | VARCHAR(100) | Nombre de usuario (único) |
| password | VARCHAR(255) | Contraseña hasheada (bcrypt) |
| full_name | VARCHAR(255) | Nombre completo |
| role | ENUM | 'paciente', 'medico', 'admin' |
| email | VARCHAR(255) | Email (nullable) |
| direccion | TEXT | Dirección (nullable) |
| edad | INTEGER | Edad (nullable) |
| sexo | ENUM | 'masculino', 'femenino', 'otro' |
| tiene_seguro | BOOLEAN | ¿Tiene seguro de salud? |
| telefono | VARCHAR(20) | Teléfono (nullable) |
| especialidad | VARCHAR(255) | Especialidad (solo médicos) |
| numero_licencia | VARCHAR(100) | Licencia médica (solo médicos) |
| calificacion | DECIMAL(3,2) | Calificación del médico |
| pacientes_atendidos | INTEGER | Contador de pacientes |
| created_at | TIMESTAMP | Fecha de creación |
| updated_at | TIMESTAMP | Fecha de actualización |

**Índices:**
- `cedula` (UNIQUE)
- `username` (UNIQUE)
- `role`

### Tabla: terapias

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | BIGINT | Primary Key |
| nombre | VARCHAR(255) | Nombre de la terapia |
| descripcion | TEXT | Descripción detallada |
| duracion | INTEGER | Duración en minutos |
| precio | DECIMAL(10,2) | Precio de la terapia |
| imagen | VARCHAR(500) | URL de la imagen |
| especialidad | VARCHAR(255) | Especialidad requerida |
| activa | BOOLEAN | ¿Está activa? |
| created_at | TIMESTAMP | Fecha de creación |
| updated_at | TIMESTAMP | Fecha de actualización |

**Índices:**
- `activa`
- `especialidad`

### Tabla: citas

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | BIGINT | Primary Key |
| paciente_id | BIGINT | FK a users |
| medico_id | BIGINT | FK a users |
| terapia_id | BIGINT | FK a terapias |
| fecha | DATE | Fecha de la cita |
| hora | TIME | Hora de la cita |
| estado | ENUM | 'pendiente', 'confirmada', 'completada', 'cancelada' |
| sintomas | TEXT | Síntomas del paciente |
| tiene_examenes | BOOLEAN | ¿Tiene exámenes? |
| examenes | JSON | Array de exámenes (nullable) |
| notas | TEXT | Notas del médico (nullable) |
| motivo_cancelacion | TEXT | Motivo de cancelación (nullable) |
| created_at | TIMESTAMP | Fecha de creación |
| updated_at | TIMESTAMP | Fecha de actualización |

**Índices:**
- `paciente_id`
- `medico_id`
- `fecha`
- `estado`
- `(medico_id, fecha, hora)` (compuesto)

**Foreign Keys:**
- `paciente_id` → `users.id` (CASCADE)
- `medico_id` → `users.id` (CASCADE)
- `terapia_id` → `terapias.id` (CASCADE)

### Tabla: horarios_atencion

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | BIGINT | Primary Key |
| medico_id | BIGINT | FK a users |
| dia_semana | INTEGER | 0=Domingo, 6=Sábado |
| hora_inicio | TIME | Hora de inicio |
| hora_fin | TIME | Hora de fin |
| created_at | TIMESTAMP | Fecha de creación |
| updated_at | TIMESTAMP | Fecha de actualización |

**Índices:**
- `medico_id`
- `dia_semana`

**Foreign Keys:**
- `medico_id` → `users.id` (CASCADE)

---

## 🔐 Autenticación JWT

### Flujo de Autenticación

1. **Login:**
   ```
   POST /api/auth/login
   Body: { cedula, password }
   
   → Backend valida credenciales
   → Genera JWT token
   → Retorna: { success, user, token }
   ```

2. **Almacenamiento:**
   ```javascript
   sessionStorage.setItem('auth_token', token);
   ```

3. **Peticiones Autenticadas:**
   ```
   GET /api/citas/paciente/1
   Headers: { Authorization: "Bearer {token}" }
   
   → Middleware valida token
   → Extrae usuario del token
   → Procesa petición
   ```

4. **Token Expirado:**
   ```
   → Backend retorna 401
   → Frontend elimina token
   → Redirige a /login
   ```

### Estructura del JWT Token

```json
{
  "iss": "http://localhost:8080",
  "iat": 1715000000,
  "exp": 1715086400,
  "sub": 1,
  "cedula": "1234567890",
  "role": "paciente"
}
```

---

## 📡 API Endpoints

### Públicos (Sin autenticación)

```
GET  /api/health
POST /api/auth/login
POST /api/auth/register
GET  /api/terapias
GET  /api/terapias/{id}
GET  /api/medicos
GET  /api/medicos/{id}
GET  /api/medicos/especialidad/{especialidad}
```

### Protegidos (Requieren JWT)

```
GET  /api/auth/me
PUT  /api/auth/profile
GET  /api/citas/paciente/{pacienteId}
GET  /api/citas/proximas/{pacienteId}
GET  /api/citas/{id}
POST /api/citas
PUT  /api/citas/{id}/cancelar
GET  /api/horarios/disponibles
```

---

## 🔄 Flujo de Datos Completo

### Ejemplo: Crear una Cita

1. **Frontend (React):**
   ```typescript
   const [createCita] = useCreateCitaMutation();
   
   await createCita({
     pacienteId: user.id,
     data: {
       medicoId: 2,
       terapiaId: 1,
       fecha: '2026-05-10',
       hora: '10:00',
       sintomas: 'Dolor en la rodilla',
       tieneExamenes: false
     }
   });
   ```

2. **API Request:**
   ```
   POST http://localhost:8080/api/citas
   Headers: {
     Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
     Content-Type: application/json
   }
   Body: {
     pacienteId: 1,
     medicoId: 2,
     terapiaId: 1,
     fecha: "2026-05-10",
     hora: "10:00",
     sintomas: "Dolor en la rodilla",
     tieneExamenes: false
   }
   ```

3. **Backend PHP (CitaController):**
   ```php
   // Validar token (AuthMiddleware)
   // Validar datos
   // Verificar disponibilidad
   
   $cita = Cita::create([
     'paciente_id' => $data['pacienteId'],
     'medico_id' => $data['medicoId'],
     'terapia_id' => $data['terapiaId'],
     'fecha' => $data['fecha'],
     'hora' => $data['hora'],
     'estado' => 'pendiente',
     'sintomas' => $data['sintomas'],
     'tiene_examenes' => $data['tieneExamenes']
   ]);
   ```

4. **Supabase PostgreSQL:**
   ```sql
   INSERT INTO citas (
     paciente_id, medico_id, terapia_id,
     fecha, hora, estado, sintomas, tiene_examenes,
     created_at, updated_at
   ) VALUES (
     1, 2, 1,
     '2026-05-10', '10:00', 'pendiente', 'Dolor en la rodilla', false,
     NOW(), NOW()
   ) RETURNING *;
   ```

5. **Response:**
   ```json
   {
     "success": true,
     "data": {
       "id": 5,
       "paciente_id": 1,
       "medico_id": 2,
       "terapia_id": 1,
       "fecha": "2026-05-10",
       "hora": "10:00",
       "estado": "pendiente",
       "sintomas": "Dolor en la rodilla",
       "tiene_examenes": false,
       "created_at": "2026-05-06T10:30:00Z",
       "updated_at": "2026-05-06T10:30:00Z"
     },
     "message": "Cita creada exitosamente"
   }
   ```

---

## 🛠️ Migraciones

**Archivo:** `backend/migrations/create_tables.php`

Ejecutar:
```bash
php migrations/create_tables.php
```

**Acciones:**
1. Crea todas las tablas
2. Configura foreign keys
3. Crea índices
4. Inserta datos de prueba:
   - 5 usuarios (1 paciente, 3 médicos, 1 admin)
   - 6 terapias

---

## 🔒 Seguridad

### Contraseñas
- Hasheadas con `password_hash()` (bcrypt)
- Verificadas con `password_verify()`

### JWT Tokens
- Firmados con HS256
- Expiración: 24 horas
- Secret key configurable en `.env`

### SQL Injection
- Protegido por Eloquent ORM (prepared statements)

### CORS
- Configurado para permitir solo orígenes específicos
- Headers permitidos: Authorization, Content-Type

---

## 📈 Ventajas de esta Arquitectura

✅ **Separación de Responsabilidades:**
- Frontend: UI/UX
- Backend: Lógica de negocio
- Supabase: Persistencia de datos

✅ **Escalabilidad:**
- Backend PHP puede escalar horizontalmente
- Supabase maneja la escalabilidad de la BD

✅ **Seguridad:**
- Credenciales de BD solo en el backend
- JWT para autenticación stateless
- Validaciones en backend y frontend

✅ **Mantenibilidad:**
- Eloquent ORM facilita cambios en el esquema
- API REST estándar
- Código modular y organizado

✅ **Performance:**
- Eloquent con eager loading (with())
- Índices en columnas frecuentes
- Conexión persistente a PostgreSQL

---

## 🚀 Próximos Pasos

### Mejoras Sugeridas

1. **Caching:**
   - Redis para cachear terapias y médicos
   - Reducir carga en Supabase

2. **File Upload:**
   - Supabase Storage para exámenes médicos
   - Integrar con el modelo Cita

3. **Notificaciones:**
   - Email con SendGrid/Mailgun
   - SMS con Twilio
   - Push notifications

4. **Reportes:**
   - Generar PDFs de citas
   - Estadísticas para admin

5. **Testing:**
   - PHPUnit para backend
   - Jest para frontend

---

**Documentación completa de la integración PHP + Eloquent ORM + Supabase PostgreSQL** ✨
