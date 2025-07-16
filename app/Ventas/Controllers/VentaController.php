<?php
namespace App\Ventas\Controllers;

use App\Http\Controllers\Controller;
use App\Ventas\Models\Venta;

class VentaController extends Controller
{
    // Método para mostrar la vista principal de ventas
    public function index()
    {
        return view('ventas.index');
    }

    // Método para mostrar los ítems disponibles en la taquilla
    public function vistaTaquilla()
    {
        // Llamamos al modelo Venta para obtener los datos
        $datos = Venta::obtenerTaquilla();

        // Retorna la vista de taquilla con los datos
        return view('ventas.taquilla.index', $datos);
    }

    // Método para mostrar los ítems de tipo cochera
    public function vistaCochera()
    {
        // Llamamos al modelo Venta para obtener los datos
        $datos = Venta::obtenerCochera();

        // Retorna la vista de cochera con los datos
        return view('ventas.cochera.index', $datos);
    }
}
