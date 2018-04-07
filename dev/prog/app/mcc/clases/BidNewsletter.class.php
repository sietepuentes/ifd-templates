<?php

Jota::incluir(array('clases' => array('Entidad'),
    'helpers' => array('Listado', 'DBHelper', 'Upload')
        )
);

class BidNewsletter extends Entidad {

    private $IDNewsletter;
    private $Titulo;
    private $Bajada;
    private $Imagen;
    private $TextoBarraAzul1;
    private $TextoBarraAzul2;
    private $TextoBarraAzul3;
    private $DescripcionFinal;
    private $ComentarioFinal;
    private $Columna;
    private $Facebook;
    private $Twitter;
    private $Youtube;
    private $Instagram;
    private $Google;
    
    private $FechaAlta;
    

    // <editor-fold defaultstate="collapsed" desc="propiedades">
    function getIDNewsletter() {
        return $this->IDNewsletter;
    }

    function getTitulo() {
        return $this->Titulo;
    }

    function getBajada() {
        return $this->Bajada;
    }

    function getImagen() {
        return $this->Imagen;
    }

    function getTextoBarraAzul1() {
        return $this->TextoBarraAzul1;
    }

    function getTextoBarraAzul2() {
        return $this->TextoBarraAzul2;
    }

    function getTextoBarraAzul3() {
        return $this->TextoBarraAzul3;
    }

    function getDescripcionFinal() {
        return $this->DescripcionFinal;
    }

    function getComentarioFinal() {
        return $this->ComentarioFinal;
    }

    function getFechaAlta() {
        return $this->FechaAlta;
    }

    function setIDNewsletter($IDNewsletter) {
        $this->IDNewsletter = $IDNewsletter;
    }

    function setTitulo($Titulo) {
        $this->Titulo = $Titulo;
    }

    function setBajada($Bajada) {
        $this->Bajada = $Bajada;
    }

    function setImagen($Imagen) {
        $this->Imagen = $Imagen;
    }

    function setTextoBarraAzul1($TextoBarraAzul1) {
        $this->TextoBarraAzul1 = $TextoBarraAzul1;
    }

    function setTextoBarraAzul2($TextoBarraAzul2) {
        $this->TextoBarraAzul2 = $TextoBarraAzul2;
    }

    function setTextoBarraAzul3($TextoBarraAzul3) {
        $this->TextoBarraAzul3 = $TextoBarraAzul3;
    }

    function setDescripcionFinal($DescripcionFinal) {
        $this->DescripcionFinal = $DescripcionFinal;
    }

    function setComentarioFinal($ComentarioFinal) {
        $this->ComentarioFinal = $ComentarioFinal;
    }

    function setFechaAlta($FechaAlta) {
        $this->FechaAlta = $FechaAlta;
    }

    function getColumna() {
        return $this->Columna;
    }

    function setColumna($Columna) {
        $this->Columna = $Columna;
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

    
    //</editor-fold>

    public function agregar() {
        
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);

        $iRetorno = 0;

        $data = Array ("Titulo" => $this->getTitulo(),
                "Bajada" => $this->getBajada(),
                "Imagen" => $this->getImagen(),
                "FechaAlta" => $db->now(),
                "TextoBarraAzul1" => $this->getTextoBarraAzul1(),
                "TextoBarraAzul2" => $this->getTextoBarraAzul2(),
                "TextoBarraAzul3" => $this->getTextoBarraAzul3(),
                "DescripcionFinal" => $this->getDescripcionFinal(),
                "ComentarioFinal" => $this->getComentarioFinal(),
                "Columna" => $this->getColumna(),
                "Facebook" => $this->getFacebook(),
                "Twitter" => $this->getTwitter(),
                "Instagram" => $this->getInstagram(),
                "Youtube" => $this->getYoutube(),
                "Google" => $this->getGoogle()
            );
        $id = $db->insert('bid_newsletters', $data);
        if($id)
        {
            $this->setIDNewsletter($id);

            //GUARDO LA IMAGEN
            /*$handle = new upload($this->Imagen);
            if ($handle->uploaded)
            {
                $nombreArchivoSinExt = $this->getIDNewsletter()."news";
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
                    $db->where ('IDNewsletter', $this->getIDNewsletter());
                    $db->update ('bid_newsletters', $data);
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
                
        //Borro imagen si antes tenia y ahora llega una nueva
        $objAux = BidNewsletter::obtenerId($this->getIDNewsletter());
        if($this->getImagen() != "" && $objAux->getImagen() != $this->getImagen() && $objAux->getImagen() != "")
            @unlink(Config::PATH_IMAGENES.$objAux->getImagen());
        
        $data = Array ("Titulo" => $this->getTitulo(),
                "Bajada" => $this->getBajada(),
                "FechaAlta" => $db->now(),
                "Imagen" => $this->getImagen(),
                "TextoBarraAzul1" => $this->getTextoBarraAzul1(),
                "TextoBarraAzul2" => $this->getTextoBarraAzul2(),
                "TextoBarraAzul3" => $this->getTextoBarraAzul3(),
                "DescripcionFinal" => $this->getDescripcionFinal(),
                "ComentarioFinal" => $this->getComentarioFinal(),
                "Columna" => $this->getColumna(),
                "Facebook" => $this->getFacebook(),
                "Twitter" => $this->getTwitter(),
                "Instagram" => $this->getInstagram(),
                "Youtube" => $this->getYoutube(),
                "Google" => $this->getGoogle()
        );
        $db->where ('IDNewsletter', $this->getIDNewsletter());
        if ($db->update ('bid_newsletters', $data))
        {
            //GUARDO LA IMAGEN
            /*$handle = new upload($this->Imagen);
            if ($handle->uploaded)
            {
                $nombreArchivoSinExt = $this->getIDNewsletter()."news";
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
                    $db->where ('IDNewsletter', $this->getIDNewsletter());
                    $db->update ('bid_newsletters', $data);
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
        
        $db->where('IDNewsletter', $this->getIDNewsletter() );
        $db->delete('bid_newsletters');
        
    }

    public static function obtenerId($lngId) {
        if (Data::vacio($lngId)) {
            throw new ErrorException("Datos incorrectos, obtener es por id o por login.");
        }

        $oRetorno = null;

        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);

        $lngId = Data::ifNull($lngId, 0);
        
        $query = "select pub.* from bid_newsletters pub
                    where pub.IDNewsletter= '" . $lngId . "'";

        $iResultado = 0;
        $sMsgError = '';

        $result = $db->rawQuery($query);
        $oRetorno = new BidNewsletter();
        foreach ($result as $arrRes) {
            $oRetorno->setIDNewsletter($arrRes ['IDNewsletter']);
            $oRetorno->setId($arrRes ['IDNewsletter']);
            $oRetorno->setFechaAlta(date('Y-m-d', strtotime($arrRes ['FechaAlta'])));
            $oRetorno->setTitulo($arrRes ['Titulo']);
            $oRetorno->setBajada($arrRes ['Bajada']);
            $oRetorno->setImagen($arrRes ['Imagen']);
            $oRetorno->setTextoBarraAzul1($arrRes ['TextoBarraAzul1']);
            $oRetorno->setTextoBarraAzul2($arrRes ['TextoBarraAzul2']);
            $oRetorno->setTextoBarraAzul3($arrRes ['TextoBarraAzul3']);
            $oRetorno->setDescripcionFinal($arrRes ['DescripcionFinal']);
            $oRetorno->setComentarioFinal($arrRes ['ComentarioFinal']);
            $oRetorno->setColumna($arrRes ['Columna']);
            
            $oRetorno->setFacebook($arrRes ['Facebook']);
            $oRetorno->setTwitter($arrRes ['Twitter']);
            $oRetorno->setYoutube($arrRes ['Youtube']);
            $oRetorno->setInstagram($arrRes ['Instagram']);
            $oRetorno->setGoogle($arrRes ['Google']);
        }
        if ($oRetorno->getIDNewsletter() <= 0)
            throw new WebarException("El usuario/password es incorrecto.", null, $iResultado);

        return $oRetorno;
    }
    
    public static function cantidadActual() {
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);
        $iResultado = 0;
        $query = "select count(*) as cant from bid_newsletters usu";
        
        $result = $db->rawQuery($query);
        $oRetorno = new BidNewsletter();
        foreach ($result as $arrRes) {
            $iResultado = $arrRes['cant'];
        }
        return $iResultado;
    }

    //public static function obtenerListado($page, $limit, $sOrderField, $sOrderDir, $searchField, $searchOper, $searchVal, $sWhere) {
    public static function obtenerListado($sWhere) {

        $sSelectFrom = "select p.IDNewsletter, p.Titulo, p.Bajada
                        from bid_newsletters p ";

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
