<?php
require_once '../dataAccess/config.php';
permisoLogueado();
require_once '../html/head.php';
require_once '../html/header.php';
require_once '../dataAccess/funcionesPhp.php';

$continuar = true;
if (isset($_GET['id']) && $_GET['id'] == $_SESSION['idColegiado']) {
    $idColegiado = $_GET['id'];
    $matricula = $_SESSION['matricula'];

    //obtener los datos del colegiDO
    ini_set('xdebug.var_display_max_depth', -1);
    ini_set('xdebug.var_display_max_children', -1);
    ini_set('xdebug.var_display_max_data', -1);
    set_time_limit(0);

    $ch = curl_init();

    if (DB_USER == "colmed1c_admin") {
        curl_setopt($ch, CURLOPT_URL, 'http://webservices.colmed1.com.ar/colegio/ws-colmed/colegiado/imprimir_nota_deuda.php?idColegiado='.$idColegiado);
    } else {
        curl_setopt($ch, CURLOPT_URL, 'http://www.colmed1.com/desarrollo/colegio/ws-colmed/colegiado/imprimir_nota_deuda.php?idColegiado='.$idColegiado);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    //curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);

    $headers = array();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $respuesta = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
        case 200:  
            $rta=(json_decode($respuesta,true));
            //var_dump($rta);
            if (isset($rta) && isset($rta['respuesta'])) {
                $respuesta = $rta['respuesta'];
                if ($respuesta['codigo'] == 1) {
                    $chequera = $respuesta['chequera'];
                } else {
                    ?>
                    <h4 style="color: red;"<b>Error al buscar las cuotas de colegiación - <?php echo $respuesta['mensaje']; ?></b></h4>
                <?php
                    $continuar = FALSE;
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
} else {
    $continuar = FALSE;
}
if ($continuar) {
?>
    <div class="col-md12"><h6>Tesorería -> Cuotas de colegiación -> Imprimir chequera</h6></div>
    <div class="container-fluid p-3" style="background-color: #8699a4 ; color: white">
        <div class="row">
            <div class="col-md-5">
                <h5><?php echo $_SESSION['apellidoNombre']; ?></h5>
                <h5>M.P. <?php echo $_SESSION['matricula']; ?></h5>
            </div>
            <div class="col-md-5">
            </div>
            <div class="col-md-2">
                <a href="cuotasColegiacion.php?id=<?php echo $idColegiado; ?>" class="btn btn-dark">Volver</a>
            </div>
        </div>
    </div>
    <div class="container-fluid p-3" >
       <embed src='data:application/pdf;base64,<?php echo $chequera; ?>' height="600px" width='100%' type='application/pdf'>   
    </div> 
<?php
} else {
?>
    <div class="col-md-12">
        <h2 class="alert alert-danger">ERROR AL INGRESAR</h2>
    </div>
    <a href="tramites.php" class="btn btn-primary">Volver</a>
<?php
}
include("../html/footer.php");
?>
  </div>

</body>
