<?php

namespace App\Ventas\Controllers;

use App\Http\Controllers\Controller;
use App\Ventas\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function create()
    {
        return view('ventas.items.create');
    }

    public function store(Request $request)
    {
        Item::crearNuevo($request);

        return redirect()->route('items.create')->with('success', 'Ítem creado correctamente.');
    }

    public function index()
    {
    $items = Item::obtenerTodos();
    return view('ventas.items.index', compact('items'));
    }

    public function toggleEstado($id)
    {
    $item = Item::findOrFail($id);
    $item->alternarEstado();

    return redirect()->route('items.index')->with('success', 'Estado del ítem actualizado.');
    }

}
