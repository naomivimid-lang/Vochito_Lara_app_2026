<div class="col-md-6">
    <label for="cliente" class="form-label fw-bold">Nombre del cliente <span class="text-danger">*</span></label>
    <input type="text" id="cliente" name="cliente" minlength="3" maxlength="120" required
           class="form-control @error('cliente') is-invalid @enderror"
           value="{{ old('cliente', $pedido->cliente ?? '') }}">
    @error('cliente') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6">
    <label for="producto_id" class="form-label fw-bold">Producto <span class="text-danger">*</span></label>
    <select id="producto_id" name="producto_id" required
            class="form-select @error('producto_id') is-invalid @enderror">
        <option value="">Selecciona un producto</option>
        @foreach ($productos as $p)
            <option value="{{ $p->id }}" @selected(old('producto_id', $pedido->producto_id ?? '') == $p->id)>
                {{ $p->nombre }} (${{ number_format($p->precio, 2) }})
            </option>
        @endforeach
    </select>
    @error('producto_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    @if ($productos->isEmpty())
        <div class="form-text">No hay productos activos. <a href="{{ route('productos.create') }}">Registra uno primero</a>.</div>
    @endif
</div>

<div class="col-md-6">
    <label for="cantidad" class="form-label fw-bold">Cantidad <span class="text-danger">*</span></label>
    <input type="number" id="cantidad" name="cantidad" min="1" max="1000" step="1" required
           class="form-control @error('cantidad') is-invalid @enderror"
           value="{{ old('cantidad', $pedido->cantidad ?? '') }}">
    @error('cantidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6">
    <label for="entrega" class="form-label fw-bold">Fecha y hora estimada de entrega <span class="text-danger">*</span></label>
    <input type="datetime-local" id="entrega" name="entrega" required
           class="form-control @error('entrega') is-invalid @enderror"
           value="{{ old('entrega', isset($pedido) ? $pedido->entrega->format('Y-m-d\TH:i') : '') }}">
    @error('entrega') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

@isset($pedido)
    <div class="col-md-6">
        <label for="estatus" class="form-label fw-bold">Estatus <span class="text-danger">*</span></label>
        <select id="estatus" name="estatus" required
                class="form-select @error('estatus') is-invalid @enderror">
            @foreach (\App\Models\Pedido::ESTATUS as $e)
                <option value="{{ $e }}" @selected(old('estatus', $pedido->estatus) === $e)>{{ $e }}</option>
            @endforeach
        </select>
        @error('estatus') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endisset

<div class="col-12 d-flex justify-content-between pt-3">
    <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
    <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-floppy-disk"></i> {{ isset($pedido) ? 'Guardar cambios' : 'Registrar pedido' }}</button>
</div>
