/*
 * datos.js - Datos y sesión compartidos (prototipo y backend mock)
 * Lo usan cancelacion, modificacion, reporte_desempeno, pedidos, productos e iniciosesion.
 */
const Bochito = (() => {
  const ESTADOS = ["Orden recibida", "En fabricación", "Control de calidad", "Empaquetado", "En tránsito", "Entregado a tiempo", "Retrasado", "Cancelado"];
  const COLOR_ESTATUS = {
    "Orden recibida": "secondary",
    "En fabricación": "warning",
    "Control de calidad": "info",
    Empaquetado: "primary",
    "En tránsito": "success",
    "Entregado a tiempo": "success",
    Retrasado: "warning",
    Cancelado: "danger",
  };
  const COLOR_ESTADO_SOLICITUD = {
    Pendiente: "warning",
    Aprobada: "success",
    Rechazada: "danger",
  };

  const CLAVE_PEDIDOS = "bochito_pedidos";
  const CLAVE_SESION = "bochito_sesion";
  const CLAVE_SOLICITUDES = "bochito_solicitudes";

  const PEDIDOS_INICIALES = [
    { id: 1, po: "PO-2026-0001", cliente: "Frutería Don Chuy", producto: "Huacales de madera", cantidad: 120, entrega: "2026-10-15T10:00", entregaReal: "2026-10-15T09:30", estatus: "Orden recibida", desempeno: "a_tiempo", incidencias: [] },
    { id: 2, po: "PO-2026-0002", cliente: "Artesanías Raíces", producto: "Cajas de embalaje", cantidad: 60, entrega: "2026-10-18T12:00", entregaReal: "2026-10-19T14:00", estatus: "En fabricación", desempeno: "retrasado", incidencias: [] },
    { id: 3, po: "PO-2026-0003", cliente: "Abarrotes La Esperanza", producto: "Bolsas de papel kraft", cantidad: 2000, entrega: "2026-10-12T09:00", entregaReal: "2026-10-12T08:50", estatus: "Control de calidad", desempeno: "a_tiempo", incidencias: [] },
    { id: 4, po: "PO-2026-0004", cliente: "Verduras Hernández", producto: "Rejas plásticas", cantidad: 80, entrega: "2026-09-30T16:00", entregaReal: null, estatus: "Empaquetado", desempeno: "cancelado", incidencias: [] },
    { id: 5, po: "PO-2026-0005", cliente: "Cooperativa Sierra Verde", producto: "Etiquetas adhesivas", cantidad: 5000, entrega: "2026-09-20T11:00", entregaReal: "2026-09-20T10:45", estatus: "En tránsito", desempeno: "a_tiempo", incidencias: [] },
  ];

  const SOLICITUDES_INICIALES = [
    {
      id: 1,
      tipo: "cancelacion",
      po: "PO-2026-0001",
      email: "donchuy@fruteria.com",
      motivo: "Demora en tiempo de entrega",
      comentarios: "Se solicita cancelar porque ya no se requerirán los huacales para la temporada.",
      estado: "Pendiente",
      fecha: "2026-10-14T10:20:00",
      respuestaAdmin: null
    },
    {
      id: 2,
      tipo: "modificacion",
      po: "PO-2026-0002",
      email: "contacto@artesaniasraices.com",
      motivo: "Ajuste de volumen",
      comentarios: "Modificar la cantidad de cajas de embalaje de 60 a 90 unidades.",
      estado: "Aprobada",
      fecha: "2026-10-16T14:45:00",
      respuestaAdmin: "Se ajustó el inventario a 90 unidades."
    },
    {
      id: 3,
      tipo: "cancelacion",
      po: "PO-2026-0004",
      email: "pedidos@verdurashernandez.com",
      motivo: "Error en productos seleccionados",
      comentarios: "Orden duplicada por error en el portal.",
      estado: "Aprobada",
      fecha: "2026-09-29T11:00:00",
      respuestaAdmin: "Cancelación procesada y pedido marcado como Cancelado."
    }
  ];

  // ---- Almacenamiento seguro ----
  const leer = (almacen, clave) => {
    try { return JSON.parse(almacen.getItem(clave)); } catch { return null; }
  };
  const guardar = (almacen, clave, valor) => {
    try { almacen.setItem(clave, JSON.stringify(valor)); } catch { /* sin almacenamiento */ }
  };

  // ---- Pedidos ----
  function getPedidos() {
    const guardados = leer(localStorage, CLAVE_PEDIDOS);
    if (Array.isArray(guardados)) return guardados;
    guardar(localStorage, CLAVE_PEDIDOS, PEDIDOS_INICIALES);
    return structuredClone(PEDIDOS_INICIALES);
  }
  function savePedidos(lista) { guardar(localStorage, CLAVE_PEDIDOS, lista); }

  function agregarPedido({ cliente, producto, cantidad, entrega }) {
    const lista = getPedidos();
    const id = lista.reduce((m, p) => Math.max(m, p.id), 0) + 1;
    const pedido = {
      id,
      po: `PO-${new Date().getFullYear()}-${String(id).padStart(4, "0")}`,
      cliente, producto, cantidad, entrega,
      entregaReal: null,
      estatus: ESTADOS[0],
      desempeno: "pendiente",
      incidencias: [],
    };
    lista.push(pedido);
    savePedidos(lista);
    return pedido;
  }

  // ---- Solicitudes (Cancelaciones y Modificaciones) ----
  function getSolicitudes(tipo = null) {
    let lista = leer(localStorage, CLAVE_SOLICITUDES);
    if (!Array.isArray(lista)) {
      lista = structuredClone(SOLICITUDES_INICIALES);
      guardar(localStorage, CLAVE_SOLICITUDES, lista);
    }
    if (tipo) {
      return lista.filter(s => s.tipo === tipo);
    }
    return lista;
  }

  function saveSolicitud({ tipo, po, email, motivo, comentarios }) {
    const lista = getSolicitudes();
    const id = lista.reduce((m, s) => Math.max(m, s.id || 0), 0) + 1;
    const nueva = {
      id,
      tipo,
      po: po.toUpperCase().trim(),
      email: email.trim(),
      motivo: motivo || (tipo === "cancelacion" ? "Sin motivo especificado" : "Modificación de datos"),
      comentarios: comentarios.trim(),
      estado: "Pendiente",
      fecha: new Date().toISOString(),
      respuestaAdmin: null
    };
    lista.unshift(nueva);
    guardar(localStorage, CLAVE_SOLICITUDES, lista);
    return nueva;
  }

  function updateSolicitudEstado(id, nuevoEstado, respuestaAdmin = null) {
    const lista = getSolicitudes();
    const item = lista.find(s => s.id === Number(id));
    if (!item) return null;

    item.estado = nuevoEstado;
    if (respuestaAdmin) item.respuestaAdmin = respuestaAdmin;
    item.fechaResolucion = new Date().toISOString();
    guardar(localStorage, CLAVE_SOLICITUDES, lista);

    // Si se aprueba una cancelación, actualizar el estatus del pedido
    if (item.tipo === "cancelacion" && nuevoEstado === "Aprobada") {
      const pedidos = getPedidos();
      const pedido = pedidos.find(p => p.po === item.po);
      if (pedido) {
        pedido.estatus = "Cancelado";
        pedido.desempeno = "cancelado";
        savePedidos(pedidos);
      }
    }

    return item;
  }

  function eliminarSolicitud(id) {
    const lista = getSolicitudes();
    const filtrada = lista.filter(s => s.id !== Number(id));
    guardar(localStorage, CLAVE_SOLICITUDES, filtrada);
    return true;
  }

  // ---- Reportes de Desempeño ----
  function getReporteDesempeno(desde = null, hasta = null) {
    const pedidos = getPedidos();
    let filtrados = pedidos;

    if (desde || hasta) {
      const dInicio = desde ? new Date(desde + "T00:00:00") : new Date("2000-01-01");
      const dFin = hasta ? new Date(hasta + "T23:59:59") : new Date("2100-01-01");

      filtrados = pedidos.filter(p => {
        const fechaRef = p.entrega ? new Date(p.entrega) : new Date();
        return fechaRef >= dInicio && fechaRef <= dFin;
      });
    }

    let aTiempo = 1180;
    let retrasados = 52;
    let cancelados = 16;

    // Calcular en base a la lista actual de pedidos
    const countATiempo = filtrados.filter(p => p.desempeno === "a_tiempo" || p.estatus === "Entregado a tiempo").length;
    const countRetrasados = filtrados.filter(p => p.desempeno === "retrasado" || p.estatus === "Retrasado").length;
    const countCancelados = filtrados.filter(p => p.desempeno === "cancelado" || p.estatus === "Cancelado").length;

    // Sumar a la base histórica de indicadores
    aTiempo += countATiempo;
    retrasados += countRetrasados;
    cancelados += countCancelados;

    const total = aTiempo + retrasados + cancelados;
    const efectividad = total > 0 ? ((aTiempo / total) * 100).toFixed(1) : "0.0";

    return {
      metricas: {
        entregados_a_tiempo: aTiempo,
        retrasados: retrasados,
        cancelados: cancelados,
        total: total,
        porcentaje_efectividad: efectividad
      },
      pedidos: filtrados
    };
  }

  // ---- Sesión ----
  const getSesion = () => leer(sessionStorage, CLAVE_SESION);
  const iniciarSesion = (usuario) => guardar(sessionStorage, CLAVE_SESION, usuario);
  const cerrarSesion = () => { try { sessionStorage.removeItem(CLAVE_SESION); } catch { /* nada */ } };

  function requerirSesion() {
    const s = getSesion();
    if (!s) { window.location.href = "/iniciar-sesion"; return null; }
    return s;
  }

  // ---- Utilidades de interfaz ----
  const formatoFecha = (iso) => {
    if (!iso) return "—";
    const d = new Date(iso);
    return isNaN(d) ? "—" : d.toLocaleString("es-MX", { dateStyle: "medium", timeStyle: "short" });
  };

  function celda(texto) {
    const td = document.createElement("td");
    td.textContent = texto;
    return td;
  }

  function celdaEstatus(estatus) {
    const td = document.createElement("td");
    const b = document.createElement("span");
    b.className = `badge text-bg-${COLOR_ESTATUS[estatus] || "secondary"}`;
    b.textContent = estatus;
    td.appendChild(b);
    return td;
  }

  function celdaEstadoSolicitud(estado) {
    const td = document.createElement("td");
    const b = document.createElement("span");
    b.className = `badge text-bg-${COLOR_ESTADO_SOLICITUD[estado] || "secondary"}`;
    b.textContent = estado;
    td.appendChild(b);
    return td;
  }

  function activarBarraSesion(sesion) {
    const nombre = document.getElementById("usuarioActual");
    if (nombre) nombre.textContent = sesion.correo;
    const salir = document.getElementById("btnSalir");
    function alCerrarSesion() {
      cerrarSesion();
      window.location.href = "/iniciar-sesion";
    }
    if (salir) salir.addEventListener("click", alCerrarSesion);
  }

  return {
    ESTADOS,
    COLOR_ESTATUS,
    COLOR_ESTADO_SOLICITUD,
    getPedidos,
    savePedidos,
    agregarPedido,
    getSolicitudes,
    saveSolicitud,
    updateSolicitudEstado,
    eliminarSolicitud,
    getReporteDesempeno,
    getSesion,
    iniciarSesion,
    cerrarSesion,
    requerirSesion,
    formatoFecha,
    celda,
    celdaEstatus,
    celdaEstadoSolicitud,
    activarBarraSesion
  };
})();
