<?php
//ob_start("ob_gzhandler");
ob_start();

header("Content-type: text/css; charset: utf-8");

header("Cache-Control: must-revalidate");

$offset = 60 * 60 ;

$ExpStr = "Expires: " .

gmdate("D, d M Y H:i:s",

time() + $offset) . " GMT";

header($ExpStr);

$includes = "fg.menu.css,";
$includes .= "bo.css,";
$includes .= "ui.jqgrid.css";



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