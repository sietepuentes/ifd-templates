<?php
    require_once '../../../frm/core/ini.php';
    Jota::incluir(   array  ('helpers' => array('DBHelper','PageBo','Page','Html','Json')
                            )
    );

header('Content-Type: text/html; charset=iso-8859-1');

$campoID = Page::get("cid");
$ID = Page::get("id");
$tabla = Page::get("t");
$campo = Page::get("c");
$foto = Page::get("f");
$path = Page::get("p");

    //@unlink("../../../../../../../newsletter/".$foto);
    $query="update bid_".$tabla." set ".$campo." = NULL where ".$campoID." = ".$ID;
   
$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$db = new Mysqlidb($mysqli);

$result = $db->query($query);

    
    
    echo "1";
?>