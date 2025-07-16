<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function settings()
    {
        return "Configuración del sistema";
    }
}
