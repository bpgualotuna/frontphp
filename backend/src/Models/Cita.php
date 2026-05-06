<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    protected $table = 'citas';
    
    protected $fillable = [
        'paciente_id',
        'medico_id',
        'terapia_id',
        'fecha',
        'hora',
        'estado',
        'sintomas',
        'tiene_examenes',
        'examenes',
        'notas',
        'motivo_cancelacion'
    ];

    protected $casts = [
        'fecha' => 'date',
        'tiene_examenes' => 'boolean',
        'examenes' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relación: Cita pertenece a un paciente
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paciente_id');
    }

    /**
     * Relación: Cita pertenece a un médico
     */
    public function medico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'medico_id');
    }

    /**
     * Relación: Cita pertenece a una terapia
     */
    public function terapia(): BelongsTo
    {
        return $this->belongsTo(Terapia::class, 'terapia_id');
    }

    /**
     * Scope: Citas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope: Citas confirmadas
     */
    public function scopeConfirmadas($query)
    {
        return $query->where('estado', 'confirmada');
    }

    /**
     * Scope: Próximas citas (futuras y no canceladas)
     */
    public function scopeProximas($query)
    {
        return $query->where('fecha', '>=', now()->toDateString())
                     ->whereIn('estado', ['pendiente', 'confirmada'])
                     ->orderBy('fecha', 'asc')
                     ->orderBy('hora', 'asc');
    }
}
