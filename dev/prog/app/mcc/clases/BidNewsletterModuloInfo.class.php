<?php

Jota::incluir(array('clases' => array('Entidad'),
    'helpers' => array('Listado', 'DBHelper', 'Upload')
        )
);

class BidNewsletterModuloInfo extends Entidad {

    private $ID;
    private $IDNewsletter;
    private $Titulo;
    private $Descripcion;
    private $Color;
    private $TextoBoton;
    private $Link;
    private $FechaAlta;

    // <editor-fold defaultstate="collapsed" desc="propiedades">
    public function getID() {
        return $this->ID;
    }

    public function setID($ID) {
        $this->ID = $ID;
    }

    public function getTitulo() {
        return $this->Titulo;
    }

    public function setTitulo($Titulo) {
        $this->Titulo = $Titulo;
    }

    public function getIDNewsletter() {
        return $this->IDNewsletter;
    }

    public function setIDNewsletter($IDNewsletter) {
        $this->IDNewsletter = $IDNewsletter;
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

    function getDescripcion() {
        return $this->Descripcion;
    }

    function getColor() {
        return $this->Color;
    }

    function setDescripcion($Descripcion) {
        $this->Descripcion = $Descripcion;
    }

    function setColor($Color) {
        $this->Color = $Color;
    }

    function getTextoBoton() {
        return $this->TextoBoton;
    }

    function setTextoBoton($TextoBoton) {
        $this->TextoBoton = $TextoBoton;
    }

            //</editor-fold>

    public function agregar() {
        
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);

        $iRetorno = 0;

        $data = Array ("Titulo" => $this->getTitulo(),
                "Link" => $this->getLink(),
                "IDNewsletter" => $this->getIDNewsletter(),
                "Color" => $this->getColor(),
                "TextoBoton" => $this->getTextoBoton(),
                "Descripcion" => $this->getDescripcion(),
                "FechaAlta" => $db->now()
            );
        $id = $db->insert('bid_newsletter_modulo_info', $data);
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
                "IDNewsletter" => $this->getIDNewsletter(),
                "Color" => $this->getColor(),
                "TextoBoton" => $this->getTextoBoton(),
                "Descripcion" => $this->getDescripcion(),
        );
        $db->where ('ID', $this->getID());
        if ($db->update ('bid_newsletter_modulo_info', $data))
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
        $db->delete('bid_newsletter_modulo_info');
        
    }

    public static function obtenerId($Id) {
        if (Data::vacio($Id)) {
            throw new ErrorException("Datos incorrectos, obtener es por id o por login.");
        }

        $oRetorno = null;

        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);

        $Id = Data::ifNull($Id, 0);
        
        $query = "select pub.* from bid_newsletter_modulo_info pub
                    where pub.ID= '" . $Id . "'";

        $iResultado = 0;
        $sMsgError = '';

        $result = $db->rawQuery($query);
        $oRetorno = new BidNewsletterModuloInfo();
        foreach ($result as $arrRes) {
            $oRetorno->setID($arrRes ['ID']);
            $oRetorno->setIDNewsletter($arrRes ['IDNewsletter']);
            $oRetorno->setFechaAlta(date('Y-m-d', strtotime($arrRes ['FechaAlta'])));
            $oRetorno->setTitulo($arrRes ['Titulo']);
            $oRetorno->setLink($arrRes ['Link']);
            $oRetorno->setDescripcion($arrRes ['Descripcion']);
            $oRetorno->setColor($arrRes ['Color']);
            $oRetorno->setTextoBoton($arrRes ['TextoBoton']);
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
        
        $query = "select pub.* from bid_newsletter_modulo_info pub
                    where pub.IDNewsletter= '" . $ID . "'";

        $iResultado = 0;
        $sMsgError = '';

        $result = $db->rawQuery($query);
        foreach ($result as $arrRes) {
            $obj = new BidNewsletterModuloInfo();
            $obj->setID($arrRes ['ID']);
            $obj->setIDNewsletter($arrRes ['IDNewsletter']);
            $obj->setFechaAlta(date('Y-m-d', strtotime($arrRes ['FechaAlta'])));
            $obj->setTitulo($arrRes ['Titulo']);
            $obj->setLink($arrRes ['Link']);
            $obj->setDescripcion($arrRes ['Descripcion']);
            $obj->setColor($arrRes ['Color']);
            $obj->setTextoBoton($arrRes ['TextoBoton']);
            
            $oRetorno[]=$obj;
        }
        return $oRetorno;
    }

}

?>
