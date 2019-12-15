<?php

include 'conexion.php';

$sql = "SELECT * FROM listatareas where id_tarea = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    print_r($result);
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();

?>