<?php

final class NivelesDeDebug
{
    const BAJO = 0;
    const MEDIO = 1;
    const ALTO= 2;

    // ensures that this class acts like an enum
    // and that it cannot be instantiated
    private function __construct()
    {
        
    }
}

Jota::incluir(array('helpers'=> 'MySql'));

class ErrorHandler
{

    /**
     *  Analiza un array de resultados y dispara excepciones en el caso de encontrar un error.
     * @param <array> $arrResultados
     */
    public static function analizar($arrResultados)
    {
        $iResultado = (int)$arrResultados[0]['resultado'];

        if (!($iResultado > 0))
            throw new WebarException( null, null, $iResultado );
    }

    public static function manejarError(Exception $ex, $sDestino= null)
    {
        switch(Config::NIVEL_DEBUG)
        {
            case NivelesDeDebug::MEDIO:
                Page::alertar(ErrorHandler::getMensaje($ex), $sDestino);
                break;
            case NivelesDeDebug::ALTO:
                Page::alertar(ErrorHandler::getMensaje($ex), $sDestino);
                break;
            case NivelesDeDebug::BAJO:
            default:
                throw $ex;
                break;
        }
    }

    public static function getMensaje($ex)
    {
        Jota::log('ERROR', $ex->__toString());

        $sRetorno = "";

        switch(Config::NIVEL_DEBUG)
        {
            case NivelesDeDebug::MEDIO:
                $sRetorno = $ex->__toString();
                break;
            case NivelesDeDebug::ALTO:
                $sRetorno = "Sucedió un error inesperado.";
                break;
            case NivelesDeDebug::BAJO:
            default:
                $sRetorno = $ex->__toString();
                break;
        }

        return $sRetorno;
    }

}

?>
