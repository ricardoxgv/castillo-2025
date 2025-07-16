<?php

namespace App\Ventas\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MedioPago extends Model
{
    protected $table = 'medios_de_pago';

    protected $fillable = [
        'nombre',
        'activo',
        'created_by',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación con el usuario que creó el medio de pago.
     */
    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope: medios de pago activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Evento: asignar usuario creador automáticamente.
     */
    protected static function booted()
    {
        static::creating(function ($medio) {
            if (auth()->check()) {
                $medio->created_by = auth()->id();
            }
        });
    }
}
