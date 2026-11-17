{{-- 
  Formulario Reutilizable de Solicitud (Cancelación / Modificación)
  Parámetros esperados:
    - $tipo: 'cancelacion' o 'modificacion'
    - $accion: 'cancelar' o 'modificar'
--}}
<div id="avisoSolicitud" class="d-none" role="status"></div>

<form id="formSolicitud" data-accion="{{ $accion }}" data-tipo="{{ $tipo }}" method="POST" action="{{ route('solicitudes.store') }}" novalidate>
    @csrf
    <input type="hidden" name="tipo" value="{{ $tipo }}" />

    <div class="mb-3">
        <label for="{{ $tipo === 'cancelacion' ? 'email' : 'mod-email' }}" class="form-label fw-semibold">
            Correo Electrónico <span class="text-danger">*</span>
        </label>
        <input
            type="email"
            id="{{ $tipo === 'cancelacion' ? 'email' : 'mod-email' }}"
            name="email"
            class="form-control @error('email') is-invalid @enderror"
            placeholder="tu@correo.com"
            value="{{ old('email') }}"
            required
        />
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Por favor ingresa un correo electrónico válido.</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="po" class="form-label fw-semibold">
            Orden de compra (PO) <span class="text-danger">*</span>
        </label>
        <input
            type="text"
            id="po"
            name="po"
            class="form-control text-uppercase @error('po') is-invalid @enderror"
            placeholder="Ej. PO-2026-0001"
            pattern="[Pp][Oo]-[0-9]{4}-[0-9]{4}"
            value="{{ old('po') }}"
            required
        />
        <div id="infoPedido" class="mt-2 small d-none"></div>
        @error('po')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Escribe la orden de compra con el formato PO-2026-0001.</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="reason" class="form-label fw-semibold">
            {{ $tipo === 'cancelacion' ? 'Motivo de cancelación:' : 'Tipo de modificación:' }} <span class="text-danger">*</span>
        </label>
        <select id="reason" name="motivo" class="form-select @error('motivo') is-invalid @enderror" required>
            <option value="">Selecciona una opción</option>
            @if($tipo === 'cancelacion')
                <option value="Demora en tiempo de entrega" {{ old('motivo') == 'Demora en tiempo de entrega' ? 'selected' : '' }}>Demora en tiempo de entrega</option>
                <option value="Error en productos seleccionados" {{ old('motivo') == 'Error en productos seleccionados' ? 'selected' : '' }}>Error en productos seleccionados</option>
                <option value="Cambio de opinión" {{ old('motivo') == 'Cambio de opinión' ? 'selected' : '' }}>Cambio de opinión</option>
                <option value="Problemas de presupuesto" {{ old('motivo') == 'Problemas de presupuesto' ? 'selected' : '' }}>Problemas de presupuesto</option>
                <option value="Otro motivo" {{ old('motivo') == 'Otro motivo' ? 'selected' : '' }}>Otro motivo</option>
            @else
                <option value="Ajuste de volumen o cantidad" {{ old('motivo') == 'Ajuste de volumen o cantidad' ? 'selected' : '' }}>Ajuste de volumen o cantidad</option>
                <option value="Cambio de domicilio de entrega" {{ old('motivo') == 'Cambio de domicilio de entrega' ? 'selected' : '' }}>Cambio de domicilio de entrega</option>
                <option value="Actualización de fecha u horario" {{ old('motivo') == 'Actualización de fecha u horario' ? 'selected' : '' }}>Actualización de fecha u horario</option>
                <option value="Cambio de especificaciones de empaque" {{ old('motivo') == 'Cambio de especificaciones de empaque' ? 'selected' : '' }}>Cambio de especificaciones de empaque</option>
                <option value="Otro ajuste" {{ old('motivo') == 'Otro ajuste' ? 'selected' : '' }}>Otro ajuste</option>
            @endif
        </select>
        @error('motivo')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Selecciona una opción.</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="{{ $tipo === 'cancelacion' ? 'comments' : 'changes' }}" class="form-label fw-semibold">
            {{ $tipo === 'cancelacion' ? 'Comentarios adicionales:' : 'Detalles del cambio solicitado:' }} <span class="text-danger">*</span>
        </label>
        <textarea
            id="{{ $tipo === 'cancelacion' ? 'comments' : 'changes' }}"
            name="comentarios"
            class="form-control @error('comentarios') is-invalid @enderror"
            rows="{{ $tipo === 'cancelacion' ? 3 : 4 }}"
            placeholder="{{ $tipo === 'cancelacion' ? 'Describe brevemente la causa de cancelación...' : 'Describe las nuevas cantidades, especificaciones o dirección...' }}"
            required
        >{{ old('comentarios') }}</textarea>
        @error('comentarios')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Por favor describe tu solicitud.</div>
        @enderror
    </div>

    <button type="submit" id="btnSubmitSolicitud" class="btn btn-teal w-100 py-2 fw-bold shadow-sm">
        @if($tipo === 'cancelacion')
            <i class="bi bi-x-circle-fill me-1"></i> Confirmar y Registrar Cancelación
        @else
            <i class="bi bi-save2-fill me-1"></i> Enviar y Registrar Modificación
        @endif
    </button>
</form>
