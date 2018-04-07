<?php

Jota::incluir(array('clases' => array('Entidad'),
    'helpers' => array('Listado', 'DBHelper', 'Upload')
        )
);

class BidPublicacionLink extends Entidad {

    private $ID;
    private $IDPublicacion;
    private $Pais;
    private $Link;
    private $FechaAlta;

    // <editor-fold defaultstate="collapsed" desc="propiedades">
    public function getID() {
        return $this->ID;
    }

    public function setID($ID) {
        $this->ID = $ID;
    }

    public function getPais() {
        return $this->Pais;
    }

    public function setPais($Pais) {
        $this->Pais = $Pais;
    }

    public function getIDPublicacion() {
        return $this->IDPublicacion;
    }

    public function setIDPublicacion($IDPublicacion) {
        $this->IDPublicacion = $IDPublicacion;
    }

    public function getFechaAlta() {
        return $this->FechaAlta;
    }

    public function setFechaAlta($FechaAlta) {
        $this->FechaAlta = $FechaAlta;
    }

    function getLink() {
        return $this->Link;
    }

    function setLink($Link) {
        $this->Link = $Link;
    }

    //</editor-fold>

    public function agregar() {
        
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);

        $iRetorno = 0;

        $data = Array ("Pais" => $this->getPais(),
                "Link" => $this->getLink(),
                "IDPublicacion" => $this->getIDPublicacion(),
                "FechaAlta" => $db->now()
            );
        $id = $db->insert('bid_publicacion_links', $data);
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
            'Pais' => $this->getPais(),
            'Link' => $this->getLink(),
            'IDPublicacion' => $this->getIDPublicacion()
        );
        $db->where ('ID', $this->getID());
        if ($db->update ('bid_publicacion_links', $data))
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
        $db->delete('bid_publicacion_links');
        
    }

    public static function obtenerId($Id) {
        if (Data::vacio($Id)) {
            throw new ErrorException("Datos incorrectos, obtener es por id o por login.");
        }

        $oRetorno = null;

        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);

        $Id = Data::ifNull($Id, 0);
        
        $query = "select pub.* from bid_publicacion_links pub
                    where pub.ID= '" . $Id . "'";

        $iResultado = 0;
        $sMsgError = '';

        $result = $db->rawQuery($query);
        $oRetorno = new BidPublicacionLink();
        foreach ($result as $arrRes) {
            $oRetorno->setID($arrRes ['ID']);
            $oRetorno->setIDPublicacion($arrRes ['IDPublicacion']);
            $oRetorno->setFechaAlta(date('Y-m-d', strtotime($arrRes ['FechaAlta'])));
            $oRetorno->setPais($arrRes ['Pais']);
            $oRetorno->setLink($arrRes ['Link']);
        }
        return $oRetorno;
    }
    
    public static function listadoLinks($ID)
    {
        if (Data::vacio($ID))
        {
            throw new ErrorException("Datos incorrectos, obtener es por id.");
        }
        
        $oRetorno = array();
        
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);
        
        $query = "select pub.* from bid_publicacion_links pub
                    where pub.IDPublicacion= '" . $ID . "'";

        $iResultado = 0;
        $sMsgError = '';

        $result = $db->rawQuery($query);
        foreach ($result as $arrRes) {
            $obj = new BidPublicacionLink();
            $obj->setID($arrRes ['ID']);
            $obj->setIDPublicacion($arrRes ['IDPublicacion']);
            $obj->setFechaAlta(date('Y-m-d', strtotime($arrRes ['FechaAlta'])));
            $obj->setPais($arrRes ['Pais']);
            $obj->setLink($arrRes ['Link']);
            
            $oRetorno[]=$obj;
        }
        return $oRetorno;
    }

}

?>
