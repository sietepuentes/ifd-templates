<?php
class Config {

    // Singleton object. Leave $me alone.
    private static $me;
    // Add your server hostnames to the appropriate arrays. ($_SERVER['HTTP_HOST'])
    private $productionServers = array('ifd-templates.org');
    private $stagingServers = array('');
    private $developmentServers = array('', '', '');
    private $localServers = array('localhost','localhost:8080', '127.0.0.1', 'localhost:81');

    public $dbHost; // Database server
    public $dbName; // Database name
    public $dbUsername; // Database username
    public $dbPassword; // Database password
    public $dbDns; // Database dns
    public $navegador;

    const NIVEL_DEBUG = 0;
    
    const PATH_IMAGENES = '../../../../newsletter/';
    const PATH_IMAGENES_DEL = '../../../../newsletter/';
    const PATH_IMAGENES_FRONT = 'newsletter/';
    
    const TITULO_BO = "Administrador - BID";
    //FIN
    
    // <editor-fold defaultstate="collapsed" desc="constructor">
	// Singleton constructor
	private function __construct()
	{
		$i_am_here = $this->whereAmI();

        if ('production' == $i_am_here)
            $this->production();
        elseif ('staging' == $i_am_here)
            $this->staging();
		elseif ('development' == $i_am_here)
			$this->development ();
        elseif ('local' == $i_am_here)
            $this->local();
        elseif ('shell' == $i_am_here)
            $this->shell();
        else
            die('<h1>Where am I?</h1> <p>You need to setup your server names in <code>class.config.php</code></p>
                     <p><code>$_SERVER[\'HTTP_HOST\']</code> reported <code>' . $_SERVER['HTTP_HOST'] . '</code></p>');

        $this->everywhere(); // Add code to be run on all servers
    }

    // </editor-fold>
    // 
    // Add code to be run on all servers
    private function everywhere() {
        define('FRM_PATH', WEB_ROOT . "/prog/frm");
        define('APP_PATH', WEB_ROOT . "/prog/app");
        define('USAR_PDO',0);
        //[dami] seteamos el handler de errores
        set_error_handler("Config::exceptionErrorHandler");
        //[dami] seteamos el timezone
        date_default_timezone_set('America/Argentina/Buenos_Aires');
    }

    // production servers
    private function production() {
        define( 'NIVEL_DEBUG', 2 );
        ini_set('display_errors', '1');

        define('CARPETA', "/dev/");
        define('WEB_ROOT', $_SERVER["DOCUMENT_ROOT"] . CARPETA);
        define('WEB_ROOT_URL', "http://" . $_SERVER["SERVER_NAME"] . ($_SERVER["SERVER_PORT"] != "80" ? ":" . $_SERVER["SERVER_PORT"] : "")  . CARPETA);

        $this->dbHost = '';
        $this->dbName = '';
        $this->dbUsername = '';
        $this->dbPassword = '';
        $this->dbDns = 'mysql:dbname=' . $this->dbName . ';host=' . $this->dbHost;
        $this->dbDieOnError = false;
        
        define('DATABASE_NAME', 'ifdtempl_dev');
        define('DATABASE_USER', 'ifdtempl_bid');
        define('DATABASE_PASS', 'IFD#template');
        define('DATABASE_HOST', 'localhost');
    }

    // staging servers
    private function staging() {
        define( 'NIVEL_DEBUG', 0 );
        ini_set('display_errors', '1');

        define('CARPETA', "/");
        define('WEB_ROOT', $_SERVER["DOCUMENT_ROOT"] . CARPETA);
        define('WEB_ROOT_URL', "http://" . $_SERVER["SERVER_NAME"] . ($_SERVER["SERVER_PORT"] != "80" ? ":" . $_SERVER["SERVER_PORT"] : "")  . CARPETA);


        $this->dbHost = '';
        $this->dbName = '';
        $this->dbUsername = '';
        $this->dbPassword = '';
        $this->dbDns = 'mysql:dbname=' . $this->dbName . ';host=' . $this->dbHost;
        $this->dbDieOnError = false;

    }

    private function development()
    {
	define( 'NIVEL_DEBUG', 0 );
        ini_set('display_errors', '1');
        ini_set('error_reporting', E_ALL);

        define('CARPETA', "/");
        define('WEB_ROOT', $_SERVER["DOCUMENT_ROOT"] . CARPETA);
        define('WEB_ROOT_URL', "http://" . $_SERVER["SERVER_NAME"] . ($_SERVER["SERVER_PORT"] != "80" ? ":" . $_SERVER["SERVER_PORT"] : "")  . CARPETA);

        $this->dbHost = '';
        $this->dbName = '';
        $this->dbUsername = '';
        $this->dbPassword = '';
        $this->dbDns = 'mysql:dbname=' . $this->dbName . ';host=' . $this->dbHost;
        $this->dbDieOnError = true;

    }


    // local servers
    private function local() {
        define( 'NIVEL_DEBUG', 0 );
        ini_set('display_errors', '1');
        ini_set('error_reporting', E_ALL);

        //define('CARPETA', "/Graston/Desarrollo/");
		define('CARPETA', "/ifd-templates/");
        define('WEB_ROOT', $_SERVER["DOCUMENT_ROOT"] . CARPETA);
        define('WEB_ROOT_URL', "http://" . $_SERVER["SERVER_NAME"] . ($_SERVER["SERVER_PORT"] != "80" ? ":" . $_SERVER["SERVER_PORT"] : "")  . CARPETA);

       /*  define('DATABASE_NAME', 'bid');
        define('DATABASE_USER', 'root');
        define('DATABASE_PASS', '');
        define('DATABASE_HOST', 'localhost'); */
		define('DATABASE_NAME', 'ifdtempl_bid');
        define('DATABASE_USER', 'root');
        define('DATABASE_PASS', '');
        define('DATABASE_HOST', 'localhost');
    }

    // <editor-fold defaultstate="collapsed" desc="funciones del config">
    /**
     * Standard singleton
     * @return Config
     */
    public static function getConfig() {
        if (is_null(self::$me))
            self::$me = new Config();

        return self::$me;
    }

    // Allow access to config settings statically.
    // Ex: Config::get('some_value')
    public static function get($key) {
        return self::$me->$key;
    }

    public function whereAmI() {
        if (in_array($_SERVER['HTTP_HOST'], $this->productionServers))
            return 'production';
        elseif (in_array($_SERVER['HTTP_HOST'], $this->stagingServers))
            return 'staging';
		elseif (in_array( $_SERVER['HTTP_HOST'], $this->developmentServers ))
			return 'development';
        elseif (in_array($_SERVER['HTTP_HOST'], $this->localServers))
            return 'local';
        else
            return false;
    }

    public static function exceptionErrorHandler($errno, $errstr, $errfile, $errline) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
   

// </editor-fold>
}