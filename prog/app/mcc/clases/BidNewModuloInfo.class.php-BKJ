<?php

Jota::incluir(array('clases' => array('Entidad'),
    'helpers' => array('Listado', 'DBHelper', 'Upload')
        )
);

class BidNewModuloInfo extends Entidad {

    private $ID;
    private $IDNew;
    private $Titulo;
    private $TituloNota;
    private $Descripcion;
    private $Imagen;
    private $Link;
    private $FechaAlta;

    // <editor-fold defaultstate="collapsed" desc="propiedades">
    
    function getID() {
        return $this->ID;
    }

    function getIDNew() {
        return $this->IDNew;
    }

    function getTitulo() {
        return $this->Titulo;
    }

    function getTituloNota() {
        return $this->TituloNota;
    }

    function getDescripcion() {
        return $this->Descripcion;
    }

    function getImagen() {
        return $this->Imagen;
    }

    function getLink() {
        return $this->Link;
    }

    function getFechaAlta() {
        return $this->FechaAlta;
    }

    function setID($ID) {
        $this->ID = $ID;
    }

    function setIDNew($IDNew) {
        $this->IDNew = $IDNew;
    }

    function setTitulo($Titulo) {
        $this->Titulo = $Titulo;
    }

    function setTituloNota($TituloNota) {
        $this->TituloNota = $TituloNota;
    }

    function setDescripcion($Descripcion) {
        $this->Descripcion = $Descripcion;
    }

    function setImagen($Imagen) {
        $this->Imagen = $Imagen;
    }

    function setLink($Link) {
        $this->Link = $Link;
    }

    function setFechaAlta($FechaAlta) {
        $this->FechaAlta = $FechaAlta;
    }

                //</editor-fold>

    public function agregar() {
        
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);

        $iRetorno = 0;

        $data = Array ("Titulo" => $this->getTitulo(),
                "Link" => $this->getLink(),
                "IDNew" => $this->getIDNew(),
                "TituloNota" => $this->getTituloNota(),
                "Descripcion" => $this->getDescripcion(),
                "FechaAlta" => $db->now()
            );
        $id = $db->insert('bid_news_modulo_info', $data);
        if($id)
        {
            $this->setID($id);
        } else
            throw new ErrorException("No me devolvio un id > 0", "-1", null, null, null, null);

        $db = null;
        return $iRetorno;
    }

    public function editar() {
        $iRetorno = 0;
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);
                
        $data = Array (
                "Titulo" => $this->getTitulo(),
                "Link" => $this->getLink(),
                "IDNew" => $this->getIDNew(),
                "TituloNota" => $this->getTituloNota(),
                "Descripcion" => $this->getDescripcion(),
        );
        $db->where ('ID', $this->getID());
        if ($db->update ('bid_news_modulo_info', $data))
        {
            return true;
        }
        else
            return false;
        
        $db = null;
    }

    public function eliminar() {
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);
        
        $db->where('ID', $this->getID() );
        $db->delete('bid_news_modulo_info');
        
    }

    public static function obtenerId($Id) {
        if (Data::vacio($Id)) {
            throw new ErrorException("Datos incorrectos, obtener es por id o por login.");
        }

        $oRetorno = null;

        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);

        $Id = Data::ifNull($Id, 0);
        
        $query = "select pub.* from bid_news_modulo_info pub
                    where pub.ID= '" . $Id . "'";

        $iResultado = 0;
        $sMsgError = '';

        $result = $db->rawQuery($query);
        $oRetorno = new BidNewModuloInfo();
        foreach ($result as $arrRes) {
            $oRetorno->setID($arrRes ['ID']);
            $oRetorno->setIDNew($arrRes ['IDNew']);
            $oRetorno->setFechaAlta(date('Y-m-d', strtotime($arrRes ['FechaAlta'])));
            $oRetorno->setTitulo($arrRes ['Titulo']);
            $oRetorno->setLink($arrRes ['Link']);
            $oRetorno->setDescripcion($arrRes ['Descripcion']);
            $oRetorno->setTituloNota($arrRes ['TituloNota']);
            $oRetorno->setImagen($arrRes ['Imagen']);
        }
        return $oRetorno;
    }
    
    public static function listadoModulos($ID)
    {
        if (Data::vacio($ID))
        {
            throw new ErrorException("Datos incorrectos, obtener es por id.");
        }
        
        $oRetorno = array();
        
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);
        
        $query = "select pub.* from bid_news_modulo_info pub
                    where pub.IDNew= '" . $ID . "'";

        $iResultado = 0;
        $sMsgError = '';

        $result = $db->rawQuery($query);
        foreach ($result as $arrRes) {
            $obj = new BidNewModuloInfo();
            $obj->setID($arrRes ['ID']);
            $obj->setIDNew($arrRes ['IDNew']);
            $obj->setFechaAlta(date('Y-m-d', strtotime($arrRes ['FechaAlta'])));
            $obj->setTitulo($arrRes ['Titulo']);
            $obj->setLink($arrRes ['Link']);
            $obj->setDescripcion($arrRes ['Descripcion']);
            $obj->setTituloNota($arrRes ['TituloNota']);
            $obj->setImagen($arrRes ['Imagen']);
            
            $oRetorno[]=$obj;
        }
        return $oRetorno;
    }

}

?>
