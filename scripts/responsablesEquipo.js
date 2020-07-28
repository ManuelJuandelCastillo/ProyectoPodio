if (document.getElementById('r2')) {
    const resp2 = document.getElementById("r2");
    const resp3 = document.getElementById("r3");
    const resp4 = document.getElementById("r4");

    resp2.addEventListener('click', (e) => {
        abrirModal("responsable2");
    })
    resp3.addEventListener('click', (e) => {
        abrirModal("responsable3");
    })
    resp4.addEventListener('click', (e) => {
        abrirModal("entrenador");
    })
}

const cerrar = document.getElementById("btn-cerrar");

const modal = document.getElementById("modal-container");

const responsable = document.getElementById('responsable');

// INPUT de FORM MODAL-----------------
let dniFiltro = document.getElementById("dniFiltro");
let nombreFiltro = document.getElementById("nombreFiltro");
let apellidoFiltro = document.getElementById("apellidoFiltro");
const emailFiltro = document.getElementById('emailFiltro');
const fechaFiltro = document.getElementById('fechaFiltro');
const selector = document.getElementById('sel');

let allJugadoras = []

// FILTRO QUE LLENA LOS INPUT MODAL
const filtro = () => {
    dniFiltro.value = "";
    nombreFiltro.value = "";
    apellidoFiltro.value = "";

    dniFiltro.addEventListener('keyup', (e)=>{
        let dni = dniFiltro.value;
        if (dni == ''){
            selector.style.display = 'none';
        }else{
            fetch('AJAXpersonas.php?dni='+dni).then(res=>res.json()).then(data=>{
                allJugadoras = data;
                if(allJugadoras.length==0){
                    selector.style.display='none';
                }else{
                    selector.style.display='block';
                    while(selector.firstChild){
                        selector.firstChild.remove();
                    }
                    for(let i=0; i<allJugadoras.length;i++){
                        let fila = document.createElement('option');
                        fila.value = allJugadoras[i].documento;
                        fila.innerHTML = allJugadoras[i].documento+' - '+allJugadoras[i].apellidos+', '+allJugadoras[i].nombres;
    
                        selector.appendChild(fila);
                    }
                }
            })
        }
    })
}
selector.addEventListener('change', (e)=>{
    dni = selector.value;
    fetch('AJAXpersonas.php?dni='+dni).then(res=>res.json()).then(data=>{
        datos = data;
        dniFiltro.value = datos[0].documento;
        nombreFiltro.value = datos[0].nombres;
        apellidoFiltro.value = datos[0].apellidos;
        emailFiltro.value = datos[0].correo_electronico;
        fechaFiltro.value = datos[0].fecha_nacimiento;
        selector.style.display = 'none';
    })
})

function abrirModal(resp_n) {
    modal.style.display = 'block'
    responsable.setAttribute('value', resp_n)
    // DEPENDE DEL RESPONSABLE LLAMA A LA MISMA FUNCION PARA FILTRAR
    if (resp_n == "responsable2") {
        filtro();
    }
    if (resp_n == "responsable3") {
        filtro();
    }
    if (resp_n == "entrenador") {
        filtro();
    }
}

cerrar.addEventListener('click', (e) => {
    modal.style.display = 'none'
    responsable.setAttribute('value', '')
    dniFiltro.value = ''
    nombreFiltro.value = ''
    apellidoFiltro.value = ''

})
