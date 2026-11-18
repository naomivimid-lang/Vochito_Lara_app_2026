/*
 * solicitudes.js - Gestión integral de cancelaciones y modificaciones de pedidos
 * Funciona conectado a PostgreSQL (SolicitudController) con respaldo en localStorage (Bochito).
 */
document.addEventListener("DOMContentLoaded", () => {
  const formSolicitud = document.getElementById("formSolicitud");
  const avisoSolicitud = document.getElementById("avisoSolicitud");
  const tablaSolicitudes = document.getElementById("tablaSolicitudes");
  const cuerpoTabla = document.getElementById("cuerpoSolicitudes");
  const filtroEstado = document.getElementById("filtroEstado");
  const contadorSolicitudes = document.getElementById("contadorSolicitudes");
  const poInput = document.getElementById("po");
  const infoPedido = document.getElementById("infoPedido");
  const EMBARCADO = "En tránsito";

  const dbReady = tablaSolicitudes?.dataset?.dbReady === "1";

  // Determinar si esta página es de cancelación o modificación de forma robusta
  const accion = formSolicitud ? (formSolicitud.dataset.accion || "") : "";
  const tipoAtributo = formSolicitud ? (formSolicitud.dataset.tipo || "") : "";
  const tipoPagina = (tipoAtributo === "modificacion" || accion === "modificar" || accion === "modificacion")
    ? "modificacion"
    : "cancelacion";

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");

  function avisar(texto, tipo = "success") {
    if (!avisoSolicitud) return;
    const icono = tipo === "success"
      ? '<i class="bi bi-check-circle-fill me-2 fs-5"></i>'
      : (tipo === "danger" ? '<i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>' : '<i class="bi bi-info-circle-fill me-2 fs-5"></i>');
    avisoSolicitud.innerHTML = `<div class="d-flex align-items-center">${icono}<div>${texto}</div></div>`;
    avisoSolicitud.className = `alert alert-${tipo} mb-4 shadow-sm`;
    avisoSolicitud.classList.remove("d-none");
    avisoSolicitud.scrollIntoView({ behavior: "smooth", block: "nearest" });
  }

  // Pre-llenar correo si hay sesión activa en prototipo
  const sesion = (typeof Bochito !== "undefined" && Bochito.getSesion) ? Bochito.getSesion() : null;
  const inputEmail = document.getElementById("email") || document.getElementById("mod-email");
  if (sesion && sesion.correo && inputEmail && !inputEmail.value) {
    inputEmail.value = sesion.correo;
  }

  // Búsqueda en vivo al escribir la orden de compra (PO)
  if (poInput && infoPedido) {
    poInput.addEventListener("input", () => {
      const val = poInput.value.trim().toUpperCase();
      if (val.length < 4) {
        infoPedido.className = "mt-2 small d-none";
        infoPedido.innerHTML = "";
        return;
      }

      if (!dbReady && typeof Bochito !== "undefined" && Bochito.getPedidos) {
        const pedido = Bochito.getPedidos().find(p => p.po === val);
        if (pedido) {
          const esEmbarcado = pedido.estatus === EMBARCADO;
          const alertClass = esEmbarcado ? "danger" : "info";
          const badgeColor = esEmbarcado ? "danger" : (pedido.estatus === "Cancelado" ? "secondary" : "success");

          infoPedido.className = `alert alert-${alertClass} p-2 mt-2 mb-0 shadow-sm`;
          infoPedido.innerHTML = `
            <div><strong><i class="bi bi-box-seam me-1"></i> Pedido encontrado:</strong> ${pedido.cliente} &bull; ${pedido.producto} (${pedido.cantidad} uds.)</div>
            <div class="mt-1">Estatus actual: <span class="badge text-bg-${badgeColor}">${pedido.estatus}</span></div>
            ${esEmbarcado ? `<div class="mt-1 text-danger fw-semibold"><i class="bi bi-exclamation-octagon me-1"></i> Este pedido ya se encuentra "${EMBARCADO}" y no permite ${tipoPagina === 'cancelacion' ? 'cancelación' : 'modificación'}.</div>` : ''}
          `;
          return;
        }
      }

      if (/^[Pp][Oo]-([0-9]{4}-)?[0-9]{4}$/i.test(val)) {
        infoPedido.className = "alert alert-secondary p-2 mt-2 mb-0";
        infoPedido.innerHTML = `<i class="bi bi-info-circle me-1"></i> Orden <strong>${val}</strong>: Lista para registrar la solicitud.`;
      } else {
        infoPedido.className = "mt-2 small d-none";
        infoPedido.innerHTML = "";
      }
    });
  }

  function renderizarFilas(solicitudes) {
    if (!cuerpoTabla) return;

    const estadoSeleccionado = filtroEstado ? filtroEstado.value : "";
    const filtradas = estadoSeleccionado
      ? solicitudes.filter(s => s.estado === estadoSeleccionado)
      : solicitudes;

    if (contadorSolicitudes) {
      contadorSolicitudes.textContent = filtradas.length;
    }

    cuerpoTabla.innerHTML = "";
    const etiquetaNombre = tipoPagina === "cancelacion" ? "cancelación" : "modificación";

    if (filtradas.length === 0) {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td colspan="7" class="text-center text-muted py-4">
          <i class="bi bi-inbox fs-3 d-block mb-2 text-teal"></i>
          No hay solicitudes de ${etiquetaNombre} registradas ${estadoSeleccionado ? `con estatus "${estadoSeleccionado}"` : ""}.
        </td>
      `;
      cuerpoTabla.appendChild(tr);
      return;
    }

    filtradas.forEach((sol) => {
      const tr = document.createElement("tr");
      const badgeClase = (typeof Bochito !== "undefined" && Bochito.COLOR_ESTADO_SOLICITUD)
        ? (Bochito.COLOR_ESTADO_SOLICITUD[sol.estado] || "secondary")
        : (sol.estado === "Aprobada" ? "success" : (sol.estado === "Rechazada" ? "danger" : "warning"));

      const fechaStr = (typeof Bochito !== "undefined" && Bochito.formatoFecha)
        ? Bochito.formatoFecha(sol.fecha || sol.created_at)
        : (sol.fecha || sol.created_at || "—");

      tr.innerHTML = `
        <td class="fw-bold text-secondary">#${sol.id}</td>
        <td><span class="badge text-bg-light border">${sol.po}</span></td>
        <td><small class="text-muted">${sol.email}</small></td>
        <td>
          <div class="fw-semibold text-truncate" style="max-width: 200px;" title="${sol.motivo || ''}">
            ${sol.motivo || 'General'}
          </div>
          <small class="text-muted text-truncate d-block" style="max-width: 230px;" title="${sol.comentarios}">
            ${sol.comentarios}
          </small>
        </td>
        <td><small>${fechaStr}</small></td>
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
                <button type="button" class="btn btn-outline-secondary btn-cambiar-estado" data-id="${sol.id}" title="Reabrir / Cambiar a Pendiente">
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
   * Carga las solicitudes desde PostgreSQL o desde localStorage en modo offline
   */
  async function cargarTablaSolicitudes() {
    if (!cuerpoTabla) return;

    if (dbReady) {
      try {
        const res = await fetch(`/solicitudes?tipo=${tipoPagina}`, {
          headers: { "Accept": "application/json" }
        });
        if (res.ok) {
          const json = await res.json();
          if (json.success && Array.isArray(json.solicitudes)) {
            renderizarFilas(json.solicitudes);
            return;
          }
        }
      } catch (e) {
        // Fallback abajo si falla red
      }
    }

    const solicitudesLocales = (typeof Bochito !== "undefined" && Bochito.getSolicitudes)
      ? Bochito.getSolicitudes(tipoPagina)
      : [];
    renderizarFilas(solicitudesLocales);
  }

  function asignarEventosAcciones() {
    document.querySelectorAll(".btn-aprobar").forEach(btn => {
      btn.addEventListener("click", () => {
        actualizarEstadoSolicitud(btn.dataset.id, "Aprobada", "Solicitud aprobada por el área administrativa.");
      });
    });

    document.querySelectorAll(".btn-rechazar").forEach(btn => {
      btn.addEventListener("click", () => {
        const motivo = prompt("Motivo del rechazo de la solicitud (opcional):", "No cumple con las políticas de tiempo o embarque.");
        if (motivo !== null) {
          actualizarEstadoSolicitud(btn.dataset.id, "Rechazada", motivo);
        }
      });
    });

    document.querySelectorAll(".btn-cambiar-estado").forEach(btn => {
      btn.addEventListener("click", () => {
        actualizarEstadoSolicitud(btn.dataset.id, "Pendiente", "Solicitud reabierta para revisión.");
      });
    });

    document.querySelectorAll(".btn-eliminar").forEach(btn => {
      btn.addEventListener("click", () => {
        if (confirm(`¿Estás seguro de que deseas eliminar permanentemente la solicitud para la orden ${btn.dataset.po}?`)) {
          eliminarSolicitud(btn.dataset.id);
        }
      });
    });
  }

  async function actualizarEstadoSolicitud(id, nuevoEstado, respuestaAdmin = null) {
    if (dbReady) {
      try {
        const res = await fetch(`/solicitudes/${id}/estado`, {
          method: "PATCH",
          headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            ...(csrfToken ? { "X-CSRF-TOKEN": csrfToken } : {})
          },
          body: JSON.stringify({ estado: nuevoEstado, respuesta_admin: respuestaAdmin })
        });
        const json = await res.json().catch(() => ({}));
        if (!res.ok) {
          return avisar(json.message || "No se pudo actualizar el estado.", "danger");
        }
        await cargarTablaSolicitudes();
        return avisar(`Solicitud #${id} actualizada a <strong>${nuevoEstado}</strong>.`, nuevoEstado === "Aprobada" ? "success" : "warning");
      } catch (e) {
        return avisar("Error de conexión al actualizar la solicitud.", "danger");
      }
    }

    if (typeof Bochito !== "undefined" && Bochito.updateSolicitudEstado) {
      Bochito.updateSolicitudEstado(id, nuevoEstado, respuestaAdmin);
    }
    await cargarTablaSolicitudes();
    avisar(`Solicitud #${id} actualizada a <strong>${nuevoEstado}</strong>.`, nuevoEstado === "Aprobada" ? "success" : "warning");
  }

  async function eliminarSolicitud(id) {
    if (dbReady) {
      try {
        const res = await fetch(`/solicitudes/${id}`, {
          method: "DELETE",
          headers: {
            "Accept": "application/json",
            ...(csrfToken ? { "X-CSRF-TOKEN": csrfToken } : {})
          }
        });
        if (!res.ok) {
          return avisar("No se pudo eliminar la solicitud.", "danger");
        }
        await cargarTablaSolicitudes();
        return avisar(`La solicitud #${id} fue eliminada correctamente.`, "info");
      } catch (e) {
        return avisar("Error de conexión al eliminar la solicitud.", "danger");
      }
    }

    if (typeof Bochito !== "undefined" && Bochito.eliminarSolicitud) {
      Bochito.eliminarSolicitud(id);
    }
    await cargarTablaSolicitudes();
    avisar(`La solicitud #${id} fue eliminada correctamente.`, "info");
  }

  /**
   * Envío del formulario de solicitud
   */
  if (formSolicitud) {
    formSolicitud.addEventListener("submit", async (e) => {
      e.preventDefault();
      formSolicitud.classList.add("was-validated");

      if (!formSolicitud.checkValidity()) {
        avisar("Por favor completa todos los campos requeridos correctamente.", "danger");
        return;
      }

      const po = poInput ? poInput.value.trim().toUpperCase() : "";
      const email = inputEmail ? inputEmail.value.trim() : "";

      const reasonSelect = document.getElementById("reason");
      const motivo = reasonSelect ? reasonSelect.options[reasonSelect.selectedIndex]?.value : "Modificación solicitada";

      const commentsInput = document.getElementById("comments") || document.getElementById("changes");
      const comentarios = commentsInput ? commentsInput.value.trim() : "";

      if (dbReady) {
        try {
          const respuesta = await fetch("/solicitudes", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              ...(csrfToken ? { "X-CSRF-TOKEN": csrfToken } : {})
            },
            body: JSON.stringify({
              tipo: tipoPagina,
              po,
              email,
              motivo,
              comentarios
            })
          });

          const json = await respuesta.json().catch(() => ({}));
          if (!respuesta.ok) {
            const primerError = json.message || Object.values(json.errors || {})[0]?.[0] || "Error de validación.";
            return avisar(primerError, "danger");
          }

          const folio = json.solicitud?.id ? `#${json.solicitud.id}` : "";
          avisar(
            `¡Solicitud de ${tipoPagina === 'cancelacion' ? 'cancelación' : 'modificación'} para la orden <strong>${po}</strong> registrada exitosamente ${folio}!`,
            "success"
          );

          formSolicitud.reset();
          formSolicitud.classList.remove("was-validated");
          if (sesion && sesion.correo && inputEmail) inputEmail.value = sesion.correo;
          if (infoPedido) {
            infoPedido.className = "mt-2 small d-none";
            infoPedido.innerHTML = "";
          }
          await cargarTablaSolicitudes();
          return;
        } catch (err) {
          return avisar("No se pudo conectar con el servidor para guardar la solicitud.", "danger");
        }
      }

      // Modo offline (sin tabla en BD)
      if (typeof Bochito !== "undefined" && Bochito.getPedidos) {
        const pedido = Bochito.getPedidos().find((p) => p.po === po);
        if (pedido && pedido.estatus === EMBARCADO) {
          return avisar(
            `El pedido <strong>${po}</strong> ya fue embarcado ("${EMBARCADO}"), por lo que no es posible procesar la solicitud.`,
            "danger"
          );
        }
      }

      let nuevaSolicitud = null;
      if (typeof Bochito !== "undefined" && Bochito.saveSolicitud) {
        nuevaSolicitud = Bochito.saveSolicitud({
          tipo: tipoPagina,
          po,
          email,
          motivo,
          comentarios,
        });
      }

      const folio = nuevaSolicitud ? `#${nuevaSolicitud.id}` : "";
      avisar(
        `¡Solicitud de ${tipoPagina === 'cancelacion' ? 'cancelación' : 'modificación'} para la orden <strong>${po}</strong> registrada exitosamente ${folio}!`,
        "success"
      );

      formSolicitud.reset();
      formSolicitud.classList.remove("was-validated");
      if (sesion && sesion.correo && inputEmail) inputEmail.value = sesion.correo;
      if (infoPedido) {
        infoPedido.className = "mt-2 small d-none";
        infoPedido.innerHTML = "";
      }
      await cargarTablaSolicitudes();
    });
  }

  if (filtroEstado) {
    filtroEstado.addEventListener("change", cargarTablaSolicitudes);
  }

  cargarTablaSolicitudes();
});
