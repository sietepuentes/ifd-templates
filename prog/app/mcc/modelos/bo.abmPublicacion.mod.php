<?php
require_once "../../../frm/core/ini.php";
Jota::incluir(   array  (   'clases' => array('BidPublicacion', 'BidPublicacionAutor', 'BidPublicacionLink'),
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

$oObjeto = new BidPublicacion();

switch ($operacion)
{
    case "add":
        $oObjeto->setTitulo(Page::getOpcional("txtTitulo", ""));
        $oObjeto->setBajada(Page::getOpcional("txtBajada", ""));
        $oObjeto->setColor(Page::getOpcional("selected-color", ""));
        $oObjeto->setDescripcion(Page::getOpcional("txtDescripcion", ""));
        
        $oObjeto->setFacebook(Page::getOpcional("txtFacebook", ""));
        $oObjeto->setTwitter(Page::getOpcional("txtTwitter", ""));
        $oObjeto->setYoutube(Page::getOpcional("txtYoutube", ""));
        $oObjeto->setInstagram(Page::getOpcional("txtInstagram", ""));
        $oObjeto->setGoogle(Page::getOpcional("txtGoogle", ""));
        
        /*if(isset($_FILES["imagen"]))
            $oObjeto->setImagen($_FILES["imagen"]);
        else
            $oObjeto->setImagen("");*/
        $oObjeto->setImagen(Page::getOpcional("hidImagen", ""));
        
        try
        {
            $oObjeto->agregar();
            if($oObjeto->getIDPublicacion()>0)
            {
                $cantAut = Page::getOpcional("hidCantAut",0);
                for($i=0;$i<$cantAut;$i++)
                {
                    if(isset($_POST["txtAutor_".$i]) && $_POST["txtAutor_".$i] != ""){
                        $autor = new BidPublicacionAutor();
                        $autor->setIDPublicacion($oObjeto->getIDPublicacion());
                        $autor->setNombre($_POST["txtAutor_".$i]);
                        
                        $autor->Guardar();
                    }
                }
                
                $cantLin = Page::getOpcional("hidCantLin",0);
                for($i=0;$i<$cantLin;$i++)
                {
                    if(isset($_POST["txtPais_".$i]) && $_POST["txtPais_".$i] != ""){
                        $link = new BidPublicacionLink();
                        $link->setIDPublicacion($oObjeto->getIDPublicacion());
                        $link->setPais($_POST["txtPais_".$i]);
                        $link->setLink($_POST["txtLink_".$i]);
                        
                        $link->Guardar();
                    }
                }
                
                echo '{"tipo":"OK","mensaje": "' .utf8_encode('Se cargo correctamente.') .'"}';
            }
            else
                echo '{"tipo":"ERROR_ALTA_USUARIO","mensaje": "Error, intente nuevamente."}';
        }
        catch( WebarException $ex)
        {
            echo '{"tipo":"ERROR_ALTA","mensaje": "'.$ex->getMessage().'"}';
        }

        break;
    case "edit":
        $oObjeto->setIDPublicacion(Page::getOpcional("id", ""));
        $oObjeto->setTitulo(Page::getOpcional("txtTitulo", ""));
        $oObjeto->setBajada(Page::getOpcional("txtBajada", ""));
        $oObjeto->setColor(Page::getOpcional("selected-color", ""));
        $oObjeto->setDescripcion(Page::getOpcional("txtDescripcion", ""));
        
        $oObjeto->setFacebook(Page::getOpcional("txtFacebook", ""));
        $oObjeto->setTwitter(Page::getOpcional("txtTwitter", ""));
        $oObjeto->setYoutube(Page::getOpcional("txtYoutube", ""));
        $oObjeto->setInstagram(Page::getOpcional("txtInstagram", ""));
        $oObjeto->setGoogle(Page::getOpcional("txtGoogle", ""));
        
        /*if(isset($_FILES["imagen"]))
            $oObjeto->setImagen($_FILES["imagen"]);
        else
            $oObjeto->setImagen("");
        */
        $oObjeto->setImagen(Page::getOpcional("hidImagen", ""));
        try
        {
            $oObjeto->editar();

            if($oObjeto->getIDPublicacion()>0)
            {
                $cantAut = Page::getOpcional("hidCantAut",0);
                for($i=0;$i<$cantAut;$i++)
                {
                    $autor = new BidPublicacionAutor();
                    if(isset($_POST["txtAutor_".$i]) && $_POST["txtAutor_".$i] != ""){
                        if(isset($_POST["hidIDAutor_".$i]))
                            $autor->setID($_POST["hidIDAutor_".$i]);
                        $autor->setIDPublicacion($oObjeto->getIDPublicacion());
                        $autor->setNombre($_POST["txtAutor_".$i]);

                        $autor->Guardar();
                    }
                    else
                    {
                        if(isset($_POST["hidIDAutor_".$i]))
                        {
                            $autor->setID($_POST["hidIDAutor_".$i]);
                            $autor->eliminar();
                        }
                    }
                }
                
                $cantLin = Page::getOpcional("hidCantLin",0);
                for($i=0;$i<$cantLin;$i++)
                {
                    $link = new BidPublicacionLink();
                    if(isset($_POST["txtPais_".$i]) && $_POST["txtPais_".$i] != ""){
                        if(isset($_POST["hidIDPais_".$i]))
                            $link->setID($_POST["hidIDPais_".$i]);
                        $link->setIDPublicacion($oObjeto->getIDPublicacion());
                        $link->setPais($_POST["txtPais_".$i]);
                        $link->setLink($_POST["txtLink_".$i]);

                        $link->Guardar();
                    }
                    else
                    {
                        if(isset($_POST["hidIDPais_".$i]))
                        {
                            $link->setID($_POST["hidIDPais_".$i]);
                            $link->eliminar();
                        }
                    }
                }
                
                echo '{"tipo":"OK","mensaje": "' .utf8_encode('Se modifico correctamente.') .'"}';
            }
            else
                echo '{"tipo":"ERROR_ALTA","mensaje": "Error, intente nuevamente."}';
        }
        catch( WebarException $ex)
        {
            echo '{"tipo":"ERROR_ALTA","mensaje": "'.$ex->getMessage().'"}';
        }

        break;
    case "del":
        $oObjeto->setIDPublicacion(Page::get("id"));
        $oObjeto->eliminar();
        echo '{"tipo":"OK","mensaje": "Se elmino correctamente."}';
        exit;
        break;
    default:
    {
        $strFilter=" 1=1 ";
        if($sidx=="" || $sidx=="id")
        {
            $sidx = "IDPublicacion";
            $sord = "DESC";
        }
        //echo JqGrid::armarJson(BidPublicacion::obtenerListado($page, $rows, $sidx, $sord , $searchField, $searchOper, $searchString,$strFilter));
        $sWhere="";
        echo BidPublicacion::obtenerListado($sWhere);
    }
}
?>