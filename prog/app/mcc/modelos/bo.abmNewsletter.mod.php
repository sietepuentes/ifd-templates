<?php
require_once "../../../frm/core/ini.php";
Jota::incluir(   array  (   'clases' => array('BidNewsletter', 'BidNewsletterModuloInfo'),
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

$oObjeto = new BidNewsletter();

switch ($operacion)
{
    case "add":
        $oObjeto->setTitulo(Page::getOpcional("txtTitulo", ""));
        $oObjeto->setBajada(Page::getOpcional("txtBajada", ""));
        $oObjeto->setTextoBarraAzul1(Page::getOpcional("txtTextoBarraAzul1", ""));
        $oObjeto->setTextoBarraAzul2(Page::getOpcional("txtTextoBarraAzul2", ""));
        $oObjeto->setTextoBarraAzul3(Page::getOpcional("txtTextoBarraAzul3", ""));
        $oObjeto->setDescripcionFinal(Page::getOpcional("txtDescripcionFinal", ""));
        $oObjeto->setComentarioFinal(Page::getOpcional("txtComentarioFinal", ""));
        $oObjeto->setColumna(Page::getOpcional("chkColumna","0"));        
        
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
            $oObjeto->agregar();
            if($oObjeto->getIDNewsletter()>0)
            {
                
                $cantInf = Page::getOpcional("hidCantInf",0);
                for($i=0;$i<$cantInf;$i++)
                {
                    if(isset($_POST["txtTitulo_".$i]) && $_POST["txtTitulo_".$i] != ""){
                        $modulo = new BidNewsletterModuloInfo();
                        $modulo->setIDNewsletter($oObjeto->getIDNewsletter());
                        $modulo->setTitulo($_POST["txtTitulo_".$i]);
                        $modulo->setDescripcion((Page::getOpcional("txtDescripcion_".$i, "")));
                        $modulo->setTextoBoton((Page::getOpcional("txtTextoBoton_".$i, "")));
                        $modulo->setColor((Page::getOpcional("selected-color".$i, "")));
                        $modulo->setLink((Page::getOpcional("txtLink_".$i, "")));
                        
                        $modulo->Guardar();
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
        $oObjeto->setIDNewsletter(Page::getOpcional("id", ""));
        $oObjeto->setTitulo(Page::getOpcional("txtTitulo", ""));
        $oObjeto->setBajada(Page::getOpcional("txtBajada", ""));
        $oObjeto->setTextoBarraAzul1(Page::getOpcional("txtTextoBarraAzul1", ""));
        $oObjeto->setTextoBarraAzul2(Page::getOpcional("txtTextoBarraAzul2", ""));
        $oObjeto->setTextoBarraAzul3(Page::getOpcional("txtTextoBarraAzul3", ""));
        $oObjeto->setDescripcionFinal(Page::getOpcional("txtDescripcionFinal", ""));
        $oObjeto->setComentarioFinal(Page::getOpcional("txtComentarioFinal", ""));
        $oObjeto->setColumna(Page::getOpcional("chkColumna","0"));      
        
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

            if($oObjeto->getIDNewsletter()>0)
            {
                
                $cantInf = Page::getOpcional("hidCantInf",0);
                for($i=0;$i<$cantInf;$i++)
                {
                    $modulo = new BidNewsletterModuloInfo();
                    if(isset($_POST["txtTitulo_".$i]) && $_POST["txtTitulo_".$i] != ""){
                        if(isset($_POST["hidIDModulo_".$i]))
                            $modulo->setID($_POST["hidIDModulo_".$i]);
                        $modulo->setIDNewsletter($oObjeto->getIDNewsletter());
                        $modulo->setTitulo($_POST["txtTitulo_".$i]);
                        $modulo->setDescripcion((Page::getOpcional("txtDescripcion_".$i, "")));
                        $modulo->setTextoBoton((Page::getOpcional("txtTextoBoton_".$i, "")));
                        $modulo->setColor((Page::getOpcional("selected-color".$i, "")));
                        $modulo->setLink((Page::getOpcional("txtLink_".$i, "")));

                        $modulo->Guardar();
                    }
                    else
                    {
                        if(isset($_POST["hidIDModulo_".$i]))
                        {
                            $modulo->setID($_POST["hidIDModulo_".$i]);
                            $modulo->eliminar();
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
        $oObjeto->setIDNewsletter(Page::get("id"));
        $oObjeto->eliminar();
        echo '{"tipo":"OK","mensaje": "Se elmino correctamente."}';
        exit;
        break;
    default:
    {
        $strFilter=" 1=1 ";
        if($sidx=="" || $sidx=="id")
        {
            $sidx = "IDNewsletter";
            $sord = "DESC";
        }
        //echo JqGrid::armarJson(BidNewsletter::obtenerListado($page, $rows, $sidx, $sord , $searchField, $searchOper, $searchString,$strFilter));
        $sWhere="";
        echo BidNewsletter::obtenerListado($sWhere);
    }
}
?>