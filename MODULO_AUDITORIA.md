# 📊 Módulo de Auditoría - Documentación

## 📁 Estructura de Carpetas Creada

```
Proyecto-Integrador/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Auditor/
│   │   │       ├── AuditorDashboardController.php    # Dashboard con estadísticas
│   │   │       ├── AuditLogController.php            # Gestión de logs
│   │   │       └── TableViewController.php           # Visualización de tablas
│   │   └── Middleware/
│   │       └── IsAuditor.php                         # Middleware de protección
│   ├── Models/
│   │   └── AuditLog.php                              # Modelo de auditoría
│   └── Services/
│       └── AuditService.php                          # Servicio para registrar logs
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── auditor.blade.php                     # Layout principal
│       └── auditor/
│           ├── dashboard.blade.php                   # Vista del dashboard
│           ├── logs/
│           │   └── index.blade.php                   # Vista de logs con filtros
│           └── tables/
│               ├── users.blade.php                   # Tabla de usuarios
│               ├── citas.blade.php                   # Tabla de citas
│               └── pacientes.blade.php               # Tabla de pacientes
└── routes/
    └── auditor.php                                   # Rutas del módulo
```

## 🎯 Funcionalidades Implementadas

### 1. Dashboard de Auditoría
- **Ruta**: `/auditor/dashboard`
- **Características**:
  - Estadísticas generales (total de logs, logs hoy, usuarios, citas)
  - Gráficos de acciones por tipo
  - Tablas más afectadas
  - Últimas acciones registradas
  - Usuarios más activos

### 2. Logs de Auditoría
- **Ruta**: `/auditor/logs`
- **Características**:
  - Visualización completa de todos los logs
  - Filtros avanzados:
    - Por acción (CREATE, UPDATE, DELETE, LOGIN, LOGOUT)
    - Por tabla afectada
    - Por usuario
    - Por rango de fechas
    - Búsqueda general
  - Modal de detalles con información completa
  - Exportación a CSV
  - Paginación

### 3. Visualización de Tablas

#### Usuarios (`/auditor/tables/users`)
- Lista completa de usuarios del sistema
- Filtros por rol y búsqueda
- Estadísticas por rol
- Información de verificación de email

#### Citas (`/auditor/tables/citas`)
- Lista de todas las citas
- Filtros por estado y fechas
- Estadísticas por estado
- Información de paciente y especialidad

#### Pacientes (`/auditor/tables/pacientes`)
- Lista completa de pacientes
- Búsqueda por nombre, email, teléfono
- Estadísticas de registros

## 🔐 Seguridad

### Middleware IsAuditor
El middleware `IsAuditor` protege todas las rutas del módulo:
- Verifica que el usuario esté autenticado
- Verifica que el rol sea 'auditor'
- Retorna error 403 si no cumple los requisitos

### Registro del Middleware
El middleware está registrado en `app/Http/Kernel.php`:
```php
'auditor' => \App\Http\Middleware\IsAuditor::class,
```

## 📝 Cómo Usar el Sistema de Auditoría

### 1. Registrar Acciones Automáticamente

Usa el servicio `AuditService` en tus controladores:

```php
use App\Services\AuditService;

// Registrar una creación
AuditService::logCreate('usuarios', $user->id, $user->toArray());

// Registrar una actualización
AuditService::logUpdate('pacientes', $paciente->id, $valoresAnteriores, $valoresNuevos);

// Registrar una eliminación
AuditService::logDelete('citas', $cita->id, $cita->toArray());

// Registrar login
AuditService::logLogin();

// Registrar logout
AuditService::logLogout();

// Registrar acceso denegado
AuditService::logAccessDenied('ruta-protegida');
```

### 2. Ejemplo de Implementación en un Controlador

```php
<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Services\AuditService;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email',
        ]);

        $paciente = Paciente::create($validated);

        // Registrar en auditoría
        AuditService::logCreate('pacientes', $paciente->id, $paciente->toArray());

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente creado exitosamente');
    }

    public function update(Request $request, Paciente $paciente)
    {
        $valoresAnteriores = $paciente->toArray();

        $validated = $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email',
        ]);

        $paciente->update($validated);

        // Registrar en auditoría
        AuditService::logUpdate(
            'pacientes',
            $paciente->id,
            $valoresAnteriores,
            $paciente->toArray()
        );

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente actualizado exitosamente');
    }

    public function destroy(Paciente $paciente)
    {
        $valoresAnteriores = $paciente->toArray();
        $paciente->delete();

        // Registrar en auditoría
        AuditService::logDelete('pacientes', $paciente->id, $valoresAnteriores);

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente eliminado exitosamente');
    }
}
```

### 3. Registrar Login/Logout en AuthController

```php
// En tu AuthController, método login
public function login(Request $request)
{
    // ... tu lógica de login ...

    if (Auth::attempt($credentials)) {
        AuditService::logLogin();
        return redirect()->intended('home');
    }

    // ...
}

// En tu AuthController, método logout
public function logout()
{
    AuditService::logLogout();
    Auth::logout();
    return redirect()->route('login');
}
```

## 🎨 Diseño

El módulo cuenta con un diseño moderno y profesional:
- **Tema oscuro** con gradientes
- **Glassmorphism** para las tarjetas
- **Animaciones suaves** en hover
- **Iconos SVG** para mejor rendimiento
- **Responsive** para diferentes dispositivos
- **Colores codificados** por tipo de acción:
  - Verde: CREATE
  - Azul: UPDATE
  - Rojo: DELETE
  - Morado: LOGIN
  - Gris: LOGOUT

## 🔄 Rutas Disponibles

| Ruta | Método | Descripción |
|------|--------|-------------|
| `/auditor/dashboard` | GET | Dashboard principal |
| `/auditor/logs` | GET | Lista de logs con filtros |
| `/auditor/logs/{id}` | GET | Detalle de un log específico |
| `/auditor/logs/export/csv` | GET | Exportar logs a CSV |
| `/auditor/tables/users` | GET | Tabla de usuarios |
| `/auditor/tables/citas` | GET | Tabla de citas |
| `/auditor/tables/pacientes` | GET | Tabla de pacientes |

## 📊 Base de Datos

La tabla `auditoria` ya existe en tu proyecto con la siguiente estructura:

```sql
- id
- usuario_id (FK a usuarios)
- accion (CREATE, UPDATE, DELETE, LOGIN, LOGOUT, etc.)
- tabla_afectada
- registro_id
- valores_anteriores (JSON)
- valores_nuevos (JSON)
- ip_address
- user_agent
- created_at
```

## 🚀 Próximos Pasos

1. **Asegúrate de tener un usuario con rol 'auditor'** en tu base de datos
2. **Implementa el AuditService** en tus controladores existentes
3. **Accede al módulo** en `/auditor/dashboard`
4. **Personaliza las vistas** según tus necesidades

## 🔧 Personalización

### Agregar más tablas para visualizar

1. Crea un nuevo método en `TableViewController.php`
2. Agrega la ruta en `routes/auditor.php`
3. Crea la vista correspondiente en `resources/views/auditor/tables/`
4. Agrega el enlace en el sidebar de `layouts/auditor.blade.php`

### Agregar más tipos de acciones

Simplemente usa el método genérico:
```php
AuditService::log('CUSTOM_ACTION', 'tabla', 'id', $antes, $despues);
```

## 📞 Soporte

Si necesitas ayuda adicional o quieres agregar más funcionalidades, no dudes en preguntar.

---

**Desarrollado para el Proyecto Integrador** 🎓
