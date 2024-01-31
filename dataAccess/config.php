<?php
session_start(); //comentar esta linea si no se trabaja con sesiones
//require_once 'dataAccess/sessionControl.php';
ini_set('default_charset', 'utf8');
date_default_timezone_set("America/Argentina/Buenos_Aires");

//if (!isset($_SESSION['user_id'])) 
//{
//    $_SESSION['user_id'] = 1;
//}

$localhost = TRUE; //define si se esta trabajando a modo local o no
$proyecto = "Portal del Colegio de Medicos Distrito I";

if ($localhost) 
{
   switch ($_SERVER['HTTP_HOST']) 
   {
       case "www.colmed1.com":
            define("URL_TOTAL", "http://www.colmed1.com/desarrollo/tramites-web/");
            define("DB_USER", "hugo");
            define("DB_PASS", "hugo");
            define("DB_HOST", "192.168.2.50");
            define("DB_SELECTED", "colegio");
           break;
       case "www.colmed1.com.ar":
       case "colmed1.com.ar":
            define("URL_TOTAL", "http://www.colmed1.com.ar/portal/");
            define("DB_USER", "colmed1c_admin");
            define("DB_PASS", "@dmin2017");
            define("DB_HOST", "localhost");
            define("DB_SELECTED", "colmed1c_colegio");           
            break;
       case "www.colmed1.org.ar":
            define("URL_TOTAL", "http://www.colmed1.org.ar/portal/");
            define("DB_USER", "colmed1c_admin");
            define("DB_PASS", "@dmin2017");
            define("DB_HOST", "localhost");
            define("DB_SELECTED", "colmed1c_colegio");           
           break;
       case "localhost":
           define("URL_TOTAL", "localhost/portal/");
            define("DB_USER", "LOCALHOST");
           break;
   }

} 
else 
{
  define("URL_TOTAL", "http://localhost/portal/");
  define("DB_USER", "root");
  define("DB_PASS", "123456");
  define("DB_HOST", "localhost");
  define("DB_SELECTED", "colmed1c_colegio");
  /*
   define("URL_TOTAL", "localhost/portal/");
   define("DB_USER", "hugo");
   define("DB_PASS", "hugo");
   define("DB_HOST", "192.168.2.50");
   define("DB_SELECTED", "colegio");
   */
}
/*
 * paths para utilizar absoluto y permitir
 * url amigable a traves de .htaccess
 */
define("PATH_HOME", URL_TOTAL);
define("PATH_CSS", URL_TOTAL . "css/");
define("PATH_CONTROLS", URL_TOTAL . "controles/");
define("PATH_HTML", URL_TOTAL . "html/");
define("PATH_JS", URL_TOTAL . "js/");
define("PATH_DATAACCESS", URL_TOTAL . "dataAccess/");
define("PATH_IMAGES", URL_TOTAL . "images/");

define("CLAVE_SECRETA", "6LcCvwojAAAAAIYp0JHk3zYLfErECZ_2aEe0ifpd");

//define("ENV", 'desa');
define("ENV", 'prod');

require_once (__DIR__) . '/funcionesSeguridad.php';

