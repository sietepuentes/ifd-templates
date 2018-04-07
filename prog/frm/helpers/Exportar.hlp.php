<?php

/**
 *
 *
 * @author Jota
 */
class Exportar
{
    /**
     * Exporta el resultado de un store procedure a .CSV
     * [dami] lo modifique un poco porque la verdad estaba bastante cabeza...
     * tambien le puse un par de comentarios para que se entienda que carajo hace
     *
     * @param string $fileName nombre del archivo de salida
     * @param string $procedureName nombre del store procedure
     * @param array $arrParametros ej:array('fechaDesde'=> $fechaDesde,'fechaHasta'=> $fechaHasta)
     */
    public static function csv($fileName, $procedureName, $arrParametros)
    {
        $db = Database::getInstance();

        $sql = '';
        $arrValores = array();

        $sql .= '( ';
        foreach ($arrParametros as $key => $val)
        {
            $sql .= "?, ";
            array_push( $arrValores, $val );
        }
        //[dami]le saca la ultima coma
        $sql = substr( $sql, 0, -2 );
        $sql .= ')';

        $consulta = $db->prepare( "call $procedureName $sql" );

        $consulta->execute( $arrValores );

        $arrResultados = $consulta->fetchAll( PDO::FETCH_ASSOC );
        $consulta->closeCursor();

        self::generarCsv($fileName, $arrResultados);
    }

    /**
     * Genera una archivo CSV y lo manda en el response.
     *
     * @param string $fileName el nombre del archivo
     * @param Array $arrResultados un array de resutlados
     */
    public static function generarCsv($fileName, $arrResultados)
    {
        $colNames = '';
        $registros = '';
        //TODO [dami] ver que carajo pasa si no hay resultados
        if (MySql::tieneResultados( $arrResultados ))
        {
            $columnas = array_keys( $arrResultados[0] );
            foreach ($columnas as $campo)
            {
                $colNames .= "$campo; ";
            }

            //[dami]le saca los dos ultimos caracteres y les encaja un retorno de carro
            $colNames = substr( $colNames, 0, -2 ) . "\r\n";
            foreach ($arrResultados as $fila)
            {
                foreach ($fila as $campo)
                {
                    $registros .= self::escapearCsv( $campo ) . ";";
                }
                $registros .= "\n";
            }

            header( "Pragma: public" );
            header( "Expires: 0" );
            header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
            header( "Content-Type: application/force-download" );
            header( "Content-Type: application/octet-stream" );
            header( "Content-Type: application/download" );
            header( "Content-Disposition: attachment;filename=$fileName.csv" );
            header( "Content-Transfer-Encoding: binary " );

            //TODO [dami]ver si en un futuro podemos armar un string de retorno
            //de modo que este metodo no afecte la response

            echo $colNames;
            echo $registros;
        }
    }

    public static function escapearCsv($str)
    {
        $search = array(';', ',');
        $replace = array(' ', ' ');
        $str = str_ireplace( $search, $replace, $str );
        return $str;
    }

}

?>