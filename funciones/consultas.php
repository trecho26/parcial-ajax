<?php

//Obtener las tareas
function obtenerTareas(){
    include 'conexion.php';
    try {
        return $conn->query(" SELECT * FROM listatareas ");
       
    } catch (\Throwable $th) {
        echo "Error: ". $th->getMessage();
        return false;
    }
}

?>