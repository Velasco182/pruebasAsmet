//http://localhost/sena/Proyectos/pruebaPhp/index.html
//Ruta para ejecución del Fetch en este caso el back.php
let ruta = "/sena/Proyectos_Prueba/pruebaPhp/assets/php/back.php";
///console.log(ruta+'?id=${id}');
let dataLoaded = false;
// Instancia del formulario y boton de crear y actualizar Cliente
let formularioCrear = document.getElementById("miFormulario");
let btnEnviar = document.getElementById("btn-asmet");
// Formulario actualizar
let formularioActualizar = document.getElementById("actualizarFormulario");
let btnActualizar = document.getElementById("btn-actualizar");
// Botón eliminar
//let btnEliminar = document.getElementById('btn-eliminar');
// Recupera los valores del formulario del modal
let actualizarNombre = document.getElementById("nombreACliente");
let actualizarApellido = document.getElementById("apellidoACliente");
let actualizarTelefono = document.getElementById("telefonoACliente");
//Instancia de modal verificación
let verificacionModal = document.getElementById("verificacionModal");
//Creación de variable para asignar el dataTable
let table;

//Validar formularios
let validarFormularios = (formulario) => {
  formulario.addEventListener("input", () => {
    if (formulario.checkValidity()) {
      btnEnviar.disabled = false;
      btnActualizar.disabled = false;
    } else {
      btnEnviar.disabled = true;
      btnActualizar.disabled = true;
    }
  });
};

validarFormularios(formularioCrear);
validarFormularios(formularioActualizar);

document.addEventListener("DOMContentLoaded", (event) => {
  //#################CREAMOS UN EVENTO DE ESCUCHA EN EL BOTON DE OBTENER#####################
  formularioCrear.reset();
  formularioActualizar.reset();
  //mostrarDatos();
  renderTable();
  //##################CREAMOS UN EVENTO DE ESCUCHA EN EL BOTON DE CREAR######################
  btnEnviar.addEventListener("click", crearCliente);
  //btnActualizar.addEventListener('click', actualizarCliente);
  //btnEliminar.addEventListener('click', eliminarCliente);
  //new DataTable('#myTable');
  //Modal Actualizar
  let actualizarModal = bootstrap.Modal.getInstance(document.getElementById("actualizarModal"));
// Instancia del formulario para poder cerrarlo después de crear un Cliente
  let crearModal = bootstrap.Modal.getInstance(document.getElementById("exampleModal"));
});
///################# FUNCIÓN FLECHA PARA HACER POST A LA DB #########################
let crearCliente = () => {
  // Recupera los valores del formulario del modal
  let nombre = document.getElementById("nombreCliente").value;
  let apellido = document.getElementById("apellidoCliente").value;
  let telefono = document.getElementById("telefonoCliente").value;

  dataLoaded = false;



  // Realiza una solicitud POST para crear un nuevo cliente
  fetch(ruta, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ nombre, apellido, telefono }),
  })
    //Manejo de la respuesta
    .then((response) => {
      if (!response.ok) throw new Error("Error al crear cliente");
      //Devuelve una promesa
      return response.json();
    })
    //Manejo de datos recibidos
    .then((data) => {
      // Muestra un mensaje en la consola y refresca los datos en la tabla
      console.table(data);
      // Recargar la página actual con los datos actualizados
      //location.reload(true)
      validacion();
      //Cierra el modal
      //crearModal.hide();
      $('#exampleModal').modal('hide');
    })
    //Manejo de errores
    .catch((error) => console.error("Error:", error));
};
///################################ CIERRO POST ##################################
///################# FUNCIÓN FLECHA PARA HACER GET A LA DB #########################
let mostrarDatos = () => {
  if (!dataLoaded) {
    fetch(ruta)
      //Manejo de la respuesta
      .then((response) => {
        if (!response.ok) {
          throw new Error("Sin acceso a internet");
        }
        //Devuelve una promesa
        return response.json();
      })
      //Manejo de datos recibidos
      .then((data) => {
        renderTable(data);
        console.table(data);
        dataLoaded = true;
      })
      //Manejo de errores
      .catch((err) => console.error("Error al obtener datos: ", err));
  }
};
///################################ CIERRO GET ##################################
///################# FUNCIÓN FLECHA PARA HACER GET POR ID A LA DB #########################
let mostrarDatosID = (id) => {
  fetch(`${ruta}?id=${id}`)
    //Manejo de la respuesta
    .then((response) => {
      if (!response.ok) {
        throw new Error("Sin acceso a internet");
      }
      //Devuelve una promesa
      return response.json();
    })
    //Manejo de datos recibidos
    .then((data) => {
      //renderTable(data);
      console.table(data);

      let nombreA = data.nombre;
      let apellidoA = data.apellido;
      let telefonoA = data.telefono;

      console.table(data.id, nombreA, apellidoA, telefonoA);

      actualizarNombre.value = nombreA;
      actualizarApellido.value = apellidoA;
      actualizarTelefono.value = telefonoA;

      //validacion();
    })
    //Manejo de errores
    .catch((err) => console.error("Error al obtener datos: ", err));
};
///################################ CIERRO GET POR ID ##################################
///#################### FUNCIÓN FLECHA PARA HACER PUT A LA DB ##########################
let actualizarCliente = (id, nombre, apellido, telefono) => {
  //Mostrar los datos del cliente en el modal
  mostrarDatosID(id);
  console.log(id, nombre, apellido, telefono);

  /*let nombreA = actualizarNombre.value;
  let apellidoA = actualizarApellido.value;
  let telefonoA = actualizarTelefono.value;*/

  // Recupera los valores del formulario del modal
  /*actualizarNombre.value = nombreA;
  actualizarApellido.value = apellidoA;
  actualizarTelefono.value = telefonoA;*/

  //let actualizarModal = document.querySelector("actualizarModal");

  //if(actualizarModal){

  /*let nombre = event.target.getAttribute('nombreACliente');
  let apellido = event.target.getAttribute('apellidoACliente');
  let telefono = event.target.getAttribute('telefonoACliente');*/

  //console.log('Editar cliente con ID:', id);
  //, nombre, apellido, telefono
  // Instancia del formulario para poder cerrarlo después de crear un Cliente

  //actualizarModal.addEventListener("click", function(event){

  //if(event.target.id == 'btn-actualizar'){

  btnActualizar.addEventListener("click", () => {
    let idA = id;
    let nombreA = actualizarNombre.value;
    let apellidoA = actualizarApellido.value;
    let telefonoA = actualizarTelefono.value;

    console.log(idA, nombreA, apellidoA, telefonoA);

    //?id=${id}
    fetch(`${ruta}?id=${idA}`, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ idA, nombreA, apellidoA, telefonoA }),
    })
      //Manejo de la respuesta
      .then(async (response) => {
        if (!response.ok) {
          const text = await response.text();
          throw new Error(text);
        }
        return response.json();
      })
      //Manejo de los datos recibidos
      .then((data) => {
        // Muestra un mensaje en la consola y refresca los datos en la tabla
        console.table(data);
        // Recargar la página actual con los datos actualizados
        if (data != null) {
          //location.reload(true);
          // Cierra el modal
          //modalInstance.hide();
          $('#actualizarModal').modal('hide');
          validacion();
        }
        //validacion();
      })
      //Manejo de errores
      .catch((error) => console.error("Error:", error));
  });
};

///################################ CIERRO PUT ##################################
///#################### FUNCIÓN FLECHA PARA HACER DELETE A LA DB ##########################
let eliminarCliente = (id) => {
  console.log("Editar cliente con ID:", id);

  if (confirm("¿Estás seguro de que deseas eliminar este cliente?")) {
    fetch(`${ruta}?id=${id}`, {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id }),
    })
      //Manejo de la respuesta
      .then((response) => {
        if (!response.ok) throw new Error("Error al eliminar cliente");
        //Devuelve una promesa
        return response.json();
      })
      //Manejo de los datos recibidos
      .then((data) => {
        if (data.success) {
          console.table(data);
          // Recargar la página actual con los datos actualizados
          //location.reload(true);
          // El segundo parámetro evita el reinicio de la paginación
          table.ajax.reload(null, false);
        } else {
          alert("No se puede eliminar el cliente");
          console.error("No se puede eliminar el cliente", data.message);
        }
      })
      //Manejo de errores
      .catch((error) => console.error("Error:", error));
  }
};
///################################ CIERRO DELETE ##################################
//################## Muestra el modal cuando se actualiza el registro ######################
//modalInstance
let validacion = () => {
  // Muestra el modal
  verificacionModal.classList.add("show");
  // Cierra el modal después de 2 segundos
  setTimeout(function () {
    // Oculta el modal
    $('#verificacionModal').modal('hide');
    verificacionModal.classList.remove("show");
  }, 4000);

  setTimeout(function () {
    //Instancia de Titulo del modal
    const modalTitle = document.querySelector(
      "#verificacionModal .modal-title"
    );
    // Asignación del nuevo título
    modalTitle.textContent = "Verificación exitosa!";
    //Instancia del icono del modal
    const verificarUsuarioIcon = document.querySelector(".modal-body #verificarUsuarioIcon");
    // Asignación del nuevo icono
    verificarUsuarioIcon.classList.replace("fa-spinner", "fa-person-circle-check");
    verificarUsuarioIcon.classList.replace("fa-spin-pulse", "fa-bounce");
    //verificarUsuarioIcon.classList.replace("fa-spinner", "");
  }, 2500);

  setTimeout(function () {
    //Recargar página
    //location.reload(true);
    table.ajax.reload(null, false);
    //Ocualtar modal
    //$('#exampleModal').modal('hide');
    //$('#actualizarModal').modal('hide');
    //Clarear Modal
    formularioCrear.reset();
    formularioActualizar.reset();
  }, 4500);

};
//#################### RENDERIZAMOS LOS DATOS OBTENIDOS EN UNA TABLA ######################
function renderTable(data) {

  let d = data;

  $(document).ready(function() {

    table = new DataTable('#myTable', {
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-MX.json'
        },
        order: [[0, 'asc']], // Orden por la columna 'Nombre'
        columnDefs: [
            {
                targets: [0, 1, 2], // Centrar las columnas de datos
                className: "text-center"
            },
            {
                targets: 3, // Columna de acciones
                className: "text-center",
                render: function(data, type, row) {
                  // ID del cliente según la fila seleccionada
                    let id = row.id;
                    let nombre = row.nombre;
                    let apellido = row.apellido;
                    let telefono = row.telefono;
                    console.log(id, nombre, apellido, telefono);
                    console.log(row);
                    // Aquí puedes personalizar los botones de acciones (editar, eliminar, etc.)
                    return `
                        <i class="btn fa-solid fa-pen" style="background-color: #2e9c9d; color: white;" type="button" data-bs-toggle="modal" data-bs-target="#actualizarModal" data-id="${row.id}" onclick="actualizarCliente('${row.id}', '${row.nombre}', '${row.apellido}', '${row.telefono}')"></i>
                        <i class="btn btn-danger fa-solid fa-user-xmark" data-id="${row.id}" onclick="eliminarCliente('${row.id}')"></i>
                    `;
                }
            }
        ],
        lengthMenu: [5, 10, 15, 20],
        ajax: {
            url: `${ruta}`, // URL de tu API o archivo JSON
            type: 'GET',
            //dataSrc: '' // Si tu API devuelve un array de objetos, usa una cadena vacía
        },
        columns: [
            { data: 'nombre' },
            { data: 'apellido' },
            { data: 'telefono' },                 
            { data: null } // Columna para las acciones
        ]
    });
});

  /*$(document).ready(function() {
    $('#myTable').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": `${ruta}`,
        "type": "GET",
        dataSrc: '' // Si tu API devuelve un array de objetos, usa una cadena vacía
      },
      "columns": [
        { "data": "id" },
        { "data": "nombre" },
        { "data": "apellido" },
        { "data": "telefono" }
      ]
    });
  });*/

  //validacion();

  /*let tbody = document.querySelector("#myTable tbody");
  // Limpiar la tabla antes de renderizar
  tbody.innerHTML = "";
  // Renderizar los datos
  data.forEach((item) => {
    let row = document.createElement("tr");

    Object.keys(item).forEach((key) => {
      // Ocultar la columna id
      if (key !== "id") {
        let cell = document.createElement("td");
        cell.textContent = item[key];
        row.appendChild(cell);
      }
    });

    // ID del cliente según la fila seleccionada
    let id = item.id;
    let nombre = item.nombre;
    let apellido = item.apellido;
    let telefono = item.telefono;
    //console.log(id, nombre, apellido, telefono);

    // Crear la celda para los botones de acción
    let actionCell = document.createElement("td");

    // Botón Editar
    let editButton = document.createElement("i");
    //editButton.textContent = '✍️';
    editButton.classList.add("btn", "fa-solid", "fa-pen");
    //editButton.setAttribute('style', 'background-color: #2e9c9d; color: white; hover: { background-color: #cdeeee; color: black; }')
    editButton.style.cssText =
      "background-color: #2e9c9d; color: white; hover: { background-color: #cdeeee; color: black; }";
    editButton.setAttribute("type", "button");
    editButton.setAttribute("data-bs-toggle", "modal");
    editButton.setAttribute("data-bs-target", "#actualizarModal");
    editButton.setAttribute("data-id", id);
    //editButton.setAttribute('id', 'btn-editar');
    //################CREAMOS UN EVENTO DE ESCUCHA EN EL BOTON DE ACTUALIZAR###################
    editButton.addEventListener("click", () =>
      actualizarCliente(id, nombre, apellido, telefono)
    );
    actionCell.appendChild(editButton);

    // Botón Eliminar
    let deleteButton = document.createElement("i");
    //deleteButton.textContent = '✘';
    
    deleteButton.classList.add(
      "btn",
      "btn-danger",
      "fa-solid",
      "fa-user-xmark"
    );
    deleteButton.setAttribute("data-id", id);
    //################CREAMOS UN EVENTO DE ESCUCHA EN EL BOTON DE ELIMINAR###################
    deleteButton.addEventListener("click", () => eliminarCliente(id));
    actionCell.appendChild(deleteButton);

    row.appendChild(actionCell);
    tbody.appendChild(row);
  });*/

  // Inicializar DataTables después de renderizar la tabla
  /*new DataTable('#myTable',{
    //Agregar lenguaje español, en un inicio sólo estaba Ingles
    language:{
      url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-MX.json'
    },
    //Orden alfabético de acuerdo a la primera columna
    order: [[0, 'asc']],
    //Centrar elementos en cada celda definiendo las columnas que se quieren afectar
    columnDefs:[{
      targets: [0, 1, 2, 3],
      className: "text-center"
    }
    /*Se cambian los colores de las columnas de acuerdo al if con ayuda de una función
    ,{
      target: 4,
      render: function(data, type, userRow){
        let classColor = 'text-succes';
        if(data == 3){
          classColor = 'text-warning'
        }
        return `<span class="${classColor}">${data}</span>`
      }
    }
    ],
    lengthMenu:[5,10,15,20],
    
  });*/

  //$('#myTable').DataTable();
}
///########################### CIERRO RENDERIZACIÓN DE LA TABLA ############################
/*$(document).ready(function() {
  $('#myTable').DataTable();
});*/
