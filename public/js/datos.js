// Conexión de pedidos con PostgreSQL

const sesion = Bochito.requerirSesion();

if (sesion) {
    Bochito.activarBarraSesion(sesion);
}

const ESTADOS = Bochito.ESTADOS;

// Elementos de la página
const tabla = document.getElementById("tablaPedidos");
const sinPedidos = document.getElementById("sinPedidos");
const filtro = document.getElementById("filtroEstatus");
const mensaje = document.getElementById("mensaje");
const modalEl = document.getElementById("modalEstatus");
const modal = new bootstrap.Modal(modalEl);
const formEstatus = document.getElementById("formEstatus");
const selectNuevo = document.getElementById("nuevoEstatus");
const modalPedido = document.getElementById("modalPedido");
const modalError = document.getElementById("modalError");

const filtroPo = document.getElementById("filtroPo");
const filtroDesde = document.getElementById("filtroDesde");
const filtroHasta = document.getElementById("filtroHasta");

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

let pedidos = [];
let pedidoActual = null;
let paginaActual = 1;

// Crear espacio para la paginación
const paginacion = document.createElement("nav");
paginacion.className = "mt-4";
paginacion.setAttribute("aria-label", "Paginación de pedidos");

tabla.closest(".card").insertAdjacentElement("afterend", paginacion);

// Mostrar mensajes
function mostrarMensaje(texto, tipo) {
    mensaje.textContent = texto;
    mensaje.className = `alert alert-${tipo}`;
}

// Llenar los selectores de estatus
function llenarSelects() {
    ESTADOS.forEach((estado) => {
        filtro.add(new Option(estado, estado));
        selectNuevo.add(new Option(estado, estado));
    });
}

// Consultar pedidos en PostgreSQL
async function cargarPedidos(pagina = 1) {
    const desde = filtroDesde.value;
    const hasta = filtroHasta.value;

    const fechasMal = Boolean(desde && hasta && desde > hasta);

    filtroHasta.classList.toggle("is-invalid", fechasMal);

    if (fechasMal) {
        tabla.innerHTML = "";
        paginacion.innerHTML = "";
        sinPedidos.classList.remove("d-none");
        sinPedidos.textContent =
            "La fecha final no puede ser anterior a la inicial.";
        return;
    }

    const parametros = new URLSearchParams();

    parametros.append("po", filtroPo.value.trim());
    parametros.append("estatus", filtro.value);
    parametros.append("desde", desde);
    parametros.append("hasta", hasta);
    parametros.append("page", pagina);

    try {
        const respuesta = await fetch(
            `/productos/datos?${parametros.toString()}`,
            {
                headers: {
                    Accept: "application/json",
                },
            },
        );

        if (!respuesta.ok) {
            throw new Error("No se pudieron consultar los pedidos.");
        }

        const datos = await respuesta.json();

        pedidos = datos.data;
        paginaActual = datos.current_page;

        dibujarTabla();
        dibujarPaginacion(datos);
    } catch (error) {
        console.error(error);
        mostrarMensaje(error.message, "danger");
    }
}

// Dibujar los pedidos en la tabla
function dibujarTabla() {
    tabla.innerHTML = "";

    pedidos.forEach((pedido) => {
        const fila = document.createElement("tr");

        fila.append(
            Bochito.celda(pedido.po),
            Bochito.celda(pedido.cliente),
            Bochito.celda(pedido.producto),
            Bochito.celda(pedido.cantidad),
            Bochito.celda(Bochito.formatoFecha(pedido.entrega)),
            Bochito.celdaEstatus(pedido.estatus),
        );

        const acciones = document.createElement("td");
        acciones.className = "text-end";

        const boton = document.createElement("button");
        boton.type = "button";
        boton.className = "btn btn-outline-primary btn-sm";
        boton.dataset.id = pedido.id;
        boton.textContent = "Actualizar estatus";

        acciones.appendChild(boton);
        fila.appendChild(acciones);
        tabla.appendChild(fila);
    });

    sinPedidos.classList.toggle("d-none", pedidos.length > 0);
}

// Dibujar controles de paginación
function dibujarPaginacion(datos) {
    paginacion.innerHTML = "";

    if (datos.last_page <= 1) {
        return;
    }

    const lista = document.createElement("ul");
    lista.className = "pagination justify-content-center";

    function agregarBoton(texto, pagina, deshabilitado, activo = false) {
        const elemento = document.createElement("li");
        elemento.className = `page-item ${deshabilitado ? "disabled" : ""} ${activo ? "active" : ""}`;

        const boton = document.createElement("button");
        boton.type = "button";
        boton.className = "page-link";
        boton.textContent = texto;
        boton.disabled = deshabilitado;

        boton.addEventListener("click", () => cargarPedidos(pagina));

        elemento.appendChild(boton);
        lista.appendChild(elemento);
    }

    agregarBoton("Anterior", datos.current_page - 1, !datos.prev_page_url);

    for (let i = 1; i <= datos.last_page; i++) {
        agregarBoton(i, i, false, i === datos.current_page);
    }

    agregarBoton("Siguiente", datos.current_page + 1, !datos.next_page_url);

    paginacion.appendChild(lista);

    const resumen = document.createElement("p");
    resumen.className = "text-center text-muted small";
    resumen.textContent = `Mostrando ${datos.from ?? 0} a ${datos.to ?? 0} de ${datos.total} pedidos`;

    paginacion.appendChild(resumen);
}

// Abrir modal para actualizar estatus
function alClicTabla(e) {
    const boton = e.target.closest("button[data-id]");

    if (!boton) return;

    pedidoActual = pedidos.find(
        (pedido) => pedido.id === Number(boton.dataset.id),
    );

    if (!pedidoActual) return;

    modalPedido.textContent = `${pedidoActual.po} · ${pedidoActual.producto} (actual: ${pedidoActual.estatus})`;

    selectNuevo.value = pedidoActual.estatus;
    modalError.classList.add("d-none");

    modal.show();
}

// Guardar el nuevo estatus en PostgreSQL
async function actualizarEstatusAPI(id, estatus) {
    if (!csrfToken) {
        throw new Error("No se encontró el token de seguridad CSRF.");
    }

    const respuesta = await fetch(`/pedidos/${id}/estatus`, {
        method: "PATCH",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({ estatus }),
    });

    const body = await respuesta.json();

    return {
        status: respuesta.status,
        body,
    };
}

// Evento para guardar el estatus
async function alEnviarEstatus(e) {
    e.preventDefault();

    const nuevo = selectNuevo.value;

    if (!ESTADOS.includes(nuevo)) {
        modalError.textContent = "Selecciona un estatus válido.";
        modalError.classList.remove("d-none");
        return;
    }

    try {
        const { status, body } = await actualizarEstatusAPI(
            pedidoActual.id,
            nuevo,
        );

        if (status === 200 && body.exito) {
            modal.hide();

            await cargarPedidos(paginaActual);

            mostrarMensaje(body.mensaje, "success");
        } else {
            modalError.textContent =
                body.message ||
                body.mensaje ||
                "No se pudo actualizar el estatus.";

            modalError.classList.remove("d-none");
        }
    } catch (error) {
        modalError.textContent = error.message;
        modalError.classList.remove("d-none");
    }
}

// Eventos de filtros
function aplicarFiltros() {
    cargarPedidos(1);
}

tabla.addEventListener("click", alClicTabla);
formEstatus.addEventListener("submit", alEnviarEstatus);

filtroPo.addEventListener("input", aplicarFiltros);
filtro.addEventListener("change", aplicarFiltros);
filtroDesde.addEventListener("change", aplicarFiltros);
filtroHasta.addEventListener("change", aplicarFiltros);

// Iniciar pantalla
llenarSelects();

if (sesion) {
    cargarPedidos();
}
