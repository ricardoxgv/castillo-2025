<?php

namespace App\Datos\Controllers;

use App\Http\Controllers\Controller;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function show($id)
    {
        return "Detalle del reporte #$id";
    }
}
