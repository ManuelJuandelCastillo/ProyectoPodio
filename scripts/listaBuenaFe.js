if (document.getElementById('btn-agregar-jugadora')) {
    const agregar = document.getElementById('btn-agregar-jugadora');

    agregar.addEventListener('click', (e) => {
        abrirModal();
    })
}
const cerrar = document.getElementById('btn-cerrar');
const modal = document.getElementById('modal-container');

// inputs del modal
let dniFiltro = document.getElementById('dniFiltro');
let nombreFiltro = document.getElementById('nombreFiltro');
let apellidoFiltro = document.getElementById('apellidoFiltro');

dniFiltro.addEventListener("keyup", (e) => {
    let buscaDni = dniFiltro.value;
    // console.log(buscaDni)
    if (buscaDni == '') {
        nombreFiltro.value = '';
        apellidoFiltro.value = '';
    } else {
        fetch(`AJAXpersonas.php?dni=${buscaDni}`).then(res => res.json()).then(data => {
            allJugadoras = data
            // console.log(allJugadoras)
            if (allJugadoras.length == 0) {
                nombreFiltro.value = '';
                apellidoFiltro.value = '';
            } else {
                for (jugadoras of allJugadoras) {
                    // console.log(jugadoras.nombres)
                    nombreFiltro.value = jugadoras.nombres;
                    apellidoFiltro.value = jugadoras.apellidos;
                }
            }
        })
    }
})

const abrirModal = () => {
    modal.style.display = 'block';
}

cerrar.addEventListener('click', (e) => {
    modal.style.display = 'none';
    dniFiltro.value = '';
    nombreFiltro.value = '';
    apellidoFiltro.value = '';
})