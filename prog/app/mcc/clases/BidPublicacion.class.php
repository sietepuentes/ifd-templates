<?php

Jota::incluir(array('clases' => array('Entidad'),
    'helpers' => array('Listado', 'DBHelper', 'Upload')
        )
);

class BidPublicacion extends Entidad {

    private $IDPublicacion;
    private $Titulo;
    private $Bajada;
    private $Imagen;
    private $Descripcion;
    private $Color;
    private $Columna;
    private $Facebook;
    private $Twitter;
    private $Youtube;
    private $Instagram;
    private $Google;
    
    private $FechaAlta;
    

    // <editor-fold defaultstate="collapsed" desc="propiedades">
    public function getIDPublicacion() {
        return $this->IDPublicacion;
    }

    public function setIDPublicacion($IDPublicacion) {
        $this->IDPublicacion = $IDPublicacion;
    }

    public function getTitulo() {
        return $this->Titulo;
    }

    public function setTitulo($Titulo) {
        $this->Titulo = $Titulo;
    }

    public function getBajada() {
        return $this->Bajada;
    }

    public function setBajada($Bajada) {
        $this->Bajada = $Bajada;
    }

    public function getImagen() {
        return $this->Imagen;
    }

    public function setImagen($Imagen) {
        $this->Imagen = $Imagen;
    }

    public function getFechaAlta() {
        return $this->FechaAlta;
    }

    public function setFechaAlta($FechaAlta) {
        $this->FechaAlta = $FechaAlta;
    }

    public function getDescripcion() {
        return $this->Descripcion;
    }

    public function setDescripcion($Descripcion) {
        $this->Descripcion = $Descripcion;
    }

    function getColor() {
        return $this->Color;
    }

    function setColor($Color) {
        $this->Color = $Color;
    }

    function getColumna() {
        return $this->Columna;
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

    function setColumna($Columna) {
        $this->Columna = $Columna;
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
                "FechaAlta" => $db->now(),
                "Imagen" => $this->getImagen(),
                "Color" => $this->getColor(),
                "Descripcion" => $this->getDescripcion(),
                "Facebook" => $this->getFacebook(),
                "Twitter" => $this->getTwitter(),
                "Instagram" => $this->getInstagram(),
                "Youtube" => $this->getYoutube(),
                "Google" => $this->getGoogle()
            );
        $id = $db->insert('bid_publicaciones', $data);
        if($id)
        {
            $this->setIDPublicacion($id);

            //GUARDO LA IMAGEN
            /*
            $handle = new upload($this->Imagen);
            if ($handle->uploaded)
            {
                $nombreArchivoSinExt = $this->getIDPublicacion();
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
                    $db->where ('IDPublicacion', $this->getIDPublicacion());
                    $db->update ('bid_publicaciones', $data);
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
        $objAux = BidPublicacion::obtenerId($this->getIDPublicacion());
        if($this->getImagen() != "" && $objAux->getImagen() != $this->getImagen() && $objAux->getImagen() != "")
            @unlink(Config::PATH_IMAGENES.$objAux->getImagen());
        
        $data = Array (
            'Titulo' => $this->getTitulo(),
            'Bajada' => $this->getBajada(),
            "Imagen" => $this->getImagen(),
            "Color" => $this->getColor(),
            'Descripcion' => $this->getDescripcion(),
            "Facebook" => $this->getFacebook(),
            "Twitter" => $this->getTwitter(),
            "Instagram" => $this->getInstagram(),
            "Youtube" => $this->getYoutube(),
            "Google" => $this->getGoogle()
        );
        $db->where ('IDPublicacion', $this->getIDPublicacion());
        if ($db->update ('bid_publicaciones', $data))
        {
            //GUARDO LA IMAGEN
            /*
            $handle = new upload($this->Imagen); 
            if ($handle->uploaded)
            {
                $nombreArchivoSinExt = $this->getIDPublicacion();
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
                    $db->where ('IDPublicacion', $this->getIDPublicacion());
                    $db->update ('bid_publicaciones', $data);
                }
            }
             */
            return true;
        }
        else
            return false;
        
        $db = null;
    }

    public function eliminar() {
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);
        
        $db->where('IDPublicacion', $this->getIDPublicacion() );
        $db->delete('bid_publicaciones');
        
    }

    public static function obtenerId($lngId) {
        if (Data::vacio($lngId)) {
            throw new ErrorException("Datos incorrectos, obtener es por id o por login.");
        }

        $oRetorno = null;

        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);

        $lngId = Data::ifNull($lngId, 0);
        
        $query = "select pub.* from bid_publicaciones pub
                    where pub.IDPublicacion= '" . $lngId . "'";

        $iResultado = 0;
        $sMsgError = '';

        $result = $db->rawQuery($query);
        $oRetorno = new BidPublicacion();
        foreach ($result as $arrRes) {
            $oRetorno->setIDPublicacion($arrRes ['IDPublicacion']);
            $oRetorno->setId($arrRes ['IDPublicacion']);
            $oRetorno->setFechaAlta(date('Y-m-d', strtotime($arrRes ['FechaAlta'])));
            $oRetorno->setTitulo($arrRes ['Titulo']);
            $oRetorno->setBajada($arrRes ['Bajada']);
            $oRetorno->setColor($arrRes ['Color']);
            $oRetorno->setImagen($arrRes ['Imagen']);
            $oRetorno->setDescripcion($arrRes ['Descripcion']);
            
            $oRetorno->setFacebook($arrRes ['Facebook']);
            $oRetorno->setTwitter($arrRes ['Twitter']);
            $oRetorno->setYoutube($arrRes ['Youtube']);
            $oRetorno->setInstagram($arrRes ['Instagram']);
            $oRetorno->setGoogle($arrRes ['Google']);
        }
        if ($oRetorno->getIDPublicacion() <= 0)
            throw new WebarException("El usuario/password es incorrecto.", null, $iResultado);

        return $oRetorno;
    }
    
    public static function cantidadActual() {
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);
        $iResultado = 0;
        $query = "select count(*) as cant from bid_publicaciones usu";
        
        $result = $db->rawQuery($query);
        $oRetorno = new BidPublicacion();
        foreach ($result as $arrRes) {
            $iResultado = $arrRes['cant'];
        }
        return $iResultado;
    }

    //public static function obtenerListado($page, $limit, $sOrderField, $sOrderDir, $searchField, $searchOper, $searchVal, $sWhere) {
    public static function obtenerListado($sWhere) {

        $sSelectFrom = "select p.IDPublicacion, p.Titulo, p.Bajada
                        from bid_publicaciones p ";

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
