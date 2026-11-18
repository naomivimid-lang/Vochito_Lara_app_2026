<div class="col-md-6">
    <label for="nombre" class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
    <input type="text" id="nombre" name="nombre" maxlength="120" required
           class="form-control @error('nombre') is-invalid @enderror"
           value="{{ old('nombre', $producto->nombre ?? '') }}">
    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-3">
    <label for="precio" class="form-label fw-bold">Precio <span class="text-danger">*</span></label>
    <div class="input-group has-validation">
        <span class="input-group-text">$</span>
        <input type="number" id="precio" name="precio" step="0.01" min="0" required
               class="form-control @error('precio') is-invalid @enderror"
               value="{{ old('precio', $producto->precio ?? '') }}">
        @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="col-md-3">
    <label for="stock" class="form-label fw-bold">Stock <span class="text-danger">*</span></label>
    <input type="number" id="stock" name="stock" step="1" min="0" required
           class="form-control @error('stock') is-invalid @enderror"
           value="{{ old('stock', $producto->stock ?? 0) }}">
    @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12">
    <label for="descripcion" class="form-label fw-bold">Descripción</label>
    <textarea id="descripcion" name="descripcion" rows="3" maxlength="1000"
              class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
    @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12">
    <div class="form-check form-switch">
        <input type="checkbox" id="activo" name="activo" value="1" role="switch"
               class="form-check-input @error('activo') is-invalid @enderror"
               @checked(old('activo', $producto->activo ?? true))>
        <label for="activo" class="form-check-label">Activo (se muestra en el catálogo y al registrar pedidos)</label>
        @error('activo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="col-12 d-flex justify-content-between pt-3">
    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
    <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
</div>
