<?php

namespace App\Ventas\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class InvitadoExonerado extends Model
{
    protected $fillable = [
        'carrito_id', 'documento', 'nombres', 'imagen_documento', 'persona_autoriza', 'numero_autoriza', 'fecha_ingreso'
    ];

    public static function registrarInvitado($data)
    {
        // Guardar la imagen del documento
        $imagen_path = $data['imagen_documento']->store('invitados'); // Guardamos la imagen en la carpeta 'invitados'

        // Crear el registro del invitado o exonerado
        return self::create([
            'carrito_id' => $data['carrito_id'], // Suponiendo que tienes el carrito_id en la sesión
            'documento' => $data['documento'],
            'nombres' => $data['nombres'],
            'imagen_documento' => $imagen_path,
            'persona_autoriza' => $data['persona_autoriza'],
            'numero_autoriza' => $data['numero_autoriza'],
            'fecha_ingreso' => now(),  // Usamos 'now()' para capturar fecha y hora actual
        ]);
    }
}
