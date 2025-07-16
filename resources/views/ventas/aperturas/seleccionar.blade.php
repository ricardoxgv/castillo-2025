@extends('layouts.app')

@section('content')
    <h2>Apertura de Ventas</h2>

    @if(session('error'))
        <div style="color: red">{{ session('error') }}</div>
    @endif

    <form action="{{ route('apertura.crear') }}" method="POST" onsubmit="return confirm('¿Estás seguro de aperturar esta opción?')">
        @csrf
        <button type="submit" name="tipo" value="taquilla">Aperturar Taquilla</button>
        <button type="submit" name="tipo" value="cochera">Aperturar Cochera</button>
    </form>
@endsection
