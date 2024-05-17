///FUNCIÓN PARA HACER GET A LOS DATOS DE LA DB///
let ruta = '/sena/Proyectos/pruebaPhp/back.php';
const fetchData = () =>{
    //Ruta para ejecución del Fetch en este caso el back.php
    fetch(ruta)
    .then(response => response.json())
    .catch(err => console.error('Error al obtener datos: ', err))
    .then( data => {
        renderTable(data);
        //document.getElementById('dataContainer').innerText = JSON.stringify(data);
    });
}
////HACEMOS UN EVENTO DE ESCUCHA EN EL BOTON DE OBTENER
document.getElementById('fetchData').addEventListener('click', fetchData)
///FUNCIÓN PARA CREAR DATOS HACIENDO UN POST
function fetchCrear(){

    $('#miFormulario').submit(function(event) {
        event.preventDefault();

        console.log("en formulario");
      
        // Recopilar los datos del formulario
       /*var nombre = document.getElementById('nombreCliente').value;
        var apellido = document.getElementById('apellidoCliente').value;
        var telefono = document.getElementById('telefonoCliente').value;
        */
        let formulario = new FormData(document.getElementById("miFormulario"))

        // Enviar los datos al servidor utilizando fetch
        fetch(ruta, {
          method: 'POST',
          body: formulario
          /*headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            nombre: nombre,
            apellido: apellido,
            telefono: telefono
          })*/
        })
       .then(response => response.json())
       .catch(error => console.error(error))
       .then(data => {
          console.log(data);
      
          // Cerrar el modal
          $('#exampleModal').modal('hide');
        });
       
      });
}
////HACEMOS UN EVENTO DE ESCUCHA EN EL BOTON DE CREAR*/
document.getElementById('btn-asmet').addEventListener('click', fetchCrear)
////RENDERIZAMOS LOS DATOS OBTENIDOS EN UNA TABLA
function renderTable(data) {
    const tbody = document.querySelector('#myTable tbody');
    
    data.forEach(item => {

        const row = document.createElement('tr');

        Object.values(item).forEach(value => {

            const cell = document.createElement('td');
            cell.textContent = value;
            row.appendChild(cell);

        });

        tbody.appendChild(row);
        
    });
}