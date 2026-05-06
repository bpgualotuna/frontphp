# ✅ Verificación del Sistema

## Guía para verificar que todo está funcionando correctamente

---

## 🎯 Objetivo

Asegurarse de que el sistema completo (Backend + Frontend + Base de Datos) está funcionando correctamente.

---

## 📋 Checklist de Verificación

### ✅ 1. Verificar Instalación de Dependencias

#### Backend PHP

```bash
cd backend
composer --version
php --version
```

**Resultado esperado:**
```
Composer version 2.x.x
PHP 8.1.x (cli)
```

#### Frontend React

```bash
node --version
npm --version
```

**Resultado esperado:**
```
v18.x.x o superior
9.x.x o superior
```

---

### ✅ 2. Verificar Base de Datos en Supabase

#### Opción 1: Desde el código

```bash
cd backend
php -r "
require 'vendor/autoload.php';
\$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
\$dotenv->load();
App\Config\Database::init();

\$users = App\Models\User::count();
\$terapias = App\Models\Terapia::count();
\$citas = App\Models\Cita::count();

echo 'Usuarios: ' . \$users . PHP_EOL;
echo 'Terapias: ' . \$terapias . PHP_EOL;
echo 'Citas: ' . \$citas . PHP_EOL;
"
```

**Resultado esperado:**
```
Usuarios: 5
Terapias: 6
Citas: 0 (o más si ya creaste citas)
```

#### Opción 2: Desde Supabase Dashboard

1. Ir a: https://supabase.com/dashboard
2. Seleccionar tu proyecto
3. Ir a "Table Editor"
4. Verificar que existen las tablas:
   - ✅ users
   - ✅ terapias
   - ✅ citas
   - ✅ horarios_atencion

---

### ✅ 3. Verificar Backend API

#### 3.1 Iniciar el servidor

```bash
cd backend/public
php -S localhost:8080
```

**Resultado esperado:**
```
PHP 8.1.x Development Server (http://localhost:8080) started
```

#### 3.2 Probar Health Check

**Opción A: Navegador**
```
http://localhost:8080/api/health
```

**Opción B: curl**
```bash
curl http://localhost:8080/api/health
```

**Resultado esperado:**
```json
{
  "status": "ok",
  "message": "API funcionando correctamente",
  "timestamp": "2026-05-06 10:30:00"
}
```

#### 3.3 Probar Login

```bash
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"cedula":"1234567890","password":"password123"}'
```

**Resultado esperado:**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "cedula": "1234567890",
    "full_name": "Juan Pérez García",
    "role": "paciente",
    ...
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "message": "Inicio de sesión exitoso"
}
```

#### 3.4 Probar Endpoints Públicos

```bash
# Listar terapias
curl http://localhost:8080/api/terapias

# Listar médicos
curl http://localhost:8080/api/medicos

# Obtener terapia específica
curl http://localhost:8080/api/terapias/1

# Obtener médico específico
curl http://localhost:8080/api/medicos/2
```

**Resultado esperado:** JSON con los datos correspondientes

#### 3.5 Probar Endpoints Protegidos

```bash
# Primero hacer login y copiar el token
TOKEN=$(curl -s -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"cedula":"1234567890","password":"password123"}' \
  | grep -o '"token":"[^"]*' | cut -d'"' -f4)

# Obtener usuario actual
curl http://localhost:8080/api/auth/me \
  -H "Authorization: Bearer $TOKEN"

# Obtener citas del paciente
curl http://localhost:8080/api/citas/paciente/1 \
  -H "Authorization: Bearer $TOKEN"

# Obtener horarios disponibles
curl "http://localhost:8080/api/horarios/disponibles?fecha=2026-05-10" \
  -H "Authorization: Bearer $TOKEN"
```

**Resultado esperado:** JSON con los datos correspondientes

---

### ✅ 4. Verificar Frontend React

#### 4.1 Iniciar el servidor

```bash
# Desde la raíz del proyecto
npm run dev
```

**Resultado esperado:**
```
VITE v6.3.5  ready in 500 ms

➜  Local:   http://localhost:5173/
➜  Network: use --host to expose
```

#### 4.2 Verificar en el Navegador

1. **Abrir:** http://localhost:5173

2. **Verificar página de Login:**
   - ✅ Logo del sistema visible
   - ✅ Frase: "Lo importante no es poder, lo importante es intentar"
   - ✅ Campos: Usuario y Contraseña
   - ✅ Botón "Iniciar Sesión"
   - ✅ Link "¿No tienes cuenta? Regístrate"
   - ✅ Credenciales de prueba visibles

3. **Hacer Login:**
   - Usuario: `1234567890`
   - Contraseña: `password123`
   - Click en "Iniciar Sesión"

4. **Verificar Dashboard:**
   - ✅ Sidebar con menú visible
   - ✅ Cards con estadísticas
   - ✅ Botones de acción rápida
   - ✅ Lista de próximas citas (puede estar vacía)

5. **Verificar Navegación:**
   - ✅ Click en "Terapias" → Ver lista de terapias
   - ✅ Click en "Reservar Cita" → Flujo de 3 pasos
   - ✅ Click en "Mis Citas" → Ver citas (puede estar vacío)
   - ✅ Click en "Perfil" → Ver información del usuario

---

### ✅ 5. Verificar Flujo Completo de Cita

#### 5.1 Crear una Cita

1. **Dashboard** → Click en "Reservar Cita"

2. **Paso 1: Seleccionar Terapia**
   - ✅ Ver 6 terapias con imágenes
   - ✅ Click en "Reservar Cita" de cualquier terapia

3. **Paso 2: Seleccionar Fecha y Hora**
   - ✅ Ver calendario con próximos 7 días
   - ✅ Seleccionar una fecha
   - ✅ Ver horarios disponibles
   - ✅ Seleccionar un horario
   - ✅ Click en "Continuar"

4. **Paso 3: Formulario Médico**
   - ✅ Ingresar síntomas
   - ✅ Seleccionar si tiene exámenes
   - ✅ Click en "Continuar"

5. **Paso 4: Confirmación**
   - ✅ Ver resumen de la cita
   - ✅ Click en "Confirmar Cita"
   - ✅ Ver mensaje de éxito (SweetAlert2)
   - ✅ Redirección a "Mis Citas"

#### 5.2 Verificar la Cita Creada

1. **Ir a "Mis Citas"**
   - ✅ Ver la cita recién creada
   - ✅ Estado: "Pendiente" (naranja)
   - ✅ Ver detalles: fecha, hora, terapia, médico

2. **Cancelar la Cita**
   - ✅ Click en "Cancelar Cita"
   - ✅ Ingresar motivo de cancelación
   - ✅ Confirmar
   - ✅ Ver estado cambiado a "Cancelada" (rojo)

---

### ✅ 6. Verificar Consola del Navegador

1. **Abrir DevTools** (F12)
2. **Ir a la pestaña "Console"**
3. **Verificar que NO hay errores rojos**

**Errores comunes a ignorar:**
- Warnings de React (amarillos) → OK
- Warnings de Material-UI → OK

**Errores que NO deben aparecer:**
- ❌ "Network Error"
- ❌ "401 Unauthorized" (excepto si el token expiró)
- ❌ "500 Internal Server Error"
- ❌ "CORS Error"

---

### ✅ 7. Verificar Network Tab

1. **DevTools** → **Network Tab**
2. **Hacer Login**
3. **Verificar peticiones:**

```
POST http://localhost:8080/api/auth/login
Status: 200 OK
Response: {success: true, user: {...}, token: "..."}
```

4. **Navegar a Dashboard**
5. **Verificar peticiones:**

```
GET http://localhost:8080/api/citas/proximas/1
Status: 200 OK
Response: {success: true, data: [...]}

GET http://localhost:8080/api/terapias
Status: 200 OK
Response: {success: true, data: [...]}
```

---

## 🐛 Problemas Comunes y Soluciones

### ❌ Error: "Connection refused" en el frontend

**Causa:** El backend no está corriendo

**Solución:**
```bash
cd backend/public
php -S localhost:8080
```

### ❌ Error: "CORS policy" en el navegador

**Causa:** CORS no configurado correctamente

**Solución:** Verificar `backend/.env`
```env
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:5173
```

### ❌ Error: "401 Unauthorized" en todas las peticiones

**Causa:** Token JWT inválido o expirado

**Solución:**
1. Hacer logout
2. Hacer login nuevamente
3. Verificar que el token se guarda en sessionStorage

### ❌ Error: "Class not found" en PHP

**Causa:** Autoload no generado

**Solución:**
```bash
cd backend
composer dump-autoload
```

### ❌ Error: "SQLSTATE[08006]" en PHP

**Causa:** No se puede conectar a Supabase

**Solución:**
1. Verificar conexión a internet
2. Verificar credenciales en `backend/.env`
3. Ping a Supabase:
```bash
ping db.hdvmdtapjqqrwcqabqck.supabase.co
```

### ❌ Frontend muestra página en blanco

**Causa:** Error de JavaScript

**Solución:**
1. Abrir DevTools (F12)
2. Ver errores en Console
3. Verificar que `npm run dev` está corriendo
4. Limpiar cache del navegador (Ctrl+Shift+R)

---

## ✅ Checklist Final

```
[ ] Backend PHP corriendo en puerto 8080
[ ] Frontend React corriendo en puerto 5173
[ ] Base de datos con 5 usuarios y 6 terapias
[ ] Health check retorna status "ok"
[ ] Login funciona correctamente
[ ] Dashboard muestra estadísticas
[ ] Se pueden ver terapias
[ ] Se pueden ver médicos
[ ] Se puede crear una cita
[ ] Se puede ver la cita creada
[ ] Se puede cancelar una cita
[ ] No hay errores en la consola del navegador
[ ] Las peticiones HTTP retornan 200 OK
```

---

## 🎉 Sistema Verificado

Si todos los checks están ✅, **el sistema está funcionando correctamente**.

---

## 📞 Soporte

Si algún check falla:

1. Revisar la sección "Problemas Comunes"
2. Consultar `GUIA_INSTALACION.md`
3. Ver logs del backend: `tail -f /var/log/php_errors.log`
4. Ver logs del frontend: Consola del navegador (F12)

---

**¡Sistema verificado y listo para usar!** ✨
