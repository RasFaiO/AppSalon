document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp() {
    buscarPorFecha();
    alertaEliminar();
}

function buscarPorFecha() {
    const fechaInput = document.querySelector('#fecha');
    fechaInput.addEventListener('input', function(e){
        const fechaSeleccionada = e.target.value;
        // Rerdireccionamos al usuario pasando en la url la fecha
        window.location = `?fecha=${fechaSeleccionada}`;
    });
}

function alertaEliminar() {
    const inputEliminar = document.querySelector('#eliminar');
    // if (resultado.resultado){
    //     Swal.fire({
    //         icon: 'success',
    //         title: 'Cita Creada',
    //         text: 'Tu cita se agendó con éxito',
    //         button: 'OK'
    //     }).then( () => {
    //         setTimeout(() => {
    //             window.location.reload();
    //         }, 2000);
    //     });
    // }
}