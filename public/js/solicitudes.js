/*
 * solicitudes.js - Gestión integral de cancelaciones y modificaciones de pedidos
 * Sincronizado con Bochito (localStorage) y con los endpoints de Laravel (SolicitudController).
 */
document.addEventListener("DOMContentLoaded", () => {
  const formSolicitud = document.getElementById("formSolicitud");
  const avisoSolicitud = document.getElementById("avisoSolicitud");
  const tablaSolicitudes = document.getElementById("tablaSolicitudes");
  const cuerpoTabla = document.getElementById("cuerpoSolicitudes");
  const filtroEstado = document.getElementById("filtroEstado");
  const contadorSolicitudes = document.getElementById("contadorSolicitudes");
  const EMBARCADO = "En tránsito";

  // Determinar si esta página es de cancelaciones o modificaciones
  const tipoPagina = formSolicitud ? formSolicitud.dataset.accion : "cancelacion";

  // Token CSRF de Laravel si está disponible en la página
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");

  function avisar(texto, tipo = "success") {
    if (!avisoSolicitud) return;
    avisoSolicitud.textContent = texto;
    avisoSolicitud.className = `alert alert-${tipo} mt-3 mb-0 shadow-sm`;
    avisoSolicitud.classList.remove("d-none");
    avisoSolicitud.scrollIntoView({ behavior: "smooth", block: "nearest" });
  }

  // Pre-llenar correo si hay sesión activa
  const sesion = Bochito.getSesion();
  if (sesion && sesion.correo) {
    const inputEmail = document.getElementById("email") || document.getElementById("mod-email");
    if (inputEmail && !inputEmail.value) {
      inputEmail.value = sesion.correo;
    }
  }

  /**
   * Renderiza las solicitudes registradas en la tabla
   */
  function cargarTablaSolicitudes() {
    if (!cuerpoTabla) return;
    cuerpoTabla.innerHTML = "";

    const estadoSeleccionado = filtroEstado ? filtroEstado.value : "";
    let solicitudes = Bochito.getSolicitudes(tipoPagina);

    if (estadoSeleccionado) {
      solicitudes = solicitudes.filter(s => s.estado === estadoSeleccionado);
    }

    if (contadorSolicitudes) {
      contadorSolicitudes.textContent = solicitudes.length;
    }

    if (solicitudes.length === 0) {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td colspan="7" class="text-center text-muted py-4">
          <i class="bi bi-inbox fs-3 d-block mb-2"></i>
          No hay solicitudes de ${tipoPagina} registradas ${estadoSeleccionado ? `con estatus "${estadoSeleccionado}"` : ""}.
        </td>
      `;
      cuerpoTabla.appendChild(tr);
      return;
    }

    solicitudes.forEach((sol) => {
      const tr = document.createElement("tr");

      // Badge de estado
      const badgeClase = Bochito.COLOR_ESTADO_SOLICITUD[sol.estado] || "secondary";

      tr.innerHTML = `
        <td class="fw-bold text-secondary">#${sol.id}</td>
        <td><span class="badge text-bg-light border">${sol.po}</span></td>
        <td><small class="text-muted">${sol.email}</small></td>
        <td>
          <div class="fw-semibold text-truncate" style="max-width: 180px;" title="${sol.motivo || ''}">
            ${sol.motivo || 'General'}
          </div>
          <small class="text-muted text-truncate d-block" style="max-width: 220px;" title="${sol.comentarios}">
            ${sol.comentarios}
          </small>
        </td>
        <td><small>${Bochito.formatoFecha(sol.fecha || sol.created_at)}</small></td>
        <td><span class="badge text-bg-${badgeClase}">${sol.estado}</span></td>
        <td class="text-end">
          <div class="btn-group btn-group-sm" role="group">
            ${
              sol.estado === "Pendiente"
                ? `
                <button type="button" class="btn btn-outline-success btn-aprobar" data-id="${sol.id}" title="Aprobar solicitud">
                  <i class="bi bi-check-lg"></i> Aprobar
                </button>
                <button type="button" class="btn btn-outline-warning btn-rechazar" data-id="${sol.id}" title="Rechazar solicitud">
                  <i class="bi bi-x-lg"></i> Rechazar
                </button>
              `
                : `
                <button type="button" class="btn btn-outline-secondary btn-cambiar-estado" data-id="${sol.id}" title="Cambiar a Pendiente">
                  <i class="bi bi-arrow-repeat"></i> Reabrir
                </button>
              `
            }
            <button type="button" class="btn btn-outline-danger btn-eliminar" data-id="${sol.id}" data-po="${sol.po}" title="Eliminar solicitud">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </td>
      `;
      cuerpoTabla.appendChild(tr);
    });

    asignarEventosAcciones();
  }

  /**
   * Asigna eventos a los botones de acciones de la tabla
   */
  function asignarEventosAcciones() {
    // Botón Aprobar
    document.querySelectorAll(".btn-aprobar").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.dataset.id;
        actualizarEstadoSolicitud(id, "Aprobada", "Solicitud aprobada por el área administrativa.");
      });
    });

    // Botón Rechazar
    document.querySelectorAll(".btn-rechazar").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.dataset.id;
        const motivo = prompt("Motivo del rechazo de la solicitud (opcional):", "No cumple con las políticas de tiempo o embarque.");
        if (motivo !== null) {
          actualizarEstadoSolicitud(id, "Rechazada", motivo);
        }
      });
    });

    // Botón Reabrir / Cambiar Estado
    document.querySelectorAll(".btn-cambiar-estado").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.dataset.id;
        actualizarEstadoSolicitud(id, "Pendiente", "Solicitud reabierta para revisión.");
      });
    });

    // Botón Eliminar
    document.querySelectorAll(".btn-eliminar").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.dataset.id;
        const po = btn.dataset.po;
        if (confirm(`¿Estás seguro de que deseas eliminar permanentemente la solicitud para la orden ${po}?`)) {
          eliminarSolicitud(id);
        }
      });
    });
  }

  /**
   * Actualiza el estado de la solicitud en localStorage y backend
   */
  async function actualizarEstadoSolicitud(id, nuevoEstado, respuestaAdmin = null) {
    // 1. Actualización local
    Bochito.updateSolicitudEstado(id, nuevoEstado, respuestaAdmin);
    cargarTablaSolicitudes();
    avisar(`Solicitud #${id} actualizada a "${nuevoEstado}".`, nuevoEstado === "Aprobada" ? "success" : "warning");

    // 2. Intentar actualización en backend Laravel
    try {
      await fetch(`/solicitudes/${id}/estado`, {
        method: "PATCH",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          ...(csrfToken ? { "X-CSRF-TOKEN": csrfToken } : {})
        },
        body: JSON.stringify({ estado: nuevoEstado, respuesta_admin: respuestaAdmin })
      });
    } catch (e) {
      // Backend offline o modo local: los datos persisten en localStorage
    }
  }

  /**
   * Elimina una solicitud en localStorage y backend
   */
  async function eliminarSolicitud(id) {
    // 1. Eliminación local
    Bochito.eliminarSolicitud(id);
    cargarTablaSolicitudes();
    avisar(`La solicitud #${id} fue eliminada correctamente.`, "info");

    // 2. Intentar eliminación en backend Laravel
    try {
      await fetch(`/solicitudes/${id}`, {
        method: "DELETE",
        headers: {
          "Accept": "application/json",
          ...(csrfToken ? { "X-CSRF-TOKEN": csrfToken } : {})
        }
      });
    } catch (e) {
      // Persiste localmente
    }
  }

  /**
   * Envío del formulario de solicitud (registro)
   */
  if (formSolicitud) {
    formSolicitud.addEventListener("submit", async (e) => {
      e.preventDefault();
      formSolicitud.classList.add("was-validated");
      if (avisoSolicitud) avisoSolicitud.className = "d-none";

      if (!formSolicitud.checkValidity()) return;

      const accion = formSolicitud.dataset.accion; // "cancelar" o "modificar"
      const tipo = accion === "cancelar" ? "cancelacion" : "modificacion";
      const poInput = document.getElementById("po");
      const po = poInput.value.trim().toUpperCase();

      const emailInput = document.getElementById("email") || document.getElementById("mod-email");
      const email = emailInput ? emailInput.value.trim() : "";

      const reasonSelect = document.getElementById("reason");
      const motivo = reasonSelect ? reasonSelect.options[reasonSelect.selectedIndex]?.text : "Modificación solicitada";

      const commentsInput = document.getElementById("comments") || document.getElementById("changes");
      const comentarios = commentsInput ? commentsInput.value.trim() : "";

      // Verificar estatus del pedido en el registro
      const pedido = Bochito.getPedidos().find((p) => p.po === po);
      if (pedido && pedido.estatus === EMBARCADO) {
        return avisar(
          `El pedido ${po} ya fue embarcado ("${EMBARCADO}"), por lo que no es posible procesar la solicitud de ${tipo}.`,
          "danger"
        );
      }

      // 1. Guardar solicitud localmente en Bochito
      const nuevaSolicitud = Bochito.saveSolicitud({
        tipo,
        po,
        email,
        motivo,
        comentarios,
      });

      // 2. Intentar guardar en backend Laravel
      try {
        await fetch("/solicitudes", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            ...(csrfToken ? { "X-CSRF-TOKEN": csrfToken } : {})
          },
          body: JSON.stringify({
            tipo,
            po,
            email,
            motivo,
            comentarios
          })
        });
      } catch (err) {
        // En modo estático/offline la solicitud ya está guardada en localStorage
      }

      avisar(
        `¡Solicitud de ${tipo} para la orden ${po} registrada exitosamente con folio #${nuevaSolicitud.id}!`,
        "success"
      );

      formSolicitud.reset();
      formSolicitud.classList.remove("was-validated");

      // Si el usuario tenía sesión, restaurar el correo
      if (sesion && sesion.correo && emailInput) {
        emailInput.value = sesion.correo;
      }

      // Actualizar la tabla
      cargarTablaSolicitudes();
    });
  }

  // Filtrado de solicitudes por estado
  if (filtroEstado) {
    filtroEstado.addEventListener("change", cargarTablaSolicitudes);
  }

  // Inicializar la tabla al cargar la vista
  cargarTablaSolicitudes();
});
