const fetchData = () =>{
    //Ruta para ejecución del Fetch en este caso el back.php
    fetch('/sena/Proyectos/pruebaPhp/back.php')
    .then(response => response.json())
    .catch(err => console.error('Error al obtener datos: ', err))
    .then( data => {
        renderTable(data);
        //document.getElementById('dataContainer').innerText = JSON.stringify(data);
    });
}

document.getElementById('fetchData').addEventListener('click', fetchData)

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