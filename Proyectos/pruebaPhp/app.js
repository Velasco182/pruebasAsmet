//http://localhost/sena/Proyectos/pruebaPhp/
//Ruta para ejecución del Fetch en este caso el back.php
let ruta = '/sena/Proyectos/pruebaPhp/back.php';
let dataLoaded = false;
// Instancia del formulario y boton de crear Cliente
let formulario = document.getElementById('miFormulario');
let btnEnviar = document.getElementById('btn-asmet');
// Recupera los valores del formulario del modal
let nombre = document.getElementById('nombreCliente').value;
let apellido = document.getElementById('apellidoCliente').value;
let telefono = document.getElementById('numeroCliente').value;
//Validar formulario
formulario.addEventListener('input', () => {
  if (formulario.checkValidity()) {

    btnEnviar.disabled = false;

  } else {

    btnEnviar.disabled = true;

  }
});
document.addEventListener('DOMContentLoaded', (event) => {
  //#################CREAMOS UN EVENTO DE ESCUCHA EN EL BOTON DE OBTENER#####################
  mostrarDatos()
  //##################CREAMOS UN EVENTO DE ESCUCHA EN EL BOTON DE CREAR######################
  document.getElementById('btn-asmet').addEventListener('click', crearCliente)
  //################CREAMOS UN EVENTO DE ESCUCHA EN EL BOTON DE ACTUALIZAR###################
  document.getElementById('btn-asmet').addEventListener('click', crearCliente)
})
///################# FUNCIÓN FLECHA PARA HACER POST A LA DB #########################
let crearCliente = () => {

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
      location.reload(true);
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
let actualizarCliente = (id, nombre, apellido, telefono) => {

  fetch(ruta, {

    method: 'PUT',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ id, nombre, apellido, telefono })

  })
    .then(response => {
      if (!response.ok) throw new Error('Error al actualizar cliente');
      return response.json();
    })
    .then(data => {
      // Muestra un mensaje en la consola y refresca los datos en la tabla
      console.table(data);
      // Recargar la página actual con los datos actualizados
      location.reload(true);
      // Cierra el modal
      modalInstance.hide();
    })
    .catch(error => console.error('Error:', error));
}
///################################ CIERRO PUT ##################################
///#################### FUNCIÓN FLECHA PARA HACER DELETE A LA DB ##########################
let eliminarCliente = (id) => {

  fetch(ruta, {

    method: 'DELETE',
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
      // Muestra un mensaje en la consola y refresca los datos en la tabla
      console.table(data);
      // Recargar la página actual con los datos actualizados
      location.reload(true);
      // Cierra el modal
      modalInstance.hide();
    })
    .catch(error => console.error('Error:', error));
}
///################################ CIERRO DELETE ##################################
//#################### RENDERIZAMOS LOS DATOS OBTENIDOS EN UNA TABLA ######################
function renderTable(data) {

  let tbody = document.querySelector('#myTable tbody');

  data.forEach(item => {

    let row = document.createElement('tr');

    Object.values(item).forEach(value => {

      let cell = document.createElement('td');
      cell.textContent = value;
      row.appendChild(cell);

    });

    tbody.appendChild(row);

  });
}
///########################### CIERRO RENDERIZACIÓN DE LA TABLA ############################
