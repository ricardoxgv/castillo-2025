@extends('layouts.app') {{-- O cambia esto si tienes otro layout --}}

@section('content')
    <h2>Registrar nuevo ítem</h2>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    {{-- Mostrar errores de validación --}}
    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('items.store') }}">
        @csrf

        <label for="nombre">Nombre del ítem:</label><br>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required><br><br>

        <label for="tipo">Tipo:</label><br>
        <select name="tipo" required>
            <option value="">-- Seleccione --</option>
            <option value="entrada" {{ old('tipo') == 'entrada' ? 'selected' : '' }}>Entrada</option>
            <option value="servicio" {{ old('tipo') == 'servicio' ? 'selected' : '' }}>Servicio</option>
            <option value="cochera" {{ old('tipo') == 'cochera' ? 'selected' : '' }}>Cochera</option>
        </select><br><br>
        <label for="categoria">Categoría:</label><br>
            <select name="categoria" required>
                <option value="general" {{ old('categoria') == 'general' ? 'selected' : '' }}>General</option>
                <option value="exonerado" {{ old('categoria') == 'exonerado' ? 'selected' : '' }}>Exonerado (Marina, CONADIS...)</option>
                <option value="invitado" {{ old('categoria') == 'invitado' ? 'selected' : '' }}>Invitado (Cortesía, Marketing...)</option>
            </select><br><br>

        <label for="costo">Costo:</label><br>
        <input type="number" name="costo" step="0.01" value="{{ old('costo') }}" required><br><br>

        <button type="submit">Guardar ítem</button>
    </form>
@endsection
