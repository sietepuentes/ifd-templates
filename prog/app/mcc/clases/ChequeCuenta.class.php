<?php
    Jota::incluir(   array  (  'clases' => array('Entidad'),
                             'helpers' => array('Listado','DBHelper','PDOHelper')
                            )
    );

class ChequeCuenta extends Entidad
{
    private $IDCuenta;
    private $Numero;
    private $IDCooperativa;
    private $Habilitado;
    private $FechaAlta;

    // <editor-fold defaultstate="collapsed" desc="propiedades">
    function getIDCuenta() {
        return $this->IDCuenta;
    }

    function getNumero() {
        return $this->Numero;
    }

    function getIDCooperativa() {
        return $this->IDCooperativa;
    }

    function getHabilitado() {
        return $this->Habilitado;
    }

    function getFechaAlta() {
        return $this->FechaAlta;
    }

    function setIDCuenta($IDCuenta) {
        $this->IDCuenta = $IDCuenta;
    }

    function setNumero($Numero) {
        $this->Numero = $Numero;
    }

    function setIDCooperativa($IDCooperativa) {
        $this->IDCooperativa = $IDCooperativa;
    }

    function setHabilitado($Habilitado) {
        $this->Habilitado = $Habilitado;
    }

    function setFechaAlta($FechaAlta) {
        $this->FechaAlta = $FechaAlta;
    }

    //</editor-fold>

    public function editar(){
        return $this->agregar();
    }
    
    public function agregar()
    {
        $db = Database::getInstance(USAR_PDO == 1);
        try
        {
            if($this->getIDCuenta()<=0)
            {
                $query="INSERT INTO cheque_cuentas (Numero, IDCooperativa, Habilitado, FechaAlta)
                            VALUES ('".$this->getNumero()."',  '".$this->getIDCooperativa()."', '".$this->getHabilitado()."',
                                    NOW())";
                
                $stmt= $db->prepare($query);
                $stmt->execute();

                $id = $db->lastInsertId();
                if ($id > 0)
                    $this->setIDCuenta($id);
                else
                    throw new ErrorException ("No me devolvio un id > 0", "-1", null, null, null, null);
            }
            else
            {
                $query="UPDATE cheque_cuentas SET
                            Numero = '".$this->getNumero()."',
                            IDCooperativa = '".$this->getIDCooperativa()."',
                            Habilitado = '".$this->getHabilitado()."' 
                        WHERE IDCuenta = '".$this->getIDCuenta()."'";
                $stmt= $db->prepare($query);
                $stmt->execute();
            }
            return true;
        }
        catch(Exception $ex)
        {
            
        }
    }

    public function eliminar()
    {
        $db = Database::getInstance(USAR_PDO == 1);
        try
        {
            $query="DELETE FROM cheque_cuentas WHERE IDCuenta = '".$this->getIDCuenta()."'";
            $stmt= $db->prepare($query);
            $stmt->execute();
        }catch(Exception $ex)
        {
            
        }
    }


    public static function obtener($Id= null)
    {
        if (Data::vacio($Id))
        {
            throw new ErrorException("Datos incorrectos, obtener es por id o por login.");
        }

        $oRetorno = null;

        $iResultado= 0;
        $sMsgError= '';

        $Id= Data::ifNull($Id, 0);
        
        $sql = "SELECT * FROM cheque_cuentas usu WHERE usu.IDCuenta= '".$Id."'";
        
        $oRes = PDOHelper::query($sql);
        if (!Data::vacio($oRes)&& !Data::vacio($oRes[0]))
        {
            $iResultado= $oRes[0];

            if(!($iResultado>0))
                throw new WebarException ($sMsgError, null, $iResultado);
            else
            {
                $oRetorno = new ChequeCuenta();
                $oRetorno->setIDCuenta($oRes[0]['IDCuenta']);
                $oRetorno->setNumero($oRes[0]['Numero']);
                $oRetorno->setIDCooperativa($oRes[0]['IDCooperativa']);
                $oRetorno->setHabilitado($oRes[0]['Habilitado']);
            }
        }
        $db= null;
        return $oRetorno;
    }
    
    
    public static function obtenerListado($page, $limit, $sOrderField, $sOrderDir, $searchField, $searchOper, $searchVal, $sWhere)
    {
        $sSelectFrom= "select e.IDCuenta, e.Numero, c.Nombre as Cooperativa 
                        from cheque_cuentas e 
                        inner join sueldo_cooperativas c on e.IDCooperativa = c.IDCooperativa ";

        return Listado::obtenerListadoPaginado($sSelectFrom, $sWhere, $page, $limit, $sOrderField, $sOrderDir, $searchField, $searchOper, $searchVal);
    }
}
?>
