<?php

class File
{

    public static function leer($sNombreArchivo)
    {
        $sRetorno = "";

        if (file_exists( $sNombreArchivo ))
        {
            $fp = fopen( $sNombreArchivo, "r" );
            while ($linea = fgets( $fp, 1024 ))
                $sRetorno .= $linea;
        }
        return $sRetorno;
    }

    public static function obtenerNombreFisico($sRuta)
    {
        return basename($sRuta);
    }

    public static function eliminar($sRuta)
    {
        unlink($sRuta);
    }

    public static function moverArchivoSubido($sRutaOrigen, $sRutaDestino)
    {
        move_uploaded_file($sRutaOrigen, $sRutaDestino);
    }

    public static function obtenerExtension($sRuta)
    {
    	return pathinfo($sRuta, PATHINFO_EXTENSION);
    }

    public static function escribir($oRsHandle, $sTexto)
    {
        fwrite($oRsHandle, $sTexto);
    }

    public static function abrir($sRuta, $sOperacion= 'a+')
    {
        return fopen($sRuta, $sOperacion);
    }

    public static function cerrar($oRsHandle)
    {
        fclose($oRsHandle);
    }

    public static function escribirTexto($sRuta, $sTexto)
    {
        $oRes= self::abrir($sRuta);
        self::escribir($oRes, $sTexto);
        self::cerrar($oRes);
    }

    public static function obtenerArchivosDeDirectorio($sRuta)
    {
        $dirHandler = opendir( $sRuta );
        $arrArchivos = array();

        while ($sResourceName = readdir( $dirHandler ))
        {
            if ($sResourceName != '.' && $sResourceName != '..')
            {

                $arrArchivos[] = $sResourceName;
            }
        }
        closedir( $dirHandler );

        return $arrArchivos;
    }

    //Guardar archivo en base de datos
    //guardarFileBase("pr_categorias", "Imagen", "IDCategoria", 2, $_FILES, "Tipo", "Tamanio")
    public static function guardarFileBase($tabla, $nombreCampo, $nombreID, $ID, $file, $nombreTipo, $nombreTamanio, $nombreOriginal="")
    {
        if(!sizeof($file)==0)
        {
            $tamanio = $file["size"];
            $tipo = $file["type"];
            $archivo = $file["tmp_name"];

            $fp = fopen($archivo, "rb");
            //LEEMOS EL CONTENIDO DEL ARCHIVO
            $contenido = fread($fp, $tamanio);
            //CON LA FUNCION addslashes AGREGAMOS UN \ A CADA COMILLA SIMPLE ' PORQUE DE OTRA MANERA
            //NOS MARCARIA ERROR A LA HORA DE REALIZAR EL INSERT EN NUESTRA TABLA
            $contenido = addslashes($contenido);
            //CERRAMOS EL ARCHIVO
            fclose($fp);
            try
            {
                //$db = Database::getInstance(USAR_PDO == 1);
                if($nombreOriginal != "")
                    $query = "UPDATE ".$tabla." SET ".$nombreCampo." = '".$contenido."', ".$nombreTipo."='".$tipo."', ".$nombreTamanio."='".$tamanio."', ".$nombreOriginal." = '".$archivo."' WHERE ".$nombreID." = '".$ID."'";
                else
                    $query = "UPDATE ".$tabla." SET ".$nombreCampo." = '".$contenido."', ".$nombreTipo."='".$tipo."', ".$nombreTamanio."='".$tamanio."' WHERE ".$nombreID." = '".$ID."'";
                mysql_connect(Database::$host, Database::$username, Database::$password);
                mysql_select_db(Database::$base);
                mysql_query($query);
            }
            catch(Exception $ex)
            {
                echo $ex;
            }
        }
    }
}
?>
