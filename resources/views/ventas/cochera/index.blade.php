@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; gap: 20px;">
    <!-- Contenido de ítems de cochera -->
    <div style="width: 100%;">
        <h2>Venta - Cochera</h2>

        @forelse ($items as $item)
            <form method="POST" action="{{ route('carrito.agregar', $item->id) }}" style="margin-bottom: 10px;">
                @csrf
                <div style="border: 1px solid #ccc; padding: 10px;">
                    <strong>{{ $item->nombre }}</strong> – ${{ number_format($item->costo, 2) }}
                    <input type="number" name="cantidad" value="1" min="1" style="width: 60px;" />
                    <button type="submit">Agregar al carrito</button>
                </div>
            </form>
        @empty
            <p>No hay ítems de cochera disponibles.</p>
        @endforelse
    </div>

    <!-- Panel lateral del carrito -->
    @include('ventas.partials.carrito', [
    'empresas' => $empresas,
    'mediosPago' => $mediosPago
])
</div>
@endsection
