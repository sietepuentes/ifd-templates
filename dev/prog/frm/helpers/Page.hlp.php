<?php

class Page
{

	public static function redirect($destino)
	{
		header( "Location: $destino" );
	}

	public static function get($sNombre)
	{
		return $_REQUEST [$sNombre];
	}

	public static function getOpcional($sNombre, $valorDeReemplazo= null)
	{
		$retorno = null;

		if (isset( $_REQUEST [$sNombre] ))
		{
			$retorno = Page::get( $sNombre );
		}
		else
		{
			if ($valorDeReemplazo != null)
			{
				$retorno = $valorDeReemplazo;
			}
		}

		return $retorno;
	}

	public static function registrarScript($sScript)
	{
		echo "<script type=\"text/javascript\">$sScript</script>";
	}

	public static function alertar($sMensaje, $sScript= null)
	{
		if (Data::vacio( $sScript ))
			$sScript = "";

		Page::registrarScript( "alert('" . Data::escapearParaJs( $sMensaje ) . "'); $sScript" );
	}

	public static function getRealIpAddr()
	{
		if (!empty( $_SERVER['HTTP_CLIENT_IP'] ))   //check ip from share internet
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ))   //to check ip is pass from proxy
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	public static function terminar($response)
	{
		if (!Data::vacio( $response ))
		{
			echo $response;
		}
		flush();
		exit();
	}

    public static function getFile($sNombre)
    {
       return $_FILES[$sNombre];
    }

    public static function charsetAjaxUtf8()
    {
        header("Content-type: text/javascript; charset=UTF-8");
    }

    public static function charsetUtf8()
    {
        header("Content-type: text/html; charset=UTF-8");
    }

    public static function getNombrePagina()
    {
        $arr= str_split($_SERVER['REQUEST_URI'], '/');

        return $arr[count($arr)- 1];
    }
}

?>