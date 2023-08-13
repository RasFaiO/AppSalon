// inicializamos el paso que vamos a iniciar 
let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    // Agregamos el id para poderlo utilizar al momento de guardar las citas
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

// Cuando todo el DOM esté cargado inicializamos una función
document.addEventListener('DOMContentLoaded', function () {
    // Llamamos la función iniciarApp
    iniciarApp();
});

function iniciarApp() {
    mostrarSeccion(); // Muestra y oculta secciones
    tabs(); //Cambia lasección cuando el usuario de clic al tab correspondiente
    botonesPaginador(); // Agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI(); // Consulta la API en el Backend de PHP

    // Agregamos el id del cliente
    idCliente();
    nombreCliente(); // agrega el nombre del cliente al objeto de cita
    seleccionarFecha(); // agrega la fecha de la cita al objeto de cita
    seleccionarHora(); // agrega la hora de la cita al objeto cita

    mostrarResumen(); // Muestra el resumen de la cita
}

function mostrarSeccion() {
    // oculta la sección con la clase de mostrar
    // Seleccionamos la clase con queryselector
    const seccionAnterior = document.querySelector('.mostrar');
    // en caso de que exista mostrar...
    if (seccionAnterior) {
        // la removemos
        seccionAnterior.classList.remove('mostrar');
    }

    // asignamos a la variable el id del div seleccionado y le inyectamos el paso al que dio clic el usuario
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    // Agregamos la clase al div que tenemos seleccionado
    seccion.classList.add('mostrar');

    // Quita la clase actual
    const tabAnterior = document.querySelector('.actual');
    if (tabAnterior) {
        // la removemos
        tabAnterior.classList.remove('actual');
    }
    // Resalta el tab actual 
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');

}

function tabs() {
    // Asignamos a botones la selección de todos los botones en este caso con querySelectorAll seleccionando la clase tab y como los botones no tienen clase, los eleccionamos por la etiqueta
    const botones = document.querySelectorAll('.tabs button');
    // Iteramos en cada uno de los botones y accedemos a cada uno de los elementos con un arrow function
    botones.forEach( boton => {
        // Registramos un evento, en este caso clic
        boton.addEventListener('click', function(e) {
            paso = parseInt(e.target.dataset.paso);
            mostrarSeccion();
            botonesPaginador();
        });
    });
}

function botonesPaginador() {
    const btnAnterior = document.querySelector('#anterior');
    const btnSiguiente = document.querySelector('#siguiente');

    if (paso === 1) {
        btnAnterior.classList.add('ocultar');
        btnSiguiente.classList.remove('ocultar');
    } else if (paso === 3) {
        btnAnterior.classList.remove('ocultar');
        btnSiguiente.classList.add('ocultar');
        mostrarResumen();
} else {
        btnSiguiente.classList.remove('ocultar');
        btnAnterior.classList.remove('ocultar');
    }
    mostrarSeccion();
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click',function(){
        if(paso <= pasoInicial) return;
        paso--;
        
        botonesPaginador();
    });
}

function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click',function(){
        if(paso >= pasoFinal) return;
        paso++;
        
        botonesPaginador();
    });
}

// Con las funciones async pueden arrancar estas funciones y otras en paralelo
async function consultarAPI() {
    // El Try Catch previene que el código deje de funcionar y nos regresa un mensaje de error para poder solucionar 
    try {
        // Para hacer dinámico el código y no tener el host de nuestro dominio a la vista podemos utilizar location.origin en un templatestring
        // const url = 'http://localhost:3000/api/servicios';
        const url = `${location.origin}/api/servicios`;
        // fetch() es la función que nos permite consumir el servicio que estamos asignando
        const resultado = await fetch(url);
        // traemos los servicios con json
        const servicios = await resultado.json();
        mostrarServicios(servicios);
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios){
    // al ser un array lo recorremos co un foreach
    servicios.forEach( servicio => {
        // aplicamos distroctory
        const {id, nombre, precio} = servicio;
        const nombreServicio = document.createElement('p');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;
        
        const precioServicio = document.createElement('p');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$ ${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function(){
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);
        
        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}

function seleccionarServicio(servicio){
    // Extraemos el id del servicio
    const { id } = servicio;
    // Extraemos los servicios del objeto de citas ya que vamos a estar escribiendo sobre él
    const { servicios } = cita;

    // Identifica el elemento al que se le da clic
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    // Comprobamos si un servicio ya fue agregado o lo podemos quitar
    // some regresa true o false si ya existe el valor en un arreglo
    if ( servicios.some( agregado => agregado.id === id )){
        // si ya esta agregado, lo eliminamos
        // Filter() nos permite sacar un elemento basado en cierta condición
        cita.servicios = servicios.filter( agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    } else {
        // creamos un arreglo que toma la copia de los servicios '...servicios' y le agregamos el nuevo servicio
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }
    // console.log(cita);
}

function idCliente() {
    const id = document.querySelector('#id').value;
    cita.id = id;
}

function nombreCliente() {
    const nombre = document.querySelector('#nombre').value;
    // asignamos el nombre que traemos del usuario en citas campo nombre
    cita.nombre = nombre;
    // console.log(cita);
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    // en la función le pasamos el evento que está consultando el usuario 'e'
    inputFecha.addEventListener('input', function (e) {
        // creamos una variable día la cual instanciaremos con new Date() y le pasaremos a Date() el valor que registra el usuario 'e.target.value', es decir la fecha y utilizamos el metodo getUTCDay el cuál nos retorna el número de día de la semana iniciando en Domingo = 0
        const dia = new Date(e.target.value).getUTCDay();
        // Validamos si el usuario seleccionó sábados o domingos y en caso de que lo haya hecho...
        if ( [0,6].includes(dia)){
            // Si es un día no laboral, no va a traer la info
            e.target.value = '';
            // Llamamos la función que nos va a mostrar una alerta
            mostrarAlerta('Fines de semana no permitidos','error','#paso-2 P');
        } else {
            // en caso de que no sea un día que no trabajan, asignamos el valor a la fecha
            cita.fecha = e.target.value;
        }
    });
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e){
        const horaCita = e.target.value;
        // split() nos permite cortar una cadena de texto, le debemos pasar dónde queremos cortar y nos regresa un arreglo con el resultado
        const hora = horaCita.split(":")[0];
        if(hora < 9 || hora > 18){
            e.target.value = '';
            mostrarAlerta('El horario de atención es entre 9am y 6pm','error','#paso-2 P');
        } else {
            // console.log(cita);
            cita.hora = e.target.value;
        }
    });
}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    // Limpiamos el DIV contenido-resumen
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }
    // Para verificar si un arreglo está vacío podemos utilizar .length
    // Con Object.values() validamos si el objeto que estamos pasando está vacío 
    if (Object.values(cita).includes('') || cita.servicios.length === 0){
        mostrarAlerta('No ha seleccionado ninguna fecha, hora o servicio','error','.contenido-resumen', false);
        return;
        // console.log('Falta');
    }
    // console.log('ok');
    const { nombre, fecha, hora, servicios } = cita;

    // Heading para servicios en resultados
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = "Resumen de Servicios";
    resumen.appendChild(headingServicios);

    // Iterando y mostrando los Servicios
    servicios.forEach(servicio => {

        const {id, precio, nombre} = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });
    
    // Heading para cliente en resultados
    const headingCita = document.createElement('H3');
    headingCita.innerHTML = "Resumen De Cita";
    resumen.appendChild(headingCita);

    // Formatear el div de resumen
    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    //Formatear Fecha en Español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    // en este caso a día le sumamos 2 ya que vamos a utilizar 2 veces una instancia nueva de Date
    const dia = fechaObj.getDate() + 2;
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date ( Date.UTC(year,mes, dia ));
    // Estas opciones se las pasamos al toLocaleDateString para formatear la fecha
    const opciones = { weekday: 'long', year:'numeric', month: 'long', day: 'numeric' }
    const fechaFormateada = fechaUTC.toLocaleDateString('es-CO', opciones);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span> ${hora}`;

    // Boton para crear una Cita
    const botonesReservar = document.createElement('BUTTON');
    botonesReservar.classList.add('boton');
    botonesReservar.textContent = 'Reservar Cita';
    // Función reservarcita()
    botonesReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);

    resumen.appendChild(botonesReservar);
}

async function reservarCita() {
    // Extraemos el objeto de cita
    const { id, nombre, fecha, hora, servicios} = cita;

    // Iteramos sobre los servicios con map, solameete trae las coincidencias que encuentra
    const idServicios = servicios.map(servicio => servicio.id);
    // console.log(idServicios);
    // return;

    // formData() viene siendo el submit pero en JS, debemos enviarlo a una api
    const datos = new FormData();
    // append es la forma en que podremos agregar datos al formData()
    datos.append('usuarioId', id);
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('servicios', idServicios);

    // [...datos] toma la copia del formData() y lo formatea para poder verlo
    // console.log([...datos]);
    // return;
    // Validamos en un try carch si hay errores, en caso de que no exista, realizamos la petición a la api
    try {
        // Para hacer dinámico el código y no tener el host de nuestro dominio a la vista podemos utilizar location.origin en un templatestring
        // Se realiza petición hacia la api
        // const url = 'http://localhost:3000/api/citas';
        const url = `${location.origin}/api/citas`;
        // Podemos utilizar promise() o Asinc away
        const respuesta = await fetch(url,{
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();
        console.log(resultado);

        if (resultado.resultado){
            Swal.fire({
                icon: 'success',
                title: 'Cita Creada',
                text: 'Tu cita se agendó con éxito',
                button: 'OK'
            }).then( () => {
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al guardar la cita',
            button: 'OK'
        });
    }
}

// Le pasamos tres valores en este caso, el mensaje que vamos a mostrar, si es de tipo alerta, erorr... y el elemento en el cuál vamos a mostrar el mensaje
function mostrarAlerta(mensaje, tipo, elemento, timeout = true) {
    // Previene que se genere más de una alerta
    const alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia) {
        alertaPrevia.remove();
    }

    // Scripting para crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);
    
    // vambiamos la variable ya que no vamos utilizar el formulario y le cambiamos el selector
    // const formulario = document.querySelector('#paso-2 P');
    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    // Con setTimeout podemos hacer que después que le pasamos un valor, desaparezca en la cantidad de tiempo programado
    if (timeout){
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
}
