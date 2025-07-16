<?php

namespace App\Ventas\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ventas\Models\Item;
use App\Ventas\Models\Carrito;
use App\Ventas\Models\Empresa;
use App\Ventas\Models\MedioPago;

class CarritoController extends Controller
{
    /**
     * Mostrar el carrito con items, empresas y medios de pago activos.
     */
    public function mostrarCarrito()
    {
        return view('ventas.carrito', [
            'carrito'          => session('carrito.items', []),
            'descuento_global' => session('carrito.descuento_global', 0),
            'empresas'         => Empresa::activas()->get(),
            'mediosPago'       => MedioPago::activos()->get(),
        ]);
    }

    /**
     * Agregar un ítem al carrito.
     */
    public function agregar(Request $request, $itemId)
    {
        $item = Item::findOrFail($itemId);
        Carrito::agregar($item, $request->input('cantidad', 1));
        return back();
    }

    /**
     * Limpiar el carrito.
     */
    public function limpiar()
    {
        Carrito::limpiar();
        return back();
    }

    /**
     * Eliminar un ítem del carrito.
     */
    public function eliminar($id)
    {
        Carrito::eliminar($id);
        return back();
    }

    /**
     * Aplicar un descuento individual a un ítem.
     */
    public function aplicarDescuento(Request $request, $itemId)
    {
        Carrito::aplicarDescuento($itemId, $request->input('descuento'));
        return back()->with('success', 'Descuento aplicado correctamente.');
    }

    /**
     * Aplicar un descuento global a todo el carrito.
     */
    public function aplicarDescuentoGlobal(Request $request)
    {
        $valor = $request->input('descuento');
        $resultado = Carrito::aplicarDescuentoGlobal($valor);

        if ($resultado === 'error') {
            return back()->with('error', 'Debes eliminar los descuentos individuales antes de aplicar un descuento global.');
        }

        return back()->with('success', 'Descuento global aplicado.');
    }

    /**
     * Fijar medio de pago en sesión.
     */
   public function seleccionarMedioPago(Request $request)
    {
        if ($request->has('fijar_medio')) {
            $request->validate([
                'medio_pago_id' => 'required|exists:medios_de_pago,id',
            ]);
            session()->put('carrito.medio_pago_id', $request->medio_pago_id);
        } else {
            session()->forget('carrito.medio_pago_id');
        }

        return back();
    }

    /**
     * Fijar empresa en sesión.
     */
    public function seleccionarEmpresa(Request $request)
    {
        if ($request->has('fijar_empresa')) {
            $request->validate([
                'empresa_id' => 'required|exists:empresas,id',
            ]);
            session()->put('carrito.empresa_id', $request->empresa_id);
        } else {
            session()->forget('carrito.empresa_id');
        }

        return back();
    }
}
