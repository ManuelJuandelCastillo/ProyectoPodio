if (document.getElementById('r2')){   
    const resp2 = document.getElementById("r2")
    const resp3 = document.getElementById("r3")
    const resp4 = document.getElementById("r4")

    resp2.addEventListener('click',(e)=>{
        abrirModal("responsable2")
    })
    resp3.addEventListener('click',(e)=>{
        abrirModal("responsable3")
    })
    resp4.addEventListener('click',(e)=>{
        abrirModal("entrenador")
    })
}
    
const cerrar = document.getElementById("btn-cerrar")
    
const modal = document.getElementById("modal-container")

const responsable = document.getElementById('responsable')
const dni = document.getElementById('dni')
const nombre = document.getElementById('nombre')
const apellido = document.getElementById('apellido')

function abrirModal(resp_n){    
    modal.style.display='block'
    responsable.setAttribute('value', resp_n)
}



cerrar.addEventListener('click',(e)=>{
   modal.style.display='none'
   responsable.setAttribute('value','')
   dni.value=''
   nombre.value=''
   apellido.value=''
})