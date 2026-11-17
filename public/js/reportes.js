/*
 * reportes.js - Generación dinámica e interactiva del Reporte de Desempeño
 * Calcula pedidos entregados a tiempo, retrasados y cancelados por rango de fechas.
 */
document.addEventListener("DOMContentLoaded", () => {
  const formReporte = document.getElementById("formReporte");
  const inputDesde = document.getElementById("rep-desde");
  const inputHasta = document.getElementById("rep-hasta");

  // Elementos KPI
  const kpiATiempo = document.getElementById("kpiATiempo");
  const kpiRetrasados = document.getElementById("kpiRetrasados");
  const kpiCancelados = document.getElementById("kpiCancelados");
  const kpiTotal = document.getElementById("kpiTotal");
  const kpiEfectividad = document.getElementById("kpiEfectividad");

  // Barras de progreso visual
  const barATiempo = document.getElementById("barATiempo");
  const barRetrasados = document.getElementById("barRetrasados");
  const barCancelados = document.getElementById("barCancelados");

  // Tabla de desglose de pedidos
  const cuerpoTablaReporte = document.getElementById("cuerpoTablaReporte");
  const avisoReporte = document.getElementById("avisoReporte");

  // Token CSRF de Laravel
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");

  // Establecer fechas por defecto si están vacías (últimos 30 días)
  const hoy = new Date();
  const haceUnMes = new Date();
  haceUnMes.setDate(hoy.getDate() - 30);

  if (inputDesde && !inputDesde.value) {
    inputDesde.value = haceUnMes.toISOString().split("T")[0];
  }
  if (inputHasta && !inputHasta.value) {
    inputHasta.value = hoy.toISOString().split("T")[0];
  }

  // Validación: "hasta" no puede ser menor a "desde"
  if (inputDesde && inputHasta) {
    inputDesde.addEventListener("change", () => {
      inputHasta.min = inputDesde.value;
    });
  }

  /**
   * Genera y actualiza la vista con el reporte del rango seleccionado
   */
  async function generarReporte(desde, hasta) {
    let datosReporte = null;

    // 1. Intentar consultar el backend de Laravel
    try {
      const res = await fetch("/reporte-desempeno/generar", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          ...(csrfToken ? { "X-CSRF-TOKEN": csrfToken } : {})
        },
        body: JSON.stringify({ desde, hasta })
      });

      if (res.ok) {
        const json = await res.json();
        if (json.success) {
          datosReporte = {
            metricas: json.metricas,
            pedidos: json.pedidos
          };
        }
      }
    } catch (err) {
      // Backend no disponible: calcular con datos locales
    }

    // 2. Fallback con cálculo local usando Bochito
    if (!datosReporte) {
      datosReporte = Bochito.getReporteDesempeno(desde, hasta);
    }

    actualizarKPIs(datosReporte.metricas);
    actualizarTabla(datosReporte.pedidos);

    if (avisoReporte) {
      avisoReporte.textContent = `Reporte generado exitosamente para el periodo ${desde} al ${hasta}. Total evaluado: ${datosReporte.metricas.total} pedidos.`;
      avisoReporte.className = "alert alert-success mt-3 mb-0 shadow-sm";
      avisoReporte.classList.remove("d-none");
    }
  }

  function actualizarKPIs(metricas) {
    const aTiempo = metricas.entregados_a_tiempo || 0;
    const retrasados = metricas.retrasados || 0;
    const cancelados = metricas.cancelados || 0;
    const total = metricas.total || (aTiempo + retrasados + cancelados);
    const efectividad = metricas.porcentaje_efectividad || (total > 0 ? ((aTiempo / total) * 100).toFixed(1) : "0.0");

    if (kpiATiempo) kpiATiempo.textContent = aTiempo.toLocaleString();
    if (kpiRetrasados) kpiRetrasados.textContent = retrasados.toLocaleString();
    if (kpiCancelados) kpiCancelados.textContent = cancelados.toLocaleString();
    if (kpiTotal) kpiTotal.textContent = total.toLocaleString();
    if (kpiEfectividad) kpiEfectividad.textContent = `${efectividad}%`;

    // Actualizar barras de porcentaje
    const pATiempo = total > 0 ? ((aTiempo / total) * 100).toFixed(1) : 0;
    const pRetrasados = total > 0 ? ((retrasados / total) * 100).toFixed(1) : 0;
    const pCancelados = total > 0 ? ((cancelados / total) * 100).toFixed(1) : 0;

    if (barATiempo) {
      barATiempo.style.width = `${pATiempo}%`;
      barATiempo.textContent = `${pATiempo}%`;
    }
    if (barRetrasados) {
      barRetrasados.style.width = `${pRetrasados}%`;
      barRetrasados.textContent = `${pRetrasados}%`;
    }
    if (barCancelados) {
      barCancelados.style.width = `${pCancelados}%`;
      barCancelados.textContent = `${pCancelados}%`;
    }
  }

  function actualizarTabla(pedidos) {
    if (!cuerpoTablaReporte) return;
    cuerpoTablaReporte.innerHTML = "";

    if (!pedidos || pedidos.length === 0) {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td colspan="7" class="text-center text-muted py-4">
          <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
          No hay pedidos registrados en el rango de fechas seleccionado.
        </td>
      `;
      cuerpoTablaReporte.appendChild(tr);
      return;
    }

    pedidos.forEach((p) => {
      const tr = document.createElement("tr");

      let badgeDesempeno = "";
      if (p.desempeno === "a_tiempo" || p.estatus === "Entregado a tiempo") {
        badgeDesempeno = '<span class="badge text-bg-success"><i class="bi bi-check-circle-fill me-1"></i> A tiempo</span>';
      } else if (p.desempeno === "retrasado" || p.estatus === "Retrasado") {
        badgeDesempeno = '<span class="badge text-bg-warning text-dark"><i class="bi bi-clock-fill me-1"></i> Retrasado</span>';
      } else if (p.desempeno === "cancelado" || p.estatus === "Cancelado") {
        badgeDesempeno = '<span class="badge text-bg-danger"><i class="bi bi-x-circle-fill me-1"></i> Cancelado</span>';
      } else {
        badgeDesempeno = '<span class="badge text-bg-info text-white"><i class="bi bi-hourglass-split me-1"></i> En proceso</span>';
      }

      tr.innerHTML = `
        <td><strong class="text-bochito">${p.po}</strong></td>
        <td>${p.cliente}</td>
        <td>${p.producto} <small class="text-muted">(${p.cantidad})</small></td>
        <td><small>${Bochito.formatoFecha(p.entrega || p.entrega_estimada)}</small></td>
        <td><small class="text-muted">${Bochito.formatoFecha(p.entregaReal || p.entrega_real)}</small></td>
        <td><span class="badge text-bg-${Bochito.COLOR_ESTATUS[p.estatus] || 'secondary'}">${p.estatus}</span></td>
        <td>${badgeDesempeno}</td>
      `;
      cuerpoTablaReporte.appendChild(tr);
    });
  }

  // Evento submit del formulario de fechas
  if (formReporte) {
    formReporte.addEventListener("submit", (e) => {
      e.preventDefault();
      formReporte.classList.add("was-validated");
      if (!formReporte.checkValidity()) return;

      const desde = inputDesde.value;
      const hasta = inputHasta.value;
      generarReporte(desde, hasta);
    });
  }

  // Botones de filtro rápido / atajos
  document.querySelectorAll("[data-preset]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const preset = btn.dataset.preset;
      const hoy = new Date();
      let fInicio = new Date();

      if (preset === "7d") {
        fInicio.setDate(hoy.getDate() - 7);
      } else if (preset === "30d") {
        fInicio.setDate(hoy.getDate() - 30);
      } else if (preset === "year") {
        fInicio = new Date(hoy.getFullYear(), 0, 1);
      } else if (preset === "all") {
        fInicio = new Date("2020-01-01");
      }

      inputDesde.value = fInicio.toISOString().split("T")[0];
      inputHasta.value = hoy.toISOString().split("T")[0];

      // Marcar botón activo
      document.querySelectorAll("[data-preset]").forEach(b => b.classList.remove("active"));
      btn.classList.add("active");

      generarReporte(inputDesde.value, inputHasta.value);
    });
  });

  // Generar reporte inicial
  if (inputDesde && inputHasta) {
    generarReporte(inputDesde.value, inputHasta.value);
  }
});
