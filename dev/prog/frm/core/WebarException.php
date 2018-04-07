<?php

final class CodigosDeError {
    //BO
    const USUARIO_REPETIDO              = -2;
    //FRONT
    const USUARIO_INVALIDO              = -100;
    const FRASE_NO_GUARDADA             = -101;
    

    
    // ensures that this class acts like an enum
    // and that it cannot be instantiated
    private function __construct()
    {
        
    }

}

class WebarException extends Exception
{
    private $datosAdicionales;
    public function getDatosAdicionales()
    {
        return $this->datosAdicionales;
    }

    public function setDatosAdicionales($datosAdicionales)
    {
        $this->datosAdicionales = $datosAdicionales;
    }

    function __construct($sMensaje= null, $oExOriginal= null, $iCodigo= -1, $datosAdicionales= null)
    {
        if($datosAdicionales!= null)
            $this->setDatosAdicionales($datosAdicionales);
        //$this->setDatosAdicionales(json_decode ($datosAdicionales, true));

        if(!Data::vacio($iCodigo))
            parent::__construct((!Data::vacio($sMensaje) ? $sMensaje : $this->getMensajeError($iCodigo)), $iCodigo);
        else
            parent::__construct ($sMensaje, -1, $oExOriginal);
//            Jota::log ('error', $oExOriginal);
            //[dami]ojo porque parece que en el php 5.1.6 hay un constructor que no admite anidadas
//            Jota::log($oExOriginal);
    }

    protected function getMensajeError($iCodigo)
    {
        $sRetorno= "";
        switch($iCodigo)
        {
            case CodigosDeError::USUARIO_REPETIDO:
                $sRetorno= "Ya existe un usuario con ese id.";
                break;
            case CodigosDeError::USUARIO_INVALIDO:
                $sRetorno= "El usuario no esta registrado";
                break;
            default :
                $sRetorno= "Error inesperado";
                break;
        }

        return $sRetorno;
    }
}

?>