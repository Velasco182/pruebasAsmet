const fetchData = () =>{
    fetch('http://localhost:8080/back.php')
    .then(response => response.json())
    .then( data => {
        renderTable(data);
        //document.getElementById('dataContainer').innerText = JSON.stringify(data);
    })

    .catch(err => console.error('Error al obtener datos: ', err));
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