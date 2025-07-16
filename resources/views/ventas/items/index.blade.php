@extends('layouts.app')

@section('content')
    <h1>Listado de Ítems</h1>

    @if(session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Costo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->nombre }}</td>
                    <td>S/. {{ number_format($item->costo, 2) }}</td>
                    <td>
                        <form method="POST" action="{{ route('items.toggle', $item->id) }}" onsubmit="return confirmCambio(this, '{{ $item->estado ? 'desactivar' : 'activar' }}')">
                            @csrf
                            @method('PATCH')
                            <button type="submit">
                                {{ $item->estado ? 'Activo' : 'Inactivo' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        function confirmCambio(form, accion) {
            return confirm('¿Estás seguro que deseas ' + accion + ' este ítem?');
        }
    </script>
@endsection
