<?php
    //VARIABLES

    //Declaración de una variable
    $inicio = "Hola";
    //Imprimir un texto y concatenación con .
    echo $inicio . "Mundo";
    // No es obligatorio definir el tipo de dato
    //Salto de linea
    echo "<br>";

    //TIPOS DE DATO
    $cadena = "Hola Mundo";
    $numero = 4;
    $flotante = 4.4;
    //Comillas simples
    $caracter = 'C';

    //CONSTANTES
    define("PI", 3.1416);
    echo PI;
    //Salto de linea
    echo "<br>";

    //OPERADORES LÓGICOS Y SENTENCIAS IF, ELSE IF Y ELSE
    // > < >= <= <> != ==
    $a = 1;
    $b = 2;
    $manzana = true;
    $durazno = false;

    if($a<$b){

        echo "Verdadero";
    
    }elseif($manzana != $durazno){
        
         echo "Manzana diferente a Durazno";
    
    }else{

        echo "Falso";  

    }
    //Salto de linea
    echo "<br>";

    //SENTENCIA SWICH CASE
    $i = 3;

    switch($i){
        case 0:
            echo "El valor es 0";
            break;
        case 1:
            echo "El valor es 1";
            break;
        case 2:
            echo "El valor es 2";
            break;
        case 3:
            echo "El valor es 3";
            break;
        default:
            echo "El valor es diferente a 0, 1, 2 o 3";
            break;
    }

    //Ciclos
    //FOR 
    for($i=0; $i<5; $i++){

        echo "Iteración For #".$i. "<br>"; 

    }
    //WHILE
    $i = 0;
    while($i<5){

        echo "Iteración While #".$i. "<br>";
        $i++;

    }
    //DO WHILE
    $j = 0;
    do{

        echo "Iteración Do While #".$i. "<br>";
        $j++;

    }while($i<5);

    //ARREGLOS
    $frutas = array("manzana", "pera", "naranja", "plátano");

    $otraForma = array(
        'Nombre Fruta 1' => $frutas[0],
        'Nombre Fruta 2' => $frutas[1],
        'Nombre Fruta 3' => $frutas[2]
    );

    //  count($x) Permite saber el numero exacto de elementos de un arreglo
    for($i = 0; $i < count($otraForma); $i++){
        echo "(For) La ".$i."° fruta es: ".$otraForma[$i]."<br>";
    }

    foreach($frutas as $value => $fruta){
        echo "(ForEach) La ".$value."° fruta es: ".$fruta."<br>";
    }
?>