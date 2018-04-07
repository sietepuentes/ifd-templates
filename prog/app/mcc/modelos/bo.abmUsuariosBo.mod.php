<?php
require_once "../../../frm/core/ini.php";
Jota::incluir(   array  (   'clases' => array('BidUsuarioBo'),
                            'helpers' => array('PageBo','Page','JqGrid')
                        )
);


$sidx = Page::getOpcional("sidx", "");
$sord = Page::getOpcional("sord", "");

$operacion = Page::getOpcional("oper", "");
$page = Page::getOpcional("page", "");
$rows = Page::getOpcional("rows", "");
$searchField = Page::getOpcional("searchField", "");
$searchOper = Page::getOpcional("searchOper", "");
$searchString = Page::getOpcional("searchString", "");
$strFilter  = "";

$oUsuario = new BidUsuarioBo();

switch ($operacion)
{
    case "add":
        $oUsuario->setNombre(Page::get("txtNombre"));
        $oUsuario->setApellido(Page::get("txtApellido"));
        $oUsuario->setUsuario(Page::get("txtUsuario"));
        $oUsuario->setClave(Page::get("txtPassword"));
        $oUsuario->setEmail(Page::get("txtEmail"));
        
        try
        {
            $oUsuario->agregar();
            if($oUsuario->getIDUsuario()==-2)
                echo '{"tipo":"ERROR_ALTA_USUARIO","mensaje": "' .utf8_encode('El usuario ingresado ya existe.') .'"}';
            else
            {
                if($oUsuario->getIDUsuario()>0)
                    echo '{"tipo":"OK","mensaje": "' .utf8_encode('Se cargo correctamente el usuario.') .'"}';
                else
                    echo '{"tipo":"ERROR_ALTA_USUARIO","mensaje": "Error, intente nuevamente."}';
            }
        }
        catch( WebarException $ex)
        {
            echo '{"tipo":"ERROR_ALTA_USUARIO","mensaje": "'.$ex->getMessage().'"}';
        }

        break;
    case "edit":
        $oUsuario->setIDUsuario(Page::get("id"));
        $oUsuario->setNombre(Page::get("txtNombre"));
        $oUsuario->setApellido(Page::get("txtApellido"));
        $oUsuario->setUsuario(Page::get("txtUsuario"));
        $oUsuario->setClave(Page::get("txtPassword"));
        $oUsuario->setEmail(Page::get("txtEmail"));
        
        try
        {
            $oUsuario->editar();
            if($oUsuario->getIDUsuario()==-2)
                echo '{"tipo":"ERROR_ALTA_USUARIO","mensaje": "' .utf8_encode('El usuario ingresado ya existe.') .'"}';
            else
            {
                if($oUsuario->getIDUsuario()>0)
                    echo '{"tipo":"OK","mensaje": "' .utf8_encode('Se cargo modifico el usuario.') .'"}';
                else
                    echo '{"tipo":"ERROR_ALTA_USUARIO","mensaje": "Error, intente nuevamente."}';
            }
        }
        catch( WebarException $ex)
        {
            echo '{"tipo":"ERROR_ALTA_USUARIO","mensaje": "'.$ex->getMessage().'"}';
        }

        break;
    case "del":
        $oUsuario->setIDUsuario(Page::get("id"));
        $oUsuario->eliminar();
        echo '{"tipo":"OK","mensaje": "Se elmino correctamente."}';
        exit;
        break;
    default:
    {
        $strFilter=" 1=1 ";
        if($sidx=="" || $sidx=="id")
        {
            $sidx = "IDUsuario";
            $sord = "DESC";
        }
        //echo JqGrid::armarJson(BidUsuarioBo::obtenerListado($page, $rows, $sidx, $sord , $searchField, $searchOper, $searchString,$strFilter));
        $sWhere="";
        echo BidUsuarioBo::obtenerListado($sWhere);
    }
}
?>