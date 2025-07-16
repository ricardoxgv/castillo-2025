<?php

namespace App\Ventas\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ventas\Models\Apertura;


class AperturaController extends Controller
{
    public function seleccionar()
    {
        return view('ventas.aperturas.seleccionar'); // vista con los botones "Aperturar Taquilla / Cochera"
    }

    public function aperturar(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:taquilla,cochera',
        ]);

        $tipo = $request->input('tipo');

        if (Apertura::yaAperturadoHoy($tipo)) {
            return redirect()->back()->with('error', 'Ya has aperturado ' . $tipo . ' hoy.');
        }

        Apertura::crearApertura($tipo);

        return redirect()->route('ventas.' . $tipo . '.index')
                ->with('success', 'Se aperturó correctamente la ' . $tipo . '.');

    }
}
