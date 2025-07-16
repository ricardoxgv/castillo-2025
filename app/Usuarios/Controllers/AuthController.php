<?php

namespace App\Usuarios\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function show()
    {
        return view('usuarios.login');
    }

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        switch ($user->tipo) {
            case 'admin':
                return redirect('/admin');
            case 'ventas':
                return redirect('/ventas');
            case 'vendedor':
                return redirect('/vendedores');
            case 'cajero':
                return redirect('/caja');
            case 'datos':
                return redirect('/reportes');
            case 'control':
                return redirect('/control');
            default:
                return redirect('/home');
        }
    }

    return back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
}


    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
