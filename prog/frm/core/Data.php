<?php

/**
 * El helper de datos posee funciones para trabajar con datos y cositas.
 *
 * @author dami
 */
class Data
{

    public static function revertirSafe($dato)
    {
	$retorno= self::stripSlashes($dato);
	$retorno= self::fromHtmlEnt($retorno);
	return $retorno;
    }
    // <editor-fold defaultstate="collapsed" desc="datos en general">

    public static function vacio($dato, $bCeroNoEsVacio= false)
    {
        if (    !isset($dato)
                || (    $bCeroNoEsVacio
                        && (    empty($dato)
                                && $dato != "0"
                                && $dato != 0
                            )
                    )
                || (    !$bCeroNoEsVacio
                        && (empty($dato))
                    )
                || $dato== '0000-00-00 00:00:00'
                || $dato== '0000-00-00'
        )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Aplica un valor de reemplazo a una variable en el caso de que esta sea vacia.
     * Similar al isNull de SqlServer o al ifNull de MySql.
     * @param multiple $dato
     * @param multiple $valorDeReemplazo
     * @return multiple
     * @author dami
     */
    public static function ifNull($dato, $valorDeReemplazo)
    {
        if (Data::vacio($dato))
            $dato = $valorDeReemplazo;

        return $dato;
    }

    /**
     *  Resuleve el problema de comillas que puede surgir al intentar escapear un texto que ya esta escapeado
     *  por el escapeo automatico de apache (magic quites).
     * @param string|array $dato
     * @return string|array
     * @author dami
     */
    public static function fixSlashes($dato)
    {
        //[dami]la funcion get_magic_quotes_gpc nos dice si apache tiene habilitado
        //el escapeo automatico de comillas, de modo que si no esta habilitado no hace falta resolver nada
        if(!Data::vacio( $dato )|| !get_magic_quotes_gpc())
            return $dato;
        else
            return Data::stripSlashes($dato);
    }
    
    /**
     *  Remueve las barras de escapeo de un texto o un array de textos.
     * @param string|array $dato
     * @return string|array
     * @author dami
     */
    public static function stripSlashes($dato)
    {
       return  self::deep($dato, 'stripslashes');
    }

    /**
     *  Aplica un callback al dato y si ese dato en un array lo aplica a todos sus elementos
     * @param string|array $dato
     * @param string $sCallBack
     * @author dami
     */
    public static function deep($dato, $sCallBack)
    {
	$retorno= null;
        if(!Data::isArray( $dato))
            $retorno= $sCallBack($dato);
        else
            {
//                return array_map('Data::deep', $dato, $sCallBack);
                //[dami]antes usaba array_map pero tira errores y warnings y me da por las pelotas
                //asi que ahora hacemos una recursividad padraza para aplciar el callback a los
                //arrays dentro de otros arrays
                $arrRetorno = array();
                foreach ($dato as $clave => $valor)
                {
                    $arrRetorno["$clave"] = self::deep($dato["$clave"], $sCallBack);
                }
                $retorno= $arrRetorno;
            }

	return $retorno;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="fechas">
    public static function formatoFecha($fecha, $sFormato='Y-m-d h:i:s')
    {
        return date($sFormato, strtotime($fecha));
    }

    public static function parseDateTime($sFecha, $sFormato= null)
    {
        $retorno= date('Y-m-d h:i:s', strtotime(self::replace($sFecha, "/", "-")));
        return $retorno;
    }

    /**
     * Devuelve un numero indicando la distancia entre la fecha uno y la fecha dos.
     * @param string $fechaUno
     * @param string $fechaDos
     * @return int
     * @author dami
     */
    public static function compareDates($fechaUno, $fechaDos)
    {
        $arrFechaUno= explode('-', Data::replace($fechaUno, '/', '-'));
        $arrFechaDos= explode('-', Data::replace($fechaDos, '/', '-'));

        if(count( $arrFechaUno)< 3|| count( $arrFechaDos)< 3)
            throw new Exception ("Este metodo solo soporta el formato dd-MM-yyyy o dd/MM/yyyy");

        $enteroUno= mktime (0, 0, 0, $arrFechaUno[1], $arrFechaUno[0], $arrFechaUno[2]);
        $enteroDos= mktime (0, 0, 0, $arrFechaDos[1], $arrFechaDos[0], $arrFechaDos[2]);

        return ($enteroUno- $enteroDos);
    }
    
	public static function getNombreMes($fecha)
    {
        $ing = array("April", "May", "June", "July", "August", "September", "October", "November", "December", "January", "February", "March");
        $espa = array('Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Enero', 'Febrero', 'Marzo');

        return str_replace($ing, $espa  , self::formatoFecha($fecha, 'F'));
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="booleanos">

    public static function parseBool($sValor)
    {
        $bRetorno= !Data::vacio( $sValor );

        if(strtolower($sValor)== 'false')
            $bRetorno= false;

        return $bRetorno;
    }

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="estringuis">
    
    public static function recortarTexto($sTexto, $iLargo)
    {
        $sRetorno = $sTexto;
        if (strlen($sTexto) > $iLargo)
        {
            $sRetorno = substr($sTexto, 0, $iLargo - 3) . "...";
        }

        return $sRetorno;
    }

    public static function replace($sTexto, $sValor, $sReemplazo)
    {
        return str_replace($sValor, $sReemplazo, $sTexto);
    }

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="arrays">

    public static function hasKey($sKey, $arrArray)
    {
        return array_key_exists($sKey, $arrArray);
    }

    public static function isArray($variable)
    {
        return (bool) is_array($variable);
    }

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="html">

    /**
     *  Convierte todos caracteres ratos de un string o un array de stringuis
     *  en sus respectivas entidades de html
     * @param string|array $dato
     * @return string|array
     * @author dami
     */
    public static function toHtmlEnt($dato)
    {
	$retorno= self::deep($dato, 'htmlentities');
	return $retorno;
    }

    public static function fromHtmlEnt($dato)
    {
	$retorno= Data::deep($dato, 'html_entity_decode');
	
	return $retorno;
    }

    /**
     *  Convierte algunos caracteres raros de un string o un array de stringuis
     *  en sus respectivas entidades de html
     * @param string|array $dato
     * @return string|array
     * @author dami
     */
    public static function toHtmlSpecialChars($dato)
    {
        return self::deep($dato, 'htmlspecialchars');
    }

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="encriptacion">
    /**
     *  Devuelve un hash MD5 de un string o de un array de strings.
     * @param <type> $dato
     * @return <type>
     */
    public static function toMD5($dato)
    {
       return  self::deep($dato, 'md5');
    }
    
// </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="mysql">
    public static function safe($data)
    {
	$retorno= $data;
        if(Data::vacio( $retorno ))
            return $retorno;

        //[dami]primero fixeamos las eslayes
	$retorno= self::fixSlashes($retorno);
        //segundo convertimos los caracteres especiales a sus restepectivas entidades html
//	$retorno= self::toHtmlEnt($retorno);
        //y por ultimo escapeamos cositas que se usan en SQL injection
        $retorno= self::escapearSqlInjection($retorno);

        return $retorno;
    }

    /**
     *
     * @param string $query
     * @return string
     * @author leao (se la afano de algun lado)
     */
    protected static function escapearSqlInjection($query)
    {
//	Jota::log('entra', $query);

	//[dami] esta regex esta en php.net
	$search= array("\\","\0","\x1a","'",'"', ';',);
        $replace= array("\\\\","\\0","\\n","\\r","\Z","\'",'\"', '\;');
        $retorno= str_ireplace($search, $replace, $query);

//	Jota::log('sale', $retorno);
	return $retorno;
    }

    public static function sanitize($data)
    {
        //self::deep($data, 'Data::safe');;

        //[dami]antes usaba deep pero sucede un error muy raro, el que tenga interes
        //en saberlo me pregunta

        if(!Data::isArray( $data))
            return self::safe($data);
        else
            {
                $arrRetorno = array();
                foreach ($data as $clave => $valor)
                {
                    $arrRetorno["$clave"] = self::safe($data["$clave"]);
                }
                return $arrRetorno;
            }
    }
// </editor-fold>
}
?>