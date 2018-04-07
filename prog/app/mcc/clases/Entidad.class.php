<?php

abstract class Entidad
{

    protected $_id;
    protected $_nombre;
    protected $_fechaAlta;
    protected $_activo;

    //propiedades
    //<editor-fold>

    public function getId()
    {
        return $this->_id;
    }

    public function getNombre()
    {
        return $this->_nombre;
    }

    public function getFechaAlta()
    {
        return $this->_fechaAlta;
    }

    public function getActivo()
    {
        return $this->_activo;
    }

    public function setId($_id)
    {
        $this->_id = $_id;
    }

    public function setNombre($_nombre)
    {
        $this->_nombre = $_nombre;
    }

    public function setFechaAlta($_fechaAlta)
    {
        $this->_fechaAlta = $_fechaAlta;
    }

    public function setActivo($activo)
    {
        $this->_activo = $activo;
    }

    //calculadas
    public function esNuevo()
    {
        return ($this->getId() < 0);
    }

    //</editor-fold>
    //constructores
    //<editor-fold>

    function __construct($id= null)
    {
        if (Data::vacio($id))
            $this->setId(- 1);
        else
            $this->setId($id);

        $this->setFechaAlta(date('Y-m-d h:i:s'));
//        $this->set_fechaAlta(date('Y-m-d'));
    }

    //</editor-fold>
    //metodos
    //<editor-fold>

    public static function mapear($oEntidad, $arrFila)
    {
        try
        {
            $oEntidad->setId($arrFila ['id']);
            $oEntidad->setNombre($arrFila ['nombre']);
            //$oEntidad->setActivo((bool) $arrFila ['activo']);
            $oEntidad->setFechaAlta(date('Y-m-d', strtotime($arrFila ['fecha_alta'])));
        }
        catch (ErrorException $e)
        {
            throw new ErrorException("Error de mapeo en Entidad", null, null, null, null, $e);
        }
    }

    protected function validar()
    {
        //[dami]metodo template para validacion de datos
        //lo unico que hay que hacer es sobreescribir este metodo y arrojar
        //una webarexception en caso de no validar
    }

    public function guardar()
    {
        $lngRetorno = null;

        $this->validar();

        if ($this->esNuevo())
        {
            $lngRetorno = $this->agregar();
        }
        else
        {
            $lngRetorno = $this->editar();
        }
        return $lngRetorno;
    }

    abstract protected function agregar();

    abstract protected function editar();

    protected function getParametros()
    {
        $arrRetorno= array(
            ':p_id'=> (integer)$this->getId(),
            ':p_nombre'=> (string)$this->getNombre(),
            //':p_activo'=> (int)$this->getActivo(),
            ':p_fecha_alta'=> $this->getFechaAlta()
        );

        return $arrRetorno;
    }

    //</editor-fold>
}
?>