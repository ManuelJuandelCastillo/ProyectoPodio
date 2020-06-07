const selector_equipo = document.getElementById('selector-equipo');
const equipo_nombre = document.getElementById('input-equipo');
const equipo_institucion = document.getElementById('input-institucion');
const equipo_localidad = document.getElementById('input-localidad');
const equipo_direccion = document.getElementById('input-direccion');

const tabla_lista = document.getElementById('tabla-lista');

const torneo = document.getElementById('torneo-js').textContent;

// mostrar datos de equipo
const mostar_info_equipo = (equipo) => {
    let oReq = new XMLHttpRequest();
    oReq.onload = () => {
        let datos_equipo = JSON.parse(oReq.responseText);

        equipo_institucion.value = datos_equipo[0].nombre_institucion;
        equipo_localidad.value = datos_equipo[0].localidad;
        equipo_direccion.value = datos_equipo[0].direccion;
    }
    oReq.open('get', 'AJAXequipos.php?equipo=' + equipo + '&torneo=' + torneo, true);

    oReq.send();
}

// generador de lista de buena fe
const mostrar_lista_fe = (equipo) => {
    let Req2 = new XMLHttpRequest();
    Req2.addEventListener('load', () => {
        let lista_buenafe = JSON.parse(Req2.responseText);

        let tblista = document.createElement("tbody");

        for (let i = 0; i < lista_buenafe.length; i++) {
            let fila = document.createElement("tr");
            let celda1 = document.createElement("td");
            let text1 = document.createTextNode(lista_buenafe[i].documento);
            celda1.appendChild(text1);
            fila.appendChild(celda1);
            let celda2 = document.createElement("td");
            let text2 = document.createTextNode(lista_buenafe[i].apellidos + ', ' + lista_buenafe[i].nombres);
            celda2.appendChild(text2);
            fila.appendChild(celda2);
            let celda3 = document.createElement("td");
            let text3 = document.createElement("a");
            text3.setAttribute("href", "admDatosJugadora.php?dni="+lista_buenafe[i].documento);
            text3.setAttribute("target", "_blank");
            text3.innerHTML = 'ver';
            celda3.appendChild(text3);
            fila.appendChild(celda3);
            tblista.appendChild(fila);
        }

        tabla_lista.appendChild(tblista);

    })
    Req2.open('get', 'AJAXlista.php?equipo=' + equipo + '&torneo=' + torneo, true);

    Req2.send();
}

selector_equipo.addEventListener('change', (e) => {
    if (document.querySelectorAll('tbody')) {
        let tblBody = document.querySelector('tbody');
        tblBody.remove();
    }
    mostar_info_equipo(selector_equipo.value);
    mostrar_lista_fe(selector_equipo.value);
})