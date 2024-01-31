<?php
require_once '../dataAccess/config.php';
permisoLogueado();
require_once '../html/head.php';
require_once '../html/header.php';

$matricula = $_SESSION['matricula'];
$continua = TRUE;
if ($continua) {
    //obtener los datos del colegiDO
    ini_set('xdebug.var_display_max_depth', -1);
    ini_set('xdebug.var_display_max_children', -1);
    ini_set('xdebug.var_display_max_data', -1);
    set_time_limit(0);

    $ch = curl_init();
    if (DB_USER == "colmed1c_admin") {
        curl_setopt($ch, CURLOPT_URL, 'http://webservices.colmed1.com.ar/colegio/ws-colmed/colegiado/buscar_colegiado.php?matricula='.$matricula);
    } else {
        curl_setopt($ch, CURLOPT_URL, 'http://www.colmed1.com/desarrollo/colegio/ws-colmed/colegiado/buscar_colegiado.php?matricula='.$matricula);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    //curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);

    $headers = array();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $respuesta = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);

    $continuar = true;
    if ($err) {
        $continuar = FALSE;
    ?>
        <div class="row alert alert-danger">
            <div class="col-md-12 text-left">Disculpe las molestias. Momentaneamente fuera de servicio, intente m&aacute;s tarde</div>
        </div>
    <?php
    //echo "cURL Error #:" . $err;
    } else {
      switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
        case 200:  
            $rta=(json_decode($respuesta,true));
            //var_dump($rta);
            if (isset($rta) && isset($rta['respuesta'])) {
                $respuesta = $rta['respuesta'];
                if ($respuesta['codigo'] == 1) {
                    $colegiado = $respuesta['datos'];
                    $_SESSION['apellidoNombre'] = $colegiado['apellido'].' '.$colegiado['nombre'];
                    $_SESSION['idColegiado'] = $colegiado['idColegiado'];
                    $idColegiado = $_SESSION['idColegiado'];
                } else {
                ?>
                    <h4 style="color: red;"<b>Usuario NO V&Aacute;LIDO - <?php echo $respuesta['mensaje']; ?></b></h4>
                    <?php
                    $continuar = FALSE;
                    if (isset($respuesta['datos'])) {
                        $mailRegistrado = $respuesta['datos']['mailRegistrado'];
                        $mailRegistradoSeparado = explode("@", $mailRegistrado);
                        $longMail = strlen($mailRegistradoSeparado[0]);

                        if ($longMail > 5) {
                            $letras = 2;
                        } else {
                            $letras = 1;
                        }

                        $mailMostrar = substr($mailRegistradoSeparado[0], 0, $letras);
                        $i = $letras;
                        while ($i < $longMail-$letras) {
                            $mailMostrar .= "*";
                            $i++;
                        }
                        $mailMostrar .= substr($mailRegistradoSeparado[0], $longMail-$letras, $letras);
                        $mailMostrar .= "@".$mailRegistradoSeparado[1];
                        ?>
                        <h3>Mail registrado: <?php echo $mailMostrar; ?></h3>
                        <div class="col-md-4">
                            <a href="cambiarMail.php" class="btn btn-info" role="button">Cambiar Mail</a>
                            <a href="login.php" class="btn btn-info" role="button">Volver</a>
                        </div>


                    <?php
                    } else {
                    ?>
                        <div class="col-md-4">
                            <a href="login.php" class="btn btn-info" role="button">Volver</a>
                        </div>
                    <?php
                    }

                }
            } else {
                $continuar = FALSE;
                ?>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <h5>Momentaneamente fuera de servicio, vuelva a intentar más tarde</a>
                    </div>
                    <div class="col-md-4">
                        <a href="login.php" class="btn btn-info" role="button">Volver</a>
                    </div>
                </div>
                <?php            
            }
            break;

        case 400:  
            $rta=(json_decode($respuesta,true));
            var_dump($rta);
            $continuar = FALSE;
            break;

        default:
            echo 'Codigo HTTP inesperado: '.$http_code."<br>";
            $continuar = FALSE;
            break;
        }

    }

    if ($continuar) {
        switch ($colegiado['tipoEstado']) {
            case 'A':
                $estadoActual = "ACTIVO";
                $colorEstadoActual = "green";
                break;

            case 'I':
                $estadoActual = "INSCRIPTO";
                $colorEstadoActual = "green";
                break;

            case 'J':
                $estadoActual = "JUBILACION";
                $colorEstadoActual = "red";
                break;

            case 'F':
                $estadoActual = "FALLECIDO";
                $colorEstadoActual = "red";
                break;

            case 'C':
                $estadoActual = "BAJA - ".$colegiado['detalleMovimiento'];
                $colorEstadoActual = "red";
                break;

            default:
                $estadoActual = "SIN DATO";
                $colorEstadoActual = "blue";
                break;
        }
        
        if ($colegiado['estado_tesoreria']['codigoDeudor'] == 0) {
            $colorEstadoTesoreria = "green";
        } else {
            $colorEstadoTesoreria = "#EE5757";
        }
        ?>
        <div class="col-md-12"><hr></div>
        <div class="col-md-12">
            <div class="row">
                <div class="card" style="width: 30rem;">
                    <!--<img class="card-img-top" src="..." alt="Card image cap">-->
                    <div class="card-body">
                      <h5 class="card-title">Bienvenido!</h5>
                      <p class="card-text">
                        <h4><?php if ($colegiado['sexo'] == "M") { echo "Dr. "; } else { echo "Dra. ";} echo $_SESSION['apellidoNombre']; ?></h4>
                        <h4>M.P. <?php echo $colegiado['matricula']; ?></h4>
                        <h5>Estado actual: <b style="color: <?php echo $colorEstadoActual;  ?>;">
                            <?php echo $estadoActual; ?></b>
                        </h5>
                        <h5>Situación con Tesorería: <b style="color: <?php echo $colorEstadoTesoreria;  ?>;">
                            <?php echo $colegiado['estado_tesoreria']['leyenda']; ?></b></h5>
                      </p>
                      <a href="logout.php" class="btn btn-dark">Salir de trámites</a>
                    </div>
                </div>
                <?php 
                /*
                <div class="card" style="width: 18rem;">
                    <!--<img class="card-img-top" src="..." alt="Card image cap">-->
                    <div class="card-body">
                      <h5 class="card-title">Trámites</h5>
                      <p class="card-text">
                        <a href="certificados.php?id=<?php echo $idColegiado; ?>" class="btn btn-info btn-block" role="button">Solicitud de Certificados</a>
                        <br>
                        <a href="bajas.php?id=<?php echo $idColegiado; ?>" class="btn btn-info btn-block" role="button">Baja de matrícula</a>
                        <br>
                        <a href="rehabilitacion.php?id=<?php echo $idColegiado; ?>" class="btn btn-info btn-block" role="button">Rehabilitación de matrícula</a>
                      </p>
                    </div> 
                </div>
                */
                ?>
                <div class="card" style="width: 18rem;">
                    <!--<img class="card-img-top" src="..." alt="Card image cap">-->
                    <div class="card-body">
                      <h5 class="card-title">Tesorería</h5>
                      <p class="card-text">
                        <a href="cuotasColegiacion.php?id=<?php echo $idColegiado; ?>" class="btn btn-info btn-block" role="button">Cuotas de colegiación</a>
                        <br>
                        <?php 
                        /*
                        <a href="pagosRegistrados.php?id=<?php echo $idColegiado; ?>" class="btn btn-info btn-block" role="button">Pagos registrados</a>
                        <br>
                        */
                        if ($colegiado['estado_tesoreria']['codigoDeudor'] == 4) {
                        ?>
                            <a href="planDePagos.php?id=<?php echo $idColegiado; ?>" class="btn btn-info btn-block" role="button">Plan de Pagos</a>
                            <br>
                        <?php 
                        }
                        /*
                        <a href="cursos.php?id=<?php echo $idColegiado; ?>" class="btn btn-info btn-block" role="button">Cursos</a>
                        <br>
                        <a href="debitoAutomatico.php?id=<?php echo $idColegiado; ?>" class="btn btn-info btn-block" role="button">Débito automático</a>
                        */
                        ?>
                      </p>
                    </div> 
                </div>
            </div>
        </div>
    <?php
    }
} else {
?>
    <div class="row">&nbsp;</div>
    <div class="row">
        <a href="logout.php" class="btn btn-dark">Volver</a>
    </div>
<?php
}
include("../html/footer.php");
?>
  </div>

</body>
</html>