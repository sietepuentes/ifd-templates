<?php
//ob_start("ob_gzhandler");
ob_start();

header("Content-type: text/javascript; charset: iso-8859-1");

header("Cache-Control: must-revalidate");

$offset = 60 * 60 ;

$ExpStr = "Expires: " .

gmdate("D, d M Y H:i:s",

time() + $offset) . " GMT";

header($ExpStr);

$includes = "jquery-ui.min.js,";
//$includes .= "i18n/grid.locale-es.js,";
//$includes .= "jquery.jqGrid.min.js,";
//$includes .= "jquery.fgmenu.js,";
$includes .= "jquery.form.js,";
$includes .= "jquery.cookie.js,";
$includes .= "jquery.validar.js,";
//$includes .= "jqgrid.defaults.js,";
$includes .= "funciones.js,";
$includes .= "jota.js";



$includes = explode(",", $includes);

foreach($includes as $include){
    $file = dirname(__FILE__) ."/$include";

    if(file_exists($file)){
        $contents = file_get_contents($file);
    }else{
        echo "--------- " . $file . " ----------------";
        exit;
    }

    echo $contents;

}

ob_end_flush();
?>