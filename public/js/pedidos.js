/*
 * pedidos.js - RF02 (registro de pedidos) y RF05 (registro de incidencias)
 * Los pedidos e incidencias se guardan en PostgreSQL mediante Laravel.
 * Se conserva Bochito para las funciones de sesión y presentación.
 */

const sesion = Bochito.requerirSesion();

if (sesion) {
    Bochito.activarBarraSesion(sesion);
}

// Token CSRF para las peticiones a Laravel
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Pedidos cargados desde PostgreSQL
let pedidosActuales = [];

const formPedido = document.getElementById("formPedido");
const tabla = document.getElementById("tablaPedidos");
const mensaje = document.getElementById("mensaje");
const modal = new bootstrap.Modal(document.getElementById("modalIncidencia"));
const formIncidencia = document.getElementById("formIncidencia");

let pedidoIncidencia = null;

// Campos del pedido
const campos = ["cliente", "producto", "cantidad", "entrega"].map((id) =>
    document.getElementById(id),
);

// Barra de progreso
const progreso = document.getElementById("progresoPedido");
const barraProgreso = progreso.querySelector(".progress-bar");
const textoProgreso = document.getElementById("textoProgreso");

const CLASES_ANCHO = ["w-0", "w-25", "w-50", "w-75", "w-100"];

// Fecha y hora local
function ahoraLocal() {
    const d = new Date();
    d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
    return d.toISOString().slice(0, 16);
}

// Mostrar mensajes
function mostrarMensaje(texto, tipo) {
    mensaje.textContent = texto;
    mensaje.className = `alert alert-${tipo}`;
}

// Mostrar errores del servidor
function mostrarError(data, respuesta) {
    if (respuesta.status === 419) {
        return "La sesión o el token de seguridad expiró. Recarga la página.";
    }

    if (data.errors) {
        return Object.values(data.errors).flat().join(" ");
    }

    return data.message || "Ocurrió un error al comunicarse con el servidor.";
}

// Consultar pedidos guardados en PostgreSQL
async function cargarPedidos() {
    const respuesta = await fetch("/pedidos/datos", {
        headers: {
            Accept: "application/json",
        },
    });

    const data = await respuesta.json();

    if (!respuesta.ok) {
        throw new Error(mostrarError(data, respuesta));
    }

    pedidosActuales = data;
    dibujarTabla();
}

// Dibujar tabla
function dibujarTabla() {
    tabla.innerHTML = "";

    pedidosActuales.forEach((p) => {
        const fila = document.createElement("tr");

        fila.append(
            Bochito.celda(p.po),
            Bochito.celda(p.cliente),
            Bochito.celda(p.producto),
            Bochito.celda(p.cantidad),
            Bochito.celda(Bochito.formatoFecha(p.entrega)),
            Bochito.celdaEstatus(p.estatus),
        );

        const acciones = document.createElement("td");
        acciones.className = "text-end text-nowrap";

        const incidencias = Array.isArray(p.incidencias) ? p.incidencias : [];

        if (incidencias.length > 0) {
            const aviso = document.createElement("span");
            aviso.className = "badge text-bg-warning me-2";
            aviso.textContent = `${incidencias.length} incidencia(s)`;
            acciones.appendChild(aviso);
        }

        const btn = document.createElement("button");
        btn.className = "btn btn-outline-primary btn-sm";
        btn.dataset.id = p.id;
        btn.innerHTML =
            '<i class="fa-solid fa-triangle-exclamation"></i> Incidencia';

        acciones.appendChild(btn);
        fila.appendChild(acciones);
        tabla.appendChild(fila);
    });
}

// Fecha mínima para registrar un pedido
document.getElementById("entrega").min = ahoraLocal();

// Validación de campos
function campoValido(c) {
    let ok = c.checkValidity() && c.value.trim() !== "";

    if (c.id === "cantidad") {
        ok = ok && Number.isInteger(Number(c.value)) && Number(c.value) > 0;
    }

    if (c.id === "entrega") {
        ok = ok && new Date(c.value) > new Date();
    }

    return ok;
}

// Actualizar barra de progreso
function actualizarProgreso() {
    const completos = campos.filter(campoValido).length;
    const porcentaje = (completos / campos.length) * 100;

    barraProgreso.classList.remove(...CLASES_ANCHO);
    barraProgreso.classList.add(CLASES_ANCHO[completos]);
    barraProgreso.classList.toggle("bg-success", completos === campos.length);

    progreso.setAttribute("aria-valuenow", porcentaje);
    textoProgreso.textContent = `${porcentaje} % completado`;
}

// Validación mientras se escribe
function alEscribirPedido(e) {
    if (!campos.includes(e.target)) return;

    if (e.target.classList.contains("is-invalid") && campoValido(e.target)) {
        e.target.classList.remove("is-invalid");
    }

    actualizarProgreso();
}

// Validación al salir del campo
function alSalirDeCampo(e) {
    if (!campos.includes(e.target)) return;

    e.target.classList.toggle("is-invalid", !campoValido(e.target));
}

// Limpiar formulario
function alLimpiarPedido() {
    campos.forEach((c) => c.classList.remove("is-invalid"));
    setTimeout(actualizarProgreso, 0);
}

// RF02: registrar pedido en PostgreSQL
async function alEnviarPedido(e) {
    e.preventDefault();

    let valido = true;

    campos.forEach((c) => {
        const ok = campoValido(c);
        c.classList.toggle("is-invalid", !ok);
        valido = valido && ok;
    });

    if (!valido) {
        mostrarMensaje("Revisa los campos marcados.", "danger");
        return;
    }

    const [cliente, producto, cantidad, entrega] = campos;

    try {
        const respuesta = await fetch("/pedidos", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                cliente: cliente.value.trim(),
                producto: producto.value.trim(),
                cantidad: Number(cantidad.value),
                entrega: entrega.value,
            }),
        });

        const data = await respuesta.json();

        if (!respuesta.ok) {
            throw new Error(mostrarError(data, respuesta));
        }

        formPedido.reset();
        campos.forEach((c) => c.classList.remove("is-invalid"));

        await cargarPedidos();

        mostrarMensaje(
            `Pedido ${data.pedido.po} registrado con estatus "${data.pedido.estatus}".`,
            "success",
        );
    } catch (error) {
        mostrarMensaje(error.message, "danger");
    }
}

formPedido.addEventListener("submit", alEnviarPedido);
formPedido.addEventListener("input", alEscribirPedido);
formPedido.addEventListener("focusout", alSalirDeCampo);
formPedido.addEventListener("reset", alLimpiarPedido);

// RF05: abrir modal de incidencia
function abrirIncidencia(pedido) {
    pedidoIncidencia = pedido;

    document.getElementById("incidenciaPedido").textContent =
        `${pedido.po} · ${pedido.cliente} · ${pedido.producto}`;

    formIncidencia.reset();

    formIncidencia.querySelectorAll(".is-invalid").forEach((c) => {
        c.classList.remove("is-invalid");
    });

    document.getElementById("nuevaFecha").min = ahoraLocal();

    modal.show();
}

// Buscar pedido seleccionado en la tabla
function alClicTabla(e) {
    const btn = e.target.closest("button[data-id]");

    if (!btn) return;

    const pedido = pedidosActuales.find((p) => p.id === Number(btn.dataset.id));

    if (pedido) abrirIncidencia(pedido);
}

// RF05: guardar incidencia en PostgreSQL
async function alEnviarIncidencia(e) {
    e.preventDefault();

    if (!pedidoIncidencia) return;

    const tipo = document.getElementById("tipoIncidencia");
    const causa = document.getElementById("causaIncidencia");
    const fecha = document.getElementById("nuevaFecha");

    const oks = [
        tipo.value !== "",
        causa.value.trim().length >= 5,
        fecha.value !== "" && new Date(fecha.value) > new Date(),
    ];

    [tipo, causa, fecha].forEach((c, i) => {
        c.classList.toggle("is-invalid", !oks[i]);
    });

    if (!oks.every(Boolean)) return;

    try {
        const respuesta = await fetch(
            `/pedidos/${pedidoIncidencia.id}/incidencias`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    tipo: tipo.value,
                    causa: causa.value.trim(),
                    nuevaFecha: fecha.value,
                }),
            },
        );

        const data = await respuesta.json();

        if (!respuesta.ok) {
            throw new Error(mostrarError(data, respuesta));
        }

        const po = pedidoIncidencia.po;

        modal.hide();
        pedidoIncidencia = null;

        await cargarPedidos();

        mostrarMensaje(
            `Incidencia registrada en ${po}. Se notificará al cliente.`,
            "warning",
        );
    } catch (error) {
        mostrarMensaje(error.message, "danger");
    }
}

tabla.addEventListener("click", alClicTabla);
formIncidencia.addEventListener("submit", alEnviarIncidencia);

// Inicializar página
actualizarProgreso();

if (sesion) {
    cargarPedidos().catch((error) => {
        mostrarMensaje(error.message, "danger");
    });
}
