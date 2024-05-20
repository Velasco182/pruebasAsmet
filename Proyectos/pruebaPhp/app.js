//http://localhost/sena/Proyectos/pruebaPhp/
//Ruta para ejecución del Fetch en este caso el back.php
let ruta = '/sena/Proyectos/pruebaPhp/back.php';
console.log(ruta+'?id=${id}');
let dataLoaded = false;
// Instancia del formulario y boton de crear y actualizar Cliente
let formularioCrear = document.getElementById('miFormulario');
let btnEnviar = document.getElementById('btn-asmet');
let formularioActualizar = document.getElementById('actualizarFormulario');
let btnActualizar = document.getElementById('btn-actualizar');
let btnEliminar = document.getElementById('btn-eliminar');
//Validar formularios
let validarFormularios = (formulario) =>{

  formulario.addEventListener('input', () => {
    if (formulario.checkValidity()) {
  
      btnEnviar.disabled = false;
      btnActualizar.disabled = false;
  
    } else {
  
      btnEnviar.disabled = true;
      btnActualizar.disabled = true;
  
    }
  });

}

validarFormularios(formularioCrear);
validarFormularios(formularioActualizar);

document.addEventListener('DOMContentLoaded', (event) => {
  //#################CREAMOS UN EVENTO DE ESCUCHA EN EL BOTON DE OBTENER#####################
  mostrarDatos();
  //##################CREAMOS UN EVENTO DE ESCUCHA EN EL BOTON DE CREAR######################
  btnEnviar.addEventListener('click', crearCliente);
  //################CREAMOS UN EVENTO DE ESCUCHA EN EL BOTON DE ACTUALIZAR###################
  //btnActualizar.addEventListener('click', actualizarCliente);
  //################CREAMOS UN EVENTO DE ESCUCHA EN EL BOTON DE ELIMINAR###################
  //btnEliminar.addEventListener('click', eliminarCliente);
});
///################# FUNCIÓN FLECHA PARA HACER POST A LA DB #########################
let crearCliente = () => {

  // Recupera los valores del formulario del modal
  let nombre = document.getElementById('nombreCliente').value;
  let apellido = document.getElementById('apellidoCliente').value;
  let telefono = document.getElementById('numeroCliente').value;
  
  dataLoaded = false;

  // Instancia del formulario para poder cerrarlo después de crear un Cliente
  let modalInstance = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
  // Realiza una solicitud POST para crear un nuevo cliente
  fetch(ruta, {

    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ nombre, apellido, telefono })

  })
    .then(response => {
      if (!response.ok) throw new Error('Error al crear cliente');
      return response.json();
    })
    .then(data => {
      // Muestra un mensaje en la consola y refresca los datos en la tabla
      console.table(data);
      // Recargar la página actual con los datos actualizados
      setTimeout(location.reload(true), 50);
      // Cierra el modal
      modalInstance.hide();
    })
    .catch(error => console.error('Error:', error));
}
///################################ CIERRO POST ##################################
///################# FUNCIÓN FLECHA PARA HACER GET A LA DB #########################
let mostrarDatos = () => {

  if (!dataLoaded) {

    fetch(ruta)
      .then(response => {

        if (!response.ok) {
          throw new Error('Sin acceso a internet');
        }
        return response.json();

      })
      .then(data => {

        renderTable(data);
        console.table(data);
        dataLoaded = true;

      })
      .catch(err => console.error('Error al obtener datos: ', err));
  }
}
///################################ CIERRO GET ##################################
///#################### FUNCIÓN FLECHA PARA HACER PUT A LA DB ##########################
let actualizarCliente = (id) => {
  console.log('Editar cliente con ID:', id);
//, nombre, apellido, telefono
  // Recupera los valores del formulario del modal
  let actualizarNombre = document.getElementById('nombreACliente').value;
  let actualizarApellido = document.getElementById('apellidoACliente').value;
  let actualizarTelefono = document.getElementById('numeroACliente').value;
  // Instancia del formulario para poder cerrarlo después de crear un Cliente
  let modalInstance = bootstrap.Modal.getInstance(document.getElementById('actualizarModal'));
  
  fetch(ruta, {

    method: 'PUT',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ id, actualizarNombre, actualizarApellido, actualizarTelefono })

  })
    .then(response => {
      if (!response.ok) throw new Error('Error al actualizar cliente');
      return response.json();
    })
    .then(data => {
      // Muestra un mensaje en la consola y refresca los datos en la tabla
      console.table(data);
      // Recargar la página actual con los datos actualizados
      //location.reload(true);
      // Cierra el modal
      modalInstance.hide();
    })
    .catch(error => console.error('Error:', error));
}
///################################ CIERRO PUT ##################################
///#################### FUNCIÓN FLECHA PARA HACER DELETE A LA DB ##########################
let eliminarCliente = (id) => {
  console.log('Editar cliente con ID:', id);

  if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {

    fetch(`/sena/Proyectos/pruebaPhp/back.php?id=${id}`, 
    {
      method: 'DELETE'
      ,
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ id })

    })
    .then(response => {

      if (!response.ok) throw new Error('Error al eliminar cliente');
      return response.json();

    })
    .then(data => {
      if(data.success){

        console.table(data);
        // Recargar la página actual con los datos actualizados
        location.reload(true);

        }else{

          alert("No se puede eliminar el cliente");
          console.error('No se puede eliminar el cliente', data.message);

        }
      // Muestra un mensaje en la consola y refresca los datos en la tabla
      
      //mostrarDatos();
      // Recargar la página actual con los datos actualizados
      //location.reload(true);
      // Cierra el modal
      //modalInstance.hide();
    })
    .catch(error => console.error('Error:', error));

    //console.log()
  }
}
///################################ CIERRO DELETE ##################################
//#################### RENDERIZAMOS LOS DATOS OBTENIDOS EN UNA TABLA ######################
function renderTable(data) {

  let tbody = document.querySelector('#myTable tbody');
  // Limpiar la tabla antes de renderizar
  tbody.innerHTML = ''; 

  data.forEach(item => {

    let row = document.createElement('tr');

    Object.keys(item).forEach(key => {
      // Ocultar la columna id
      if (key !== 'id') { 

        let cell = document.createElement('td');
        cell.textContent = item[key];
        row.appendChild(cell);

      }

    });

    let id = item.id;

    // Crear la celda para los botones de acción
    let actionCell = document.createElement('td');

    // Botón Editar
    let editButton = document.createElement('button');
    editButton.textContent = '✍️';
    editButton.classList.add('btn', 'btn-primary');
    editButton.setAttribute('type', 'button'); 
    editButton.setAttribute('data-bs-toggle', 'modal');
    editButton.setAttribute('data-bs-target', '#actualizarModal');
    editButton.setAttribute('data-id', id);
    editButton.addEventListener('click', () => actualizarCliente(item.id));
    actionCell.appendChild(editButton);

    //console.log(id);

    // Botón Eliminar
    let deleteButton = document.createElement('button');
    deleteButton.textContent = '✘';
    deleteButton.classList.add('btn', 'btn-danger');
    deleteButton.setAttribute('data-id', id);
    deleteButton.addEventListener('click', () => eliminarCliente(item.id));
    actionCell.appendChild(deleteButton);

    row.appendChild(actionCell);
    tbody.appendChild(row);
  
  });
}

///########################### CIERRO RENDERIZACIÓN DE LA TABLA ############################
