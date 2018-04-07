<?php

$enlace =  mysql_connect('localhost', 'ifdtempl_bid', 'IFD#template');
if (!$enlace) {
    die('No pudo conectarse: ' . mysql_error());
}
echo 'Conectado satisfactoriamente';

$mysqli = new mysqli("localhost", "ifdtempl_bid", "IFD#template", "ifdtempl_bid");
if ($mysqli->connect_errno) {
    echo "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
?>