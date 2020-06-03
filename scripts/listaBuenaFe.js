if (document.getElementById('btn-agregar-jugadora')) {
    const agregar = document.getElementById('btn-agregar-jugadora')

    agregar.addEventListener('click',(e)=>{
        abrirModal()
    })
}
const cerrar = document.getElementById('btn-cerrar')
const modal = document.getElementById('modal-container')

const dni = document.getElementById('dni')
const nombre = document.getElementById('nombre')
const apellido = document.getElementById('apellido')

function abrirModal(){
    modal.style.display='block'
}

cerrar.addEventListener('click',(e)=>{
    modal.style.display='none'
    dni.value=''
    nombre.value=''
    apellido.value=''
})