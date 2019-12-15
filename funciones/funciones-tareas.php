<?php

$id = (int) $_POST['id'];
$accion = $_POST['accion'];
$estado = (int) $_POST['estado'];

if ($accion === 'cambiarEstado') {
    include 'conexion.php';
    try {
        $stmt = $conn->prepare(" UPDATE listatareas SET estado = ? WHERE id_tarea = ? ");
        $stmt->bind_param('ii', $estado, $id);
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
} else if ($accion === 'eliminarTarea') {
    include 'conexion.php';
    try {
        $stmt = $conn->prepare(" DELETE FROM listatareas WHERE id_tarea = ? ");
        $stmt->bind_param('i', $id);
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

}

echo json_encode($respuesta);

?>