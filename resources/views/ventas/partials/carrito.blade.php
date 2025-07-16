@php
    $carrito = session('carrito.items', []);
    $descuento_global = session('carrito.descuento_global', 0);
    $monto_total = 0;
    $hay_descuento_individual = false;
@endphp

<div style="position: fixed; top: 80px; right: 20px; width: 450px; border-left: 2px solid #ccc; padding: 15px; background-color: #f9f9f9;">
    <h3>🛒 Carrito</h3>

    @if (empty($carrito))
        <p>No hay ítems en el carrito.</p>
    @else
        <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Cant.</th>
                    <th>Precio UND</th>
                    <th>Submonto</th>
                    <th>Descuento</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($carrito as $key => $item)
                    @php
                        $descuento = $item['descuento'] ?? 0;
                        $precio_unitario = $item['precio_unitario'];
                        $submonto = $precio_unitario * $item['cantidad'];
                        $monto_final = $submonto - $descuento;
                        $monto_total += $monto_final;
                        if ($descuento > 0) $hay_descuento_individual = true;
                    @endphp
                    <tr>
                        <td>{{ $item['nombre'] }}</td>
                        <td style="text-align: center;">{{ $item['cantidad'] }}</td>
                        <td style="text-align: right;">S/ {{ number_format($precio_unitario, 2) }}</td>
                        <td style="text-align: right;">S/ {{ number_format($submonto, 2) }}</td>
                        <td style="text-align: center;">
                            @if ($descuento > 0)
                                @if (str_contains($item['descuento_text'] ?? '', '%'))
                                    -{{ $item['descuento_text'] }} (S/ {{ number_format($descuento, 2) }})
                                @else
                                    - S/ {{ number_format($descuento, 2) }}
                                @endif
                            @else
                                <input type="checkbox" class="descuento-toggle" data-id="{{ $key }}" @if($descuento_global > 0) disabled @endif>
                            @endif
                        </td>
                        <td style="text-align: right;">S/ {{ number_format($monto_final, 2) }}</td>
                        <td>
                            <form method="POST" action="{{ route('carrito.eliminar', $key) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit">❌</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Descuento formulario -->
                    <tr id="descuento-form-{{ $key }}" class="descuento-form-row" style="display: none;">
                        <td colspan="7">
                            <form method="POST" action="{{ route('carrito.descuento', $key) }}">
                                @csrf
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <span>Submonto: <strong>S/ {{ number_format($submonto, 2) }}</strong></span>
                                    <label>Descuento:</label>
                                    <input type="text" name="descuento" placeholder="10 o 10%" required style="width: 80px;">
                                    <button type="submit">✔ Aplicar</button>
                                    <button type="button" class="cancelar-descuento" data-id="{{ $key }}">✖ Cancelar</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $descuento_global_text = session('carrito.descuento_global_text', '');
            $descuento_global_monto = 0;
            if (str_contains($descuento_global_text, '%')) {
                $porcentaje = floatval(str_replace('%', '', $descuento_global_text)) / 100;
                $descuento_global_monto = $monto_total * $porcentaje;
            } else {
                $descuento_global_monto = floatval($descuento_global);
            }
            $total_final = $monto_total - $descuento_global_monto;
            $subtotal = $total_final / 1.18;
            $igv = $total_final - $subtotal;
        @endphp

        <div style="margin-top: 10px; text-align: right;">
            <p><strong>Subtotal:</strong> S/ {{ number_format($subtotal, 2) }}</p>
            <p><strong>IGV (18%):</strong> S/ {{ number_format($igv, 2) }}</p>
            <p><strong>Total:</strong> S/ {{ number_format($monto_total, 2) }}</p>

            @if ($descuento_global_monto > 0)
                <p><strong>💸 Descuento Global:</strong> 
                    @if ($descuento_global_text)
                        -{{ $descuento_global_text }} (S/ {{ number_format($descuento_global_monto, 2) }})
                    @else
                        - S/ {{ number_format($descuento_global_monto, 2) }}
                    @endif
                </p>
                <p><strong>💰 Precio Promocional:</strong> S/ {{ number_format($total_final, 2) }}</p>
            @endif

            @if ($hay_descuento_individual)
                <p style="color: red;"><strong>⚠ Ya hay descuentos individuales. El descuento global no se puede aplicar.</strong></p>
            @endif

            @if ($descuento_global_monto > 0)
                <p style="color: red;"><strong>⚠ Ya hay un descuento global aplicado.</strong></p>
            @endif
        </div>

        <!-- Botón para aplicar descuento global -->
        <form method="POST" action="{{ route('carrito.descuento.global') }}" style="margin-top: 10px; text-align: right;">
            @csrf
            <label for="descuento_global"><strong>Aplicar descuento global:</strong></label>
            <input type="text" name="descuento" id="descuento_global" placeholder="10 o 10%" style="width: 80px;" 
                   @if($descuento_global_monto > 0 || $hay_descuento_individual) disabled @endif required>
            <button type="submit" @if($descuento_global_monto > 0 || $hay_descuento_individual) disabled @endif>💸 Aplicar</button>
        </form>

        @if ($descuento_global_monto > 0)
            <form method="POST" action="{{ route('carrito.descuento.global') }}" style="margin-top: 5px; text-align: right;">
                @csrf
                <input type="hidden" name="descuento" value="0">
                <button type="submit" style="background-color: #f99;">❌ Limpiar Descuento Global</button>
            </form>
        @endif

        <!-- Limpiar el carrito -->
        <form method="POST" action="{{ route('carrito.limpiar') }}" style="margin-top: 10px; text-align: right;">
            @csrf
            <button type="submit">🧹 Limpiar Carrito</button>
        </form>

        <!-- Selección de Medio de Pago -->
        <form method="POST" action="{{ route('carrito.seleccionar.medio_pago') }}" style="margin-top: 15px; text-align: right;">
            @csrf
            <label for="medio_pago_id"><strong>Medio de Pago:</strong></label>
            <select name="medio_pago_id" id="medio_pago_id" required>
                <option value="">-- Seleccionar --</option>
                @foreach ($mediosPago as $medio)
                    <option value="{{ $medio->id }}" {{ session('carrito.medio_pago_id') == $medio->id ? 'selected' : '' }}>
                        {{ $medio->nombre }}
                    </option>
                @endforeach
            </select>
            <label style="margin-left: 10px;">
                <input type="checkbox" name="fijar_medio" value="1"
                    {{ session()->has('carrito.medio_pago_id') ? 'checked' : '' }}
                    onchange="this.form.submit()">
                Fijar
            </label>
        </form>

        <!-- Selección de Empresa -->
        <form method="POST" action="{{ route('carrito.seleccionar.empresa') }}" style="margin-top: 10px; text-align: right;">
            @csrf
            <label for="empresa_id"><strong>Empresa:</strong></label>
            <select name="empresa_id" id="empresa_id" required>
                <option value="">-- Seleccionar --</option>
                @foreach ($empresas as $empresa)
                    <option value="{{ $empresa->id }}" {{ session('carrito.empresa_id') == $empresa->id ? 'selected' : '' }}>
                        {{ $empresa->nombre }}
                    </option>
                @endforeach
            </select>
            <label style="margin-left: 10px;">
                <input type="checkbox" name="fijar_empresa" value="1"
                    {{ session()->has('carrito.empresa_id') ? 'checked' : '' }}
                    onchange="this.form.submit()">
                Fijar
            </label>
        </form>
    @endif
</div>

<script>
    document.querySelectorAll('.descuento-toggle').forEach(chk => {
        chk.addEventListener('change', function () {
            const id = this.dataset.id;
            document.getElementById('descuento-form-' + id).style.display = this.checked ? 'table-row' : 'none';
        });
    });

    document.querySelectorAll('.cancelar-descuento').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            document.getElementById('descuento-form-' + id).style.display = 'none';
            document.querySelector(`.descuento-toggle[data-id="${id}"]`).checked = false;
        });
    });

    // Control de visibilidad de los selects para medio de pago y empresa
    function controlarCheckboxVisualmente(selector, selectId) {
        const checkbox = document.querySelector(selector);
        const select = document.getElementById(selectId);

        if (!checkbox || !select) return;

        select.readOnly = checkbox.checked;
        select.style.backgroundColor = checkbox.checked ? '#eee' : '';
        select.style.pointerEvents = checkbox.checked ? 'none' : 'auto';

        checkbox.addEventListener('change', function () {
            if (this.checked) {
                select.readOnly = true;
                select.style.backgroundColor = '#eee';
                select.style.pointerEvents = 'none';
            } else {
                select.readOnly = false;
                select.style.backgroundColor = '';
                select.style.pointerEvents = 'auto';
            }
        });
    }

    controlarCheckboxVisualmente('input[name="fijar_medio"]', 'medio_pago_id');
    controlarCheckboxVisualmente('input[name="fijar_empresa"]', 'empresa_id');
</script>
