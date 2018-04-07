<?php
//header('P3P: CP="CAO PSA OUR"');
@session_start();


require_once 'Config.php';
$Config = Config::getConfig();
//require_once FRM_PATH .'/lib/PDOTester.php';
require_once 'funciones.php';
require_once 'MysqliDb.php';

require_once 'Data.php';
require_once 'WebarException.php';
require_once 'Jota.php';
Jota::requerir('prog/frm/lib/FirePHP/fb.php');
require_once FRM_PATH . '/clases/UsuarioFB.class.php';

/*
Database::$dns = 'mysql:dbname='.$Config->dbName.';host='.$Config->dbHost;
Database::$password = $Config->dbPassword;
Database::$username = $Config->dbUsername;
Database::$host = $Config->dbHost;
Database::$base = $Config->dbName;
*/

require_once FRM_PATH . '/lib/Browser.lib.php';
//Las APP funcionan distinto en cada Browser, es fundamental detectarlo.
$browser = new Browser();

?>