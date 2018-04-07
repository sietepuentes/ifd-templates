<?php

/**
 *  Las PDOException traen en su interior la info del error propia del driver (en este caso mysql)
 *  Esta pseudo-enum contiene los index del array que contiene esa info de error
 *  y constantes que representan estos codigos.
 *
 * @author dami
 */
final class InfoDeErroresMySql //pseudo enum
{
    //errorInfo keys
    const SQLSTATE = 0; //SQLSTATE error code (a five characters alphanumeric identifier defined in the ANSI SQL standard).
    const SPEC_ERR_CODE = 1; //Driver specific error code.
    const SPEC_ERR_MSG = 2; //Driver specific error message.

    //error codes
    const UNIQUE_VIOLATION= 1062;

    // ensures that this class acts like an enum
    // and that it cannot be instantiated
    private function __construct()
    {

    }
}

/**
 * El helper de mysql posee cositas que nos facilitan el trabajo contra una bd mysql.
 *
 * @author dami
 */
class MySql
{
    // <editor-fold defaultstate="collapsed" desc="metodos">

    /**
     *  Determina si un array de resutados obtuvo algun registro.
     * @param array $arrResultados
     * @return bool
     */
    public static function tieneResultados($arrResultados)
    {
        return (!Data::vacio( $arrResultados ) && !Data::vacio( $arrResultados[0] ));
    }

    /**
     *  Retorna el codigo de error interno ocurrido en la base de datos mysql
     * @param PDOException $pdoEx
     * @return int
     */
    public static function getErrorCode(PDOException $pdoEx)
    {
        return $pdoEx->errorInfo[InfoDeErroresMySql::SPEC_ERR_CODE];
    }

    // </editor-fold>
}
?>