<?php
namespace App\Ventas\Controllers;

use App\Http\Controllers\Controller;
use App\Ventas\Models\Item;
use App\Ventas\Models\InvitadoExonerado;
use Illuminate\Http\Request;

class ItemInvitadoExoneradoController extends Controller
{
    /**
     * Mostrar el formulario para llenar los datos antes de agregar el ítem al carrito.
     */
    public function mostrarFormulario($itemId)
    {
        $item = Item::findOrFail($itemId);

        if ($item->categoria !== 'invitado' && $item->categoria !== 'exonerado') {
            return redirect()->route('carrito.index')->with('error', 'Este ítem no requiere formulario.');
        }

        return view('ventas.invitados.registro_invitado', compact('item'));
    }

    /**
     * Procesar el formulario y agregar el ítem al carrito.
     */
    public function procesarFormulario(Request $request)
    {
        // Validación del formulario
        $validated = $request->validate([
            'documento' => 'required|string',
            'nombres' => 'required|string',
            'persona_autoriza' => 'required|string',
            'numero_autoriza' => 'required|string',
            'imagen_documento' => 'required|image',
        ]);

        // Guardar los datos en la tabla invitados_exonerados
        InvitadoExonerado::create([
            'carrito_id' => session()->getId(),
            'documento' => $validated['documento'],
            'nombres' => $validated['nombres'],
            'persona_autoriza' => $validated['persona_autoriza'],
            'numero_autoriza' => $validated['numero_autoriza'],
            'imagen_documento' => $request->file('imagen_documento')->store('imagenes_documentos', 'public'),
        ]);

        // Agregar el ítem al carrito
        $item = Item::findOrFail($request->input('item_id'));
        Carrito::agregar($item, $request->input('cantidad', 1));

        return redirect()->route('carrito.index')->with('success', 'Ítem agregado al carrito.');
    }
}
