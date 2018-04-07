<?php

Jota::incluir(array('clases' => array('Entidad'),
    'helpers' => array('Listado', 'DBHelper', 'Upload')
        )
);

class BidNew extends Entidad {

    private $IDNew;
    private $Titulo;
    private $Facebook;
    private $Twitter;
    private $Youtube;
    private $Instagram;
    private $Google;
    
    private $FechaAlta;
    

    // <editor-fold defaultstate="collapsed" desc="propiedades">
    function getIDNew() {
        return $this->IDNew;
    }

    function getTitulo() {
        return $this->Titulo;
    }

    function getFacebook() {
        return $this->Facebook;
    }

    function getTwitter() {
        return $this->Twitter;
    }

    function getYoutube() {
        return $this->Youtube;
    }

    function getInstagram() {
        return $this->Instagram;
    }

    function getGoogle() {
        return $this->Google;
    }

    function getFechaAlta() {
        return $this->FechaAlta;
    }

    function setIDNew($IDNew) {
        $this->IDNew = $IDNew;
    }

    function setTitulo($Titulo) {
        $this->Titulo = $Titulo;
    }

    function setFacebook($Facebook) {
        $this->Facebook = $Facebook;
    }

    function setTwitter($Twitter) {
        $this->Twitter = $Twitter;
    }

    function setYoutube($Youtube) {
        $this->Youtube = $Youtube;
    }

    function setInstagram($Instagram) {
        $this->Instagram = $Instagram;
    }

    function setGoogle($Google) {
        $this->Google = $Google;
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
                "FechaAlta" => $db->now(),
                "Facebook" => $this->getFacebook(),
                "Twitter" => $this->getTwitter(),
                "Instagram" => $this->getInstagram(),
                "Youtube" => $this->getYoutube(),
                "Google" => $this->getGoogle()
            );
        $id = $db->insert('bid_news', $data);
        if($id)
        {
            $this->setIDNew($id);

            //GUARDO LA IMAGEN
            /*$handle = new upload($this->Imagen);
            if ($handle->uploaded)
            {
                $nombreArchivoSinExt = $this->getIDNew()."news";
                $handle->file_new_name_body = $nombreArchivoSinExt;
                //$handle->jpeg_quality = 80;
                $handle->file_overwrite = true;
                $handle->Process(Config::PATH_IMAGENES);
                if ($handle->processed)
                {
                    $nombre = $nombreArchivoSinExt.".".$handle->file_src_name_ext;
                    $nombre = str_replace(array(' ', '-'), array('_','_'), $nombre) ;
                    $handle->clean();
                    $this->Imagen = $nombre;
                    
                    $data = Array (
                        'Imagen' => $this->getImagen()
                    );
                    $db->where ('IDNew', $this->getIDNew());
                    $db->update ('bid_news', $data);
                }
            }*/
        } else
            throw new ErrorException("No me devolvio un id > 0", "-1", null, null, null, null);

        $db = null;
        return $iRetorno;
    }

    public function editar() {
        $iRetorno = 0;
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);
                
        $data = Array ("Titulo" => $this->getTitulo(),
                "FechaAlta" => $db->now(),
                "Facebook" => $this->getFacebook(),
                "Twitter" => $this->getTwitter(),
                "Instagram" => $this->getInstagram(),
                "Youtube" => $this->getYoutube(),
                "Google" => $this->getGoogle()
        );
        $db->where ('IDNew', $this->getIDNew());
        if ($db->update ('bid_news', $data))
        {
            //GUARDO LA IMAGEN
            /*$handle = new upload($this->Imagen);
            if ($handle->uploaded)
            {
                $nombreArchivoSinExt = $this->getIDNew()."news";
                $handle->file_new_name_body = $nombreArchivoSinExt;
                //$handle->jpeg_quality = 80;
                $handle->file_overwrite = true;
                $handle->Process(Config::PATH_IMAGENES);
                if ($handle->processed)
                {
                    $nombre = $nombreArchivoSinExt.".".$handle->file_src_name_ext;
                    $nombre = str_replace(array(' ', '-'), array('_','_'), $nombre) ;
                    $handle->clean();
                    $this->Imagen = $nombre;
                    
                    $data = Array (
                        'Imagen' => $this->getImagen()
                    );
                    $db->where ('IDNew', $this->getIDNew());
                    $db->update ('bid_news', $data);
                }
            }*/
            return true;
        }
        else
            return false;
        
        $db = null;
    }

    public function eliminar() {
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);
        
        $db->where('IDNew', $this->getIDNew() );
        $db->delete('bid_news');
        
    }

    public static function obtenerId($lngId) {
        if (Data::vacio($lngId)) {
            throw new ErrorException("Datos incorrectos, obtener es por id o por login.");
        }

        $oRetorno = null;

        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);

        $lngId = Data::ifNull($lngId, 0);
        
        $query = "select pub.* from bid_news pub
                    where pub.IDNew= '" . $lngId . "'";

        $iResultado = 0;
        $sMsgError = '';

        $result = $db->rawQuery($query);
        $oRetorno = new BidNew();
        foreach ($result as $arrRes) {
            $oRetorno->setIDNew($arrRes ['IDNew']);
            $oRetorno->setId($arrRes ['IDNew']);
            $oRetorno->setFechaAlta(date('Y-m-d', strtotime($arrRes ['FechaAlta'])));
            $oRetorno->setTitulo($arrRes ['Titulo']);
            
            $oRetorno->setFacebook($arrRes ['Facebook']);
            $oRetorno->setTwitter($arrRes ['Twitter']);
            $oRetorno->setYoutube($arrRes ['Youtube']);
            $oRetorno->setInstagram($arrRes ['Instagram']);
            $oRetorno->setGoogle($arrRes ['Google']);
        }
        if ($oRetorno->getIDNew() <= 0)
            throw new WebarException("El usuario/password es incorrecto.", null, $iResultado);

        return $oRetorno;
    }
    
    public static function cantidadActual() {
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);
        $iResultado = 0;
        $query = "select count(*) as cant from bid_news usu";
        
        $result = $db->rawQuery($query);
        $oRetorno = new BidNew();
        foreach ($result as $arrRes) {
            $iResultado = $arrRes['cant'];
        }
        return $iResultado;
    }

    //public static function obtenerListado($page, $limit, $sOrderField, $sOrderDir, $searchField, $searchOper, $searchVal, $sWhere) {
    public static function obtenerListado($sWhere) {

        $sSelectFrom = "select p.IDNew, p.Titulo
                        from bid_news p ";

        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);
        
        $result = $db->rawQuery($sSelectFrom);
        $arr=array();
        foreach ($result as $arrRes) {
            $arr[] = $arrRes;
        }
        $json_response = json_encode($arr);
        return $json_response;
        
        //return Listado::obtenerListadoPaginado($sSelectFrom, $sWhere, $page, $limit, $sOrderField, $sOrderDir, $searchField, $searchOper, $searchVal);
    }

}

?>
