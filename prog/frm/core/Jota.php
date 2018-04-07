<?php

/**
 * Esta clase estatica, posee funciones basicas para el funcionamiento del
 * "Framework Jota"
 *
 * @author dami
 */

class Jota
{
    const PATH_CLASES= "prog/app/mcc/clases";
    const PATH_HELPERS= "prog/frm/helpers";
    const PATH_LIBS= "prog/frm/lib";
    
    public static function getUrl($https= false)
    {
        /*if (is_null($https))
	    return $_SERVER['HTTPS'] . $_SERVER["HTTP_HOST"] . FOLDER;
	else*/ if ($https)
	    return "https://" . $_SERVER["HTTP_HOST"] . CARPETA;
	else
	    return "http://" . $_SERVER["HTTP_HOST"] . CARPETA;
    }

    public static function eswas($dato)
    {
        echo var_dump($dato);
        exit();
    }

    public static function requerir($sRutaArchivo)
    {
        require_once WEB_ROOT."/$sRutaArchivo";
    }

    public static function incluir($arrClases)
    {
        if(Data::hasKey('helpers', $arrClases))
        {
            if(!Data::isArray($arrClases['helpers']))
            {
                self::requerir (self::PATH_HELPERS."/".$arrClases['helpers'].".hlp.php");
            }
            else
            {
                foreach($arrClases['helpers'] as $nombreClase)
                    self::requerir (self::PATH_HELPERS."/$nombreClase.hlp.php");
            }
        }

        if(Data::hasKey('clases', $arrClases))
        {
            if(!Data::isArray($arrClases['clases']))
            {
                self::requerir (self::PATH_CLASES."/".$arrClases['clases'].".class.php");
            }
            else
            {
                foreach($arrClases['clases'] as $nombreClase)
                    self::requerir (self::PATH_CLASES."/$nombreClase.class.php");
            }
        }

		if(Data::hasKey('libs', $arrClases))
        {
            if(!Data::isArray($arrClases['libs']))
            {
                self::requerir (self::PATH_LIBS."/".$arrClases['libs'].".lib.php");
            }
            else
            {
                foreach($arrClases['libs'] as $nombreClase)
                    self::requerir (self::PATH_LIBS."/$nombreClase.lib.php");
            }
        }
    }

    public static function cue($dato)
    {
        echo Data::toHtmlEnt($dato);
    }

    /**
     * Loguea un dato en la consola del firebug
     * @param <variable> $dato
     * @author leao y dami <3
     */
    public static function log($sTitulo, $dato)
    {
        if (NIVEL_DEBUG < 1)
        {
            fb::log( $dato, $sTitulo );
        }
    }
}

?>
