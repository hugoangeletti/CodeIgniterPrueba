<?php
function conectar() {
    
    $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_SELECTED);
    if ($link->connect_error) {
        echo $link->connect_error;
        return $link->connect_error;
    } else {
        return ($link);
    }
}

function desconectar($conexion) {
    $conexion->close();
}
