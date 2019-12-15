<?php

$tarea = $_POST['tarea'];
$descripcion = $_POST['descripcion'];
$urgencia = $_POST['urgencia'];
$estado = 0;

//Importar la conexión
include 'conexion.php';

try {
    $stmt = $conn->prepare(" INSERT INTO listatareas (nombre_tarea, descripcion, estado, urgencia) VALUES (?, ?, ?, ?) ");
    $stmt->bind_param('ssss', $tarea, $descripcion, $estado, $urgencia);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $respuesta = array(
            'respuesta'=>'correcto',
            'id_insertado'=>$stmt->insert_id,
            'tarea'=>$tarea
        );
    } else {
        $respuesta = array(
            'respuesta'=>'error'
        );
    }
    $stmt->close();
    $conn->close();
} catch (\Throwable $th) {
    //throw $th;
    $respuesta = array(
        'respuesta'=>$th->getMessage()
    );
}

echo json_encode($respuesta);

?>