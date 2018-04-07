<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsuarioFB
 *
 * @author Lucas
 */
date_default_timezone_set('UTC');
class UsuarioFB {

    protected $id;
    protected $uid;
    protected $nombre;
    protected $apellido;
    protected $urlFoto;
    protected $fechaAlta;

    //put your code here
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUid() {
        return $this->uid;
    }

    public function setUid($uid) {
        $this->uid = $uid;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function getUrlFoto() {
        return $this->urlFoto;
    }

    public function setUrlFoto($urlFoto) {
        $this->urlFoto = $urlFoto;
    }

    public function getFechaAlta() {
        return $this->fechaAlta;
    }

    public function setFechaAlta($fechaAlta) {
        $this->fechaAlta = $fechaAlta;
    }


    public function __construct()
    {
            $this->id		= 0;
            $this->uid 		= '';
            $this->nombre       = '';
            $this->apellido     = '';
            $this->urlFoto      = '';
            $this->fechaAlta	= Data::formatoFecha('-3 hours');
    }    

    public static function mapear($oUsuario, $oFila)
    {
        try
        {
            $oUsuario->setId($oFila ['id']);
            $oUsuario->setUid($oFila ['uid']);
            $oUsuario->setNombre($oFila ['nombre']);
            $oUsuario->setApellido($oFila ['apellido']);
            $oUsuario->setUrlFoto($oFila ['url_foto']);
            $oUsuario->setFechaAlta(Data::formatoFecha($oFila ['fecha_alta']));
        }
        catch (ErrorException $e)
        {
            throw new ErrorException("Error de mapeo en UsuarioFB", null, null, null, null, $e);
        }
    }

}
?>
