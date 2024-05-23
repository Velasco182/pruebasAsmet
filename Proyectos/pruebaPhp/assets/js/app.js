//http://localhost/sena/Proyectos/pruebaPhp/index.html
//Ruta para ejecución del Fetch en este caso el back.php
let ruta = "/sena/Proyectos/pruebaPhp/assets/php/back.php";
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
  mostrarDatos();
  //renderTable();
  //##################CREAMOS UN EVENTO DE ESCUCHA EN EL BOTON DE CREAR######################
  btnEnviar.addEventListener("click", crearCliente);
  //btnActualizar.addEventListener('click', actualizarCliente);
  //btnEliminar.addEventListener('click', eliminarCliente);
});
///################# FUNCIÓN FLECHA PARA HACER POST A LA DB #########################
let crearCliente = () => {
  // Recupera los valores del formulario del modal
  let nombre = document.getElementById("nombreCliente").value;
  let apellido = document.getElementById("apellidoCliente").value;
  let telefono = document.getElementById("telefonoCliente").value;

  dataLoaded = false;

  // Instancia del formulario para poder cerrarlo después de crear un Cliente
  let modalInstance = bootstrap.Modal.getInstance(
    document.getElementById("exampleModal")
  );

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
      validacion(modalInstance);
      // Cierra el modal
      //modalInstance.hide();
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
  let modalInstance = bootstrap.Modal.getInstance(
    document.getElementById("actualizarModal")
  );

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

          validacion(modalInstance);
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
          location.reload(true);
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
let validacion = (modalInstance) => {
  let timer = 5000;
  //let validacionModalInstance = bootstrap.Modal.getInstance(verificacionModal);
  // Muestra el modal
  verificacionModal.classList.add("show");
  // Cierra el modal después de 2 segundos
  setTimeout(function () {
    // Oculta el modal
    //validacionModalInstance.hide();
    verificacionModal.classList.remove("show");

    //timer = 3000;

    if (timer - 3000) {
      //Instancia de Titulo del modal
      const modalTitle = document.querySelector(
        "#verificacionModal .modal-title"
      );
      //console.log(modalTitle.textContent);

      // Asignación del nuevo título
      modalTitle.textContent = "      OK!";

      //timer = 3000;

      if (timer - 100) {
        //Recargar página
        location.reload(true);
        //Ocualtar modal
        modalInstance.hide();

        //timer = 3000;
      }
    }
  }, timer);
};
//#################### RENDERIZAMOS LOS DATOS OBTENIDOS EN UNA TABLA ######################
function renderTable(data) {
  //validacion();

  let tbody = document.querySelector("#myTable tbody");
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
  });
}
///########################### CIERRO RENDERIZACIÓN DE LA TABLA ############################
