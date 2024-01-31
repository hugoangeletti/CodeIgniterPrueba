<?php
function agregarEncuesta($accedioDesde) {
    $conect = conectar();
    mysqli_set_charset( $conect, 'utf8');
    $ip = getRealIP();
    $sql="INSERT INTO en_encuesta_colegiado (FechaInicio, IpCarga, AccesoDesde) 
        VALUES (now(), ?, ?)";
    $stmt = $conect->prepare($sql);
    $stmt->bind_param('ss', $ip, $accedioDesde);
    $stmt->execute();
    $stmt->store_result();
    $resultado = array(); 
    if (mysqli_stmt_errno($stmt)==0) {
        $resultado['idEncuesta'] = mysqli_stmt_insert_id($stmt);
        $resultado['estado'] = TRUE;
        $resultado['mensaje'] = "OK";
        $resultado['clase'] = 'alert alert-success'; 
        $resultado['icono'] = 'glyphicon glyphicon-ok';         
    } else {
        $resultado['estado'] = FALSE;
        $resultado['mensaje'] = "Error al iniciar encuesta";
        $resultado['clase'] = 'alert alert-error'; 
        $resultado['icono'] = 'glyphicon glyphicon-remove';
    }
    return $resultado;
}

function cerrarEncuesta($idEncuesta) {
    $conect = conectar();
    mysqli_set_charset( $conect, 'utf8');
    $sql="UPDATE en_encuesta_colegiado 
        SET FechaFin = now(), Paso = 3, Estado = 'F' 
        WHERE Id = ?";
    $stmt = $conect->prepare($sql);
    $stmt->bind_param('i', $idEncuesta);
    $stmt->execute();
    $stmt->store_result();
    $resultado = array(); 
    if (mysqli_stmt_errno($stmt)==0) {
        $resultado['estado'] = TRUE;
        $resultado['mensaje'] = "OK";
        $resultado['clase'] = 'alert alert-success'; 
        $resultado['icono'] = 'glyphicon glyphicon-ok';         
    } else {
        $resultado['estado'] = FALSE;
        $resultado['mensaje'] = "Error al cerrar encuesta";
        $resultado['clase'] = 'alert alert-error'; 
        $resultado['icono'] = 'glyphicon glyphicon-remove';
    }
    return $resultado;
}

function cantidadAccesosValidos($limite)
{
    $conect = conectar();
    mysqli_set_charset( $conect, 'utf8');
    $ip = getRealIP();
    if ($ip == "::1") {
        $ip = 'localhost';
    }

    $sql = "SELECT COUNT(Id) as Cantidad
        FROM en_encuesta_colegiado 
        WHERE IpCarga = ? 
        AND DATE(FechaInicio) = DATE(NOW())";
    $stmt = $conect->prepare($sql);
    $stmt->bind_param('s', $ip);
    $stmt->execute();
    $stmt->bind_result($cantidad);
    $stmt->store_result();
    $resultado = TRUE; 

    if(mysqli_stmt_errno($stmt)==0)
    {
        if (mysqli_stmt_num_rows($stmt) >= 0) 
        {
            $row = mysqli_stmt_fetch($stmt);
            //echo 'limte: '.$limite.' cantidad: '.$cantidad.' IP: '.$ip; 
            if ($cantidad > $limite) {
                $resultado = FALSE;
            }
        }
    } else {
        $resultado = FALSE;
    }
    return $resultado;    
}

function agregarRespuestas($idEncuesta, $respuestas, $paso)
{
    $conect = conectar();
    mysqli_set_charset( $conect, 'utf8');
    $sql="UPDATE en_encuesta_colegiado 
            SET Paso = ?
            WHERE Id = ?";
    
    $stmt = $conect->prepare($sql);
    $stmt->bind_param('ii', $paso, $idEncuesta);
    $stmt->execute();
    $stmt->store_result();
    $result = array(); 
    if (mysqli_stmt_errno($stmt)==0) {
        $estadoConsulta = TRUE;
        $mensaje = 'SE CARGO EL PASO 1';
        //agregar las respuestas
        foreach ($respuestas as $key => $value) {
            $sql="INSERT en_encuesta_colegiado_respuesta (IdEncuestaColegiado, IdEncuestaPreguntaRespuesta) VALUES(?, ?)";
    
            $stmt = $conect->prepare($sql);
            $stmt->bind_param('ii', $idEncuesta, $value);
            $stmt->execute();
            $stmt->store_result();
            if (mysqli_stmt_errno($stmt) != 0) {
                $estadoConsulta = FALSE;
                $mensaje = 'ERROR AL CARGAR PASO 1';
                exit;
            }
        }
    } else {
        $estadoConsulta = FALSE;
        $mensaje = 'ERROR AL CARGAR PASO 1';
    }
    $result['estado'] = $estadoConsulta;
    $result['mensaje'] = $mensaje;
    return $result; 
}

function guardarEncuestaPaso2($idEncuesta, $radioReciboPublico, $radioObraSocialPublico, 
                $radioDemandaPublico, $radioViolenciaPublico, $radioCondicionLaboralPublico, $radioReciboPrivado, $radioRetencionCajaPrivado, 
                $radioObraSocialPrivado, $radioDemandaPrivado, $radioViolenciaPrivado, $radioCondicionLaboralPrivado, 
                $radioDepositanCuenta, $radioDepositanOtra, $detalleDepositoOtraForma, $radioObraSocial, 
                $radioEmpleos, $observaciones, $radioART){
    $conect = conectar();
    mysqli_set_charset( $conect, 'utf8');
    $sql="UPDATE en_hospital_tmp 
            SET EmiteReciboPublico = ?,
                EmiteReciboPrivado = ?,
                RetencionCajaPrivado = ?,
                ObraSocialPublico = ?,
                ObraSocialPrivado = ?,
                ObraSocialAfiliadoTipo = ?,
                CantidadEmpleos = ?,
                TieneART = ?,
                DemandaPublico = ?,
                DemandaPrivado = ?,
                EpisodioViolenciaPublico = ?,
                EpisodioViolenciaPrivado = ?,
                CondicionLaboralPublico = ?,
                CondicionLaburalPrivado = ?,
                Observaciones = ?,
                Paso = 2,
                Estado = 'F',
                FechaFin = now()
                WHERE Id = ?";
    
    $stmt = $conect->prepare($sql);
    $stmt->bind_param('sssssssssssssssi', $radioReciboPublico, $radioReciboPrivado, $radioRetencionCajaPrivado, 
                $radioObraSocialPublico, $radioObraSocialPrivado, $radioObraSocial,
                $radioEmpleos, $radioART, $radioDemandaPublico, $radioDemandaPrivado, $radioViolenciaPublico, 
                $radioViolenciaPrivado, $radioCondicionLaboralPublico, $radioCondicionLaboralPrivado, $observaciones, 
                $idEncuesta);
    $stmt->execute();
    $stmt->store_result();
    $result = array(); 
    if (mysqli_stmt_errno($stmt)==0) {
        $estadoConsulta = TRUE;
        $mensaje = 'SE CARGO EL PASO 2';
    } else {
        $estadoConsulta = FALSE;
        $mensaje = 'ERROR AL CARGAR PASO 2';
    }
    $result['estado'] = $estadoConsulta;
    $result['mensaje'] = $mensaje;
    return $result; 
}


function borrarBeneficio($id)
{
    $conect = conectar();
    mysqli_set_charset( $conect, 'utf8');
    $sql="UPDATE pa_beneficio SET 
                Estado = 'B'
                WHERE Id = ?";
    
    $stmt = $conect->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->store_result();
    $result = array(); 
    if (mysqli_stmt_num_rows($stmt) >= 0) {
        $estadoConsulta = TRUE;
        $mensaje = 'BENEFICIO HA SIDO BORRADO';
    } else {
        $estadoConsulta = FALSE;
        $mensaje = 'ERROR AL BORRAR BENEFICIO';
    }
    $result['estado'] = $estadoConsulta;
    $result['mensaje'] = $mensaje;
    return $result; 
}

