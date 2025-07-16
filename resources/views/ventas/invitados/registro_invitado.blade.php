<!-- resources/views/ventas/invitados/registro_invitado.blade.php -->




<form action="{{ route('carrito.registrar_invitado') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <h3>Formulario de Registro de Invitado</h3>

    <div>
        <label for="documento">Número de Documento:</label>
        <input type="text" name="documento" id="documento" required>
    </div>

    <div>
        <label for="nombres">Nombres Completos:</label>
        <input type="text" name="nombres" id="nombres" required>
    </div>

    <div>
        <label for="imagen_documento">Imagen del Documento:</label>
        <input type="file" name="imagen_documento" id="imagen_documento" accept="image/*" required>
    </div>

    <div>
        <label for="persona_autoriza">Persona que Autoriza:</label>
        <input type="text" name="persona_autoriza" id="persona_autoriza" required>
    </div>

    <div>
        <label for="numero_autoriza">Número de la Persona que Autoriza:</label>
        <input type="text" name="numero_autoriza" id="numero_autoriza" required>
    </div>

    <button type="submit">Registrar Invitado</button>
</form>
