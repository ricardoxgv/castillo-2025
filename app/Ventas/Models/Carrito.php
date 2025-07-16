<?php

namespace App\Ventas\Models;

use App\Ventas\Models\Item;

class Carrito
{
    public static function obtener()
    {
        return session('carrito', [
            'items' => [],
            'descuento_global' => 0
        ]);
    }

    public static function agregar(Item $item, int $cantidad = 1)
    {
        $carrito = self::obtener();

        // 🔑 Generar una clave única por cada adición
        $idUnico = uniqid('item_', true);

        $carrito['items'][$idUnico] = [
            'nombre' => $item->nombre,
            'precio_unitario' => $item->costo,
            'cantidad' => $cantidad,
            'descuento' => 0,
        ];

        session()->put('carrito', $carrito);
    }

    public static function limpiar()
    {
        session()->forget('carrito');
    }

    public static function total()
    {
        $carrito = self::obtener();
        $total = 0;

        foreach ($carrito['items'] as $item) {
            $subtotal = $item['precio_unitario'] * $item['cantidad'];
            $desc = $item['descuento'] ?? 0;
            $total += $subtotal - $desc;
        }

        return $total - ($carrito['descuento_global'] ?? 0);
    }

    public static function eliminar($id)
    {
        $carrito = session('carrito', []);

        if (isset($carrito['items'][$id])) {
            unset($carrito['items'][$id]);
            session(['carrito' => $carrito]);
        }
    }

    public static function aplicarDescuento($itemId, $valor)
    {
        $carrito = session('carrito', [
            'items' => [],
            'descuento_global' => 0,
        ]);

        if (!isset($carrito['items'][$itemId])) {
            return;
        }

        $descuento = 0;
        $descuento_text = '';

        if (str_ends_with($valor, '%')) {
            $porcentaje = floatval(rtrim($valor, '%')) / 100;
            $subtotal = $carrito['items'][$itemId]['precio_unitario'] * $carrito['items'][$itemId]['cantidad'];
            $descuento = $subtotal * $porcentaje;
            $descuento_text = $valor; // ← ya incluye %
        } else {
            $descuento = floatval($valor);
            $descuento_text = ''; // para monto fijo, no se muestra texto extra
        }

        $max_descuento = $carrito['items'][$itemId]['precio_unitario'] * $carrito['items'][$itemId]['cantidad'];
        $carrito['items'][$itemId]['descuento'] = min($descuento, $max_descuento);
        $carrito['items'][$itemId]['descuento_text'] = $descuento_text;

        session()->put('carrito', $carrito);
    }


    public static function aplicarDescuentoGlobal($valor)
{
    $carrito = session('carrito', []);

    // Verificar si hay descuentos individuales
    foreach ($carrito['items'] ?? [] as $item) {
        if (!empty($item['descuento'])) {
            return 'error';
        }
    }

    $total = 0;
    foreach ($carrito['items'] as $item) {
        $total += $item['precio_unitario'] * $item['cantidad'];
    }

    $descuento = 0;
    $texto = '';

    if (str_ends_with($valor, '%')) {
        $porcentaje = floatval(rtrim($valor, '%')) / 100;
        $descuento = $total * $porcentaje;
        $texto = $valor; // Ej. "2%"
    } else {
        $descuento = floatval($valor);
        $texto = ''; // si es monto, no mostramos texto
    }

    $descuento = min($descuento, $total);
    $carrito['descuento_global'] = $descuento;
    $carrito['descuento_global_text'] = $texto;

    session()->put('carrito', $carrito);

    return 'success';
}




}
