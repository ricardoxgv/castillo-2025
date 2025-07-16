<?php

namespace App\Ventas\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Empresa extends Model
{
    protected $table = 'empresas';

    protected $fillable = [
        'nombre',
        'ruc',
        'nombre_real',
        'representante_legal',
        'activo',
        'created_by',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación con el usuario que creó la empresa.
     */
    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope: empresas activas.
     */
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Evento: asignar usuario creador automáticamente.
     */
    protected static function booted()
    {
        static::creating(function ($empresa) {
            if (auth()->check()) {
                $empresa->created_by = auth()->id();
            }
        });
    }
}
