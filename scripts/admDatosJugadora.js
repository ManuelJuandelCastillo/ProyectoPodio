// agrandar y achicar fotos al hacer click
if(document.getElementById('foto1')){
    const foto1 = document.getElementById('foto1');
    
    foto1.addEventListener('click',(e)=>{
        if(foto1.style.width=='440px'){
            foto1.style.width='220px';
            foto1.style.position='relative';
            foto1.style.zIndex='0';
        }else{
            foto1.style.width='440px';
            foto1.style.position='absolute';
            foto1.style.bottom='0';
            foto1.style.zIndex='1';
        }
    })
}
if(document.getElementById('foto2')){
    const foto2 = document.getElementById('foto2');
    
    foto2.addEventListener('click',(e)=>{
        if(foto2.style.width=='440px'){
            foto2.style.width='220px';
            foto2.style.position='relative';
            foto2.style.zIndex='0';
        }else{  
            foto2.style.width='440px';
            foto2.style.position='absolute';
            foto2.style.bottom='0';
            foto2.style.zIndex='1';
        }
    })
}
if(document.getElementById('foto3')){
    const foto3 = document.getElementById('foto3');
    
    foto3.addEventListener('click',(e)=>{
        if(foto3.style.width=='440px'){
            foto3.style.width='220px';
            foto3.style.position='relative';
            foto3.style.zIndex='0';
        }else{
            foto3.style.width='440px';
            foto3.style.position='absolute';
            foto3.style.bottom='0';
            foto3.style.zIndex='1';
        }
    })
}

// ajax personas
const input_busqueda = document.getElementById('dni');
const selector = document.getElementById('selector-jugadora');

input_busqueda.addEventListener('keyup', (e)=>{
    let busqueda = input_busqueda.value;
    if (busqueda == ''){
        selector.style.display = 'none';
    } else {
        fetch('AJAXbusquedaADM.php?dni='+busqueda).then(res=>res.json()).then(data=>{
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

selector.addEventListener('change', (e)=>{
    input_busqueda.value = selector.value;
    selector.style.display = 'none';
})