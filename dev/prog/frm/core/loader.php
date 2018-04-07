<?php

ob_start("ob_gzhandler");

header("Content-type: text/javascript; charset: iso-8859-1");

header("Cache-Control: must-revalidate");

$offset = 60 * 60 ;

$ExpStr = "Expires: " .

gmdate("D, d M Y H:i:s",

time() + $offset) . " GMT";

header($ExpStr);

if (isset($_GET["include"])){

  $includes = explode(",", $_GET["include"]);

  foreach($includes as $include){

      if(strstr($include,'cont')){
              $contents = file_get_contents(dirname(__FILE__) ."/controladores/$include.js");
      }else{
              $contents = file_get_contents(dirname(__FILE__) ."/core/js/$include.js");
      }
              
    echo $contents;
  }

}

ob_end_flush();
?>