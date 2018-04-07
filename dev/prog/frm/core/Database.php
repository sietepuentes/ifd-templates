<?php

final class Database
{

	public static $dns;
	public static $username;
	public static $password;
	public static $host;
	public static $base;
	private static $instance;

	private function __construct()
	{

	}

	/**
	 * Crea una instancia de la clase PDO
	 *
	 * @access public static
	 * @return object de la clase PDO
	 */
	public static function getInstance($pdo=true)
	{

		if (!isset( self::$instance ))
		{
			/*if ($pdo)
			{*/

//				self::$instance = new PDOTester( self::$dns, self::$username, self::$password, array(
//							PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
//						) );
//				self::$instance->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

				//TODO: [dami]el singleton no va mas, hay que wrappear PDO y usar un aproach estilo el guille
				//como hacemos en .net

				return new PDOTester( self::$dns, self::$username, self::$password, array(
							PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
						) );
			/*}
			else
			{
				require_once FRM_PATH . "/clases/Db.class.php";
				self::$instance = new DB( self::$base, self::$host, self::$username, self::$password );
			}*/
		}
		return self::$instance;
	}

	/**
	 * Impide que la clase sea clonada
	 *
	 * @access public
	 * @return string trigger_error
	 */
	public function __clone()
	{
		trigger_error( 'Clone is not allowed.', E_USER_ERROR );
	}

}

?>