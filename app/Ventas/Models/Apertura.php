<?php

namespace App\Ventas\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Apertura extends Model
{
    protected $fillable = [
        'user_id',
        'tipo',
        'apertura_at',
        'cierre_at',
        'cerrada',
    ];

    protected $dates = ['apertura_at', 'cierre_at'];

    // Verifica si el usuario ya tiene una apertura activa del mismo tipo hoy
    public static function yaAperturadoHoy($tipo)
    {
        return self::where('user_id', Auth::id())
            ->where('tipo', $tipo)
            ->whereDate('apertura_at', now()->toDateString())
            ->exists();
    }

    // Crea una nueva apertura
   public static function crearApertura($tipo)
    {
        return self::create([
            'user_id' => Auth::id(),
            'tipo' => $tipo,
            'apertura_at' => now(),
            'cerrada' => false,
        ]);
    }

    public function cerrar()
    {
        $this->update([
            'cerrada' => true,
            'cierre_at' => now(),
        ]);
    }

    public function usuario()
    {
        return $this->belongsTo(\App\Usuarios\Models\User::class, 'user_id');
    }

}
