<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $table = 'users';
    
    protected $fillable = [
        'cedula',
        'username',
        'password',
        'full_name',
        'role',
        'email',
        'direccion',
        'edad',
        'sexo',
        'tiene_seguro',
        'telefono',
        'avatar',
        'foto_perfil',
        'especialidad',
        'numero_licencia',
        'calificacion',
        'pacientes_atendidos'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'tiene_seguro' => 'boolean',
        'edad' => 'integer',
        'calificacion' => 'float',
        'pacientes_atendidos' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relación: Usuario tiene muchas citas (como paciente)
     */
    public function citasComoPaciente(): HasMany
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    /**
     * Relación: Usuario tiene muchas citas (como médico)
     */
    public function citasComoMedico(): HasMany
    {
        return $this->hasMany(Cita::class, 'medico_id');
    }

    /**
     * Relación: Médico tiene horarios de atención
     */
    public function horariosAtencion(): HasMany
    {
        return $this->hasMany(HorarioAtencion::class, 'medico_id');
    }

    /**
     * Verificar si el usuario es paciente
     */
    public function esPaciente(): bool
    {
        return $this->role === 'paciente';
    }

    /**
     * Verificar si el usuario es médico
     */
    public function esMedico(): bool
    {
        return $this->role === 'medico';
    }

    /**
     * Verificar si el usuario es admin
     */
    public function esAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
