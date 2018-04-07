<?php
/**
 * Description of GeneradorAbm
 *
 * @author leo
 */
require_once '../core/ini.php';
require_once '../core/GeneradorAbm.php';

$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : '';

if($tabla != ""){
    echo GeneradorAbm::armar($tabla);
}else{
    echo GeneradorAbm::generarArchivo($_POST);
}

?>