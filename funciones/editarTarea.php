<?php

$id = (int)$_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$urgencia = (int)$_POST['urgencia'];

include 'conexion.php';
    try {
        $stmt = $conn->prepare(" UPDATE listatareas SET nombre_tarea = ?, descripcion = ?, urgencia = ? WHERE id_tarea = ? ");
        $stmt->bind_param('ssii', $nombre, $descripcion, $urgencia, $id);
        $stmt->execute();
        if($stmt->affected_rows > 0){
            $respuesta = array(
                'respuesta'=>'correcto'
            );
        } else {
            $respuesta = array(
                'respuesta'=>'error'
            );
        }
        $stmt->close();
        $conn->close();
    } catch (\Throwable $th) {
        $respuesta = array(
            'error'=>$th->getMessage()
        );
    }

    echo json_encode($respuesta);

?>