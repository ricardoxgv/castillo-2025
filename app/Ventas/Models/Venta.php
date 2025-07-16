<?php
namespace App\Ventas\Models;

use Illuminate\Database\Eloquent\Model;
use App\Ventas\Models\Item;
use App\Ventas\Models\Empresa;
use App\Ventas\Models\MedioPago;

class Venta extends Model
{
    // Método para obtener los ítems de taquilla con empresas y medios de pago activos
    public static function obtenerTaquilla()
    {
        // Recupera los ítems separados para la taquilla
        $datos = Item::taquillaSeparada();

        // Añadir empresas y medios de pago activos
        $datos['empresas'] = Empresa::activas()->get();
        $datos['mediosPago'] = MedioPago::activos()->get();

        return $datos;
    }

    // Método para obtener los ítems de cochera con empresas y medios de pago activos
    public static function obtenerCochera()
    {
        // Recupera los ítems de tipo 'cochera'
        $items = Item::where('tipo', 'cochera')->get();

        // Añadir empresas y medios de pago activos
        return [
            'items' => $items,
            'empresas' => Empresa::activas()->get(),
            'mediosPago' => MedioPago::activos()->get(),
        ];
    }
}
