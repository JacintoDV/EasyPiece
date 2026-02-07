const usuarioP = document.getElementById('info-usuario-clic');
const registro = document.getElementById('registro-perfil');
const listaRegistro = document.getElementById('cont-info');
const contenedor = document.getElementById('lista-registro');

if (usuarioP && registro && listaRegistro && contenedor) {
    usuarioP.addEventListener('click', function() {
        contenedor.classList.remove('activo');
        contenedor.classList.add('inactivo');
        
        listaRegistro.classList.add('activo');
        listaRegistro.classList.remove('inactivo');
    });

    registro.addEventListener('click', function() {
        listaRegistro.classList.remove('activo');
        listaRegistro.classList.add('inactivo');
        
        contenedor.classList.add('activo');
        contenedor.classList.remove('inactivo');
    });
}

function transformarAInput(spanElemento) {
    if (spanElemento) {
        const nuevoInput = document.createElement('input');
        nuevoInput.value = spanElemento.innerText;
        nuevoInput.className = spanElemento.className;
        nuevoInput.id = spanElemento.id; 
        spanElemento.replaceWith(nuevoInput);
    }
}


function transformarASpan(inputElemento) {
    if (inputElemento) {
        const nuevoSpan = document.createElement('span');
        nuevoSpan.innerText = inputElemento.value; // Toma el texto nuevo que escribió el usuario
        nuevoSpan.className = inputElemento.className;
        nuevoSpan.id = inputElemento.id;
        inputElemento.replaceWith(nuevoSpan);
    }
}


const botonEditar = document.querySelector('.contenedor-informacion button');

botonEditar.addEventListener('click', function() {
    const ids = [
        'editable-telefono', 
        'editable-correo', 
        'editable-direccion', 
        'editable-trajeta'
    ];

    if (this.innerText === "Editar") {
        ids.forEach(id => {
            const span = document.getElementById(id);
            transformarAInput(span);
        });
        this.innerText = "Guardar Cambios";
    } 
    else {
        ids.forEach(id => {
            const input = document.getElementById(id);
            transformarASpan(input);
        });
        this.innerText = "Editar";
    }
});

document.addEventListener("click", (e) => {
  const notificacion = e.target.closest(".contenedor-registro");
  if (!notificacion) return;

  notificacion.classList.toggle("expanded");
  notificacion.classList.toggle("collapsed");
});


