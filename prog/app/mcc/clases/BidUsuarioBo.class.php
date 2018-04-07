<?php

Jota::incluir(array('clases' => array('Entidad'),
    'helpers' => array('Listado', 'DBHelper')
        )
);

class BidUsuarioBo extends Entidad {

    private $IDUsuario;
    private $Apellido;
    private $Usuario;
    private $Clave;
    private $FechaAlta;
    private $Email;

    // <editor-fold defaultstate="collapsed" desc="propiedades">
    public function getIDUsuario() {
        return $this->IDUsuario;
    }

    public function setIDUsuario($IDUsuario) {
        $this->IDUsuario = $IDUsuario;
    }

    public function getApellido() {
        return $this->Apellido;
    }

    public function setApellido($Apellido) {
        $this->Apellido = $Apellido;
    }

    public function getUsuario() {
        return $this->Usuario;
    }

    public function setUsuario($Usuario) {
        $this->Usuario = $Usuario;
    }

    public function getClave() {
        return $this->Clave;
    }

    public function setClave($Clave) {
        $this->Clave = $Clave;
    }

    public function getFechaAlta() {
        return $this->FechaAlta;
    }

    public function setFechaAlta($FechaAlta) {
        $this->FechaAlta = $FechaAlta;
    }

    public function getEmail() {
        return $this->Email;
    }

    public function setEmail($Email) {
        $this->Email = $Email;
    }

    //</editor-fold>

    protected function getParametros() {
        $arrRetorno = parent::getParametros();

        $arrRetorno[':p_apellido'] = $this->getApellido();
        $arrRetorno[':p_usuario'] = $this->getUsuario();
        $arrRetorno[':p_clave'] = $this->getClave();
        $arrRetorno[':p_'] = $this->getIDConcesionario();

        return $arrRetorno;
    }

    public function agregar() {
        
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);

        $iRetorno = 0;
        
        $query="SELECT count(*) as cant FROM bid_usuarios_bo WHERE Usuario = '".$this->getUsuario()."'";
        $result = $db->rawQuery($query);
        $cant = $result[0]["cant"];
        if($cant>0)
        {
            $this->setIDUsuario(-2);
            return 0;
        }
        $data = Array ("Nombre" => $this->getNombre(),
                "Apellido" => $this->getApellido(),
                "Usuario" => $this->getUsuario(),
                "Clave" => $this->getClave(),
                "FechaAlta" => $db->now(),
                "Email" => $this->getEmail()
            );
        $id = $db->insert('bid_usuarios_bo', $data);
        if($id)
        {
            $this->setId($id);
            $this->setIDUsuario($id);
        } else
            throw new ErrorException("No me devolvio un id > 0", "-1", null, null, null, null);

        $db = null;
        return $iRetorno;
    }

    public function editar() {
        $iRetorno = 0;
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);
        
        $query="SELECT count(*) as cant FROM bid_usuarios_bo WHERE not IDUsuario in ('".$this->getIDUsuario()."') AND Usuario = '".$this->getUsuario()."'";
        $result = $db->rawQuery($query);
        $cant = $result[0]["cant"];
        if($cant>0)
        {
            $this->setIDUsuario(-2);
            return 0;
        }
        
        $data = Array (
            'Nombre' => $this->getNombre(),
            'Apellido' => $this->getApellido(),
            'Usuario' => $this->getUsuario(),
            'Clave' => $this->getClave(),
            'Email' => $this->getEmail()
        );
        $db->where ('IDUsuario', $this->getIDUsuario());
        if ($db->update ('bid_usuarios_bo', $data))
            return true;
        else
            return false;
        
        $db = null;
    }

    public function eliminar() {
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);
        
        $db->where('IDUsuario', $this->getIDUsuario() );
        $db->delete('bid_usuarios_bo');
        
    }

    public static function login($sNombreUsr = null, $sPassword = null, $sSecreto = null) {
        return BidUsuarioBo::obtener(null, $sNombreUsr, $sPassword, $sSecreto);
    }

    public static function obtener($lngId = null, $sNombreUsr = null, $sPassword = null, $sSecreto = null) {
        if (Data::vacio($lngId) && Data::vacio($sNombreUsr) && Data::vacio($sSecreto)) {
            throw new ErrorException("Datos incorrectos, obtener es por id o por login.");
        }

        $oRetorno = null;

        if (Data::vacio($sSecreto))
            $sSecreto = md5($sNombreUsr . $sPassword);

        $mysqli = new mysqli( DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);

        $iResultado = 0;
        $sMsgError = '';

        $lngId = Data::ifNull($lngId, 0);
        $sNombreUsr = Data::ifNull($sNombreUsr, '');

        $query = "select 1 resultado, usu.* from bid_usuarios_bo usu
                    where
                    (   ifnull('" . $lngId . "', 0)!= 0
                        and ifnull('" . $sSecreto . "', '')= ''
                        and usu.IDUsuario= '" . $lngId . "'
                    )
                    or (    ifnull('" . $sSecreto . "', '')!= ''
                            and ifnull('" . $lngId . "', 0)= 0
                            and MD5(CONCAT(usu.Usuario, usu.Clave))= '" . $sSecreto . "'
                        );";
        
        $result = $db->rawQuery($query);
        $oRetorno = new BidUsuarioBo();
        foreach ($result as $arrRes) {
            $oRetorno->setIDUsuario($arrRes ['IDUsuario']);
            $oRetorno->setId($arrRes ['IDUsuario']);
            $oRetorno->setNombre($arrRes ['Nombre']);
            $oRetorno->setFechaAlta(date('Y-m-d', strtotime($arrRes ['FechaAlta'])));
            $oRetorno->setApellido($arrRes ['Apellido']);
            $oRetorno->setUsuario($arrRes ['Usuario']);
            $oRetorno->setClave($arrRes ['Clave']);
            $oRetorno->setEmail($arrRes ['Email']);
        }
        if ($oRetorno->getId() <= 0)
            throw new WebarException("El usuario/password es incorrecto.", null, $iResultado);

        return $oRetorno;
    }

    public static function obtenerId($lngId) {
        if (Data::vacio($lngId)) {
            throw new ErrorException("Datos incorrectos, obtener es por id o por login.");
        }

        $oRetorno = null;

        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);

        $lngId = Data::ifNull($lngId, 0);
        
        $query = "select 1 resultado, usu.* from bid_usuarios_bo usu
                    where
                    (   ifnull('" . $lngId . "', 0)!= 0
                        and usu.IDUsuario= '" . $lngId . "'
                    )
                    or (    ifnull('" . $lngId . "', 0)= 0
                        );";

        $iResultado = 0;
        $sMsgError = '';

        $result = $db->rawQuery($query);
        $oRetorno = new BidUsuarioBo();
        foreach ($result as $arrRes) {
            $oRetorno->setIDUsuario($arrRes ['IDUsuario']);
            $oRetorno->setId($arrRes ['IDUsuario']);
            $oRetorno->setNombre($arrRes ['Nombre']);
            $oRetorno->setFechaAlta(date('Y-m-d', strtotime($arrRes ['FechaAlta'])));
            $oRetorno->setApellido($arrRes ['Apellido']);
            $oRetorno->setUsuario($arrRes ['Usuario']);
            $oRetorno->setClave($arrRes ['Clave']);
            $oRetorno->setEmail($arrRes ['Email']);
        }
        if ($oRetorno->getId() <= 0)
            throw new WebarException("El usuario/password es incorrecto.", null, $iResultado);

        return $oRetorno;
    }
    
    public static function cantidadActual() {
        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);
        $iResultado = 0;
        $query = "select count(*) as cant from bid_usuarios_bo usu";
        
        $result = $db->rawQuery($query);
        $oRetorno = new BidUsuarioBo();
        foreach ($result as $arrRes) {
            $iResultado = $arrRes['cant'];
        }
        return $iResultado;
    }

    //public static function obtenerListado($page, $limit, $sOrderField, $sOrderDir, $searchField, $searchOper, $searchVal, $sWhere) {
    public static function obtenerListado($sWhere) {

        $sSelectFrom = "select u.IDUsuario, u.Nombre, u.Apellido, u.Email, u.Usuario, u.Clave
                        from bid_usuarios_bo u ";

        $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
        $db = new Mysqlidb($mysqli);
        
        $result = $db->rawQuery($sSelectFrom);
        foreach ($result as $arrRes) {
            $arr[] = $arrRes;
        }
        $json_response = json_encode($arr);
        return $json_response;
        
        //return Listado::obtenerListadoPaginado($sSelectFrom, $sWhere, $page, $limit, $sOrderField, $sOrderDir, $searchField, $searchOper, $searchVal);
    }

}

?>
