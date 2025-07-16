<?php

namespace App\Vendedores\Controllers;

use App\Http\Controllers\Controller;

class VendedorController extends Controller
{
    public function index()
    {
        return view('vendedores.index');
    }

    public function show($id)
    {
        return "Detalle del vendedor #$id";
    }
}
