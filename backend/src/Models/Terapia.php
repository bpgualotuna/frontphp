<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Terapia extends Model
{
    protected $table = 'terapias';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'duracion',
        'precio',
        'imagen',
        'especialidad',
        'activa'
    ];

    protected $casts = [
        'duracion' => 'integer',
        'precio' => 'decimal:2',
        'activa' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relación: Terapia tiene muchas citas
     */
    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'terapia_id');
    }

    /**
     * Scope: Solo terapias activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }
}
