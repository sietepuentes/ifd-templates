<?php
require_once "../../../frm/core/ini.php";
Jota::incluir(   array  (   'clases' => array('BidNew', 'BidNewModuloInfo'),
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

$oObjeto = new BidNew();

switch ($operacion)
{
    case "add":
        $oObjeto->setTitulo(Page::getOpcional("txtTitulo", ""));   
        
        $oObjeto->setFacebook(Page::getOpcional("txtFacebook", ""));
        $oObjeto->setTwitter(Page::getOpcional("txtTwitter", ""));
        $oObjeto->setYoutube(Page::getOpcional("txtYoutube", ""));
        $oObjeto->setInstagram(Page::getOpcional("txtInstagram", ""));
        $oObjeto->setGoogle(Page::getOpcional("txtGoogle", ""));
        
        try
        {
            $oObjeto->agregar();
            if($oObjeto->getIDNew()>0)
            {
                $cantInf = Page::getOpcional("hidCantInf",0);
                for($i=0;$i<$cantInf;$i++)
                {
                    if(isset($_POST["txtTitulo_".$i]) && $_POST["txtTitulo_".$i] != ""){
                        $modulo = new BidNewModuloInfo();
                        $modulo->setIDNew($oObjeto->getIDNew());
                        $modulo->setTitulo($_POST["txtTitulo_".$i]);
                        $modulo->setTituloNota($_POST["txtTituloNota_".$i]);
                        $modulo->setDescripcion((Page::getOpcional("txtDescripcion_".$i, "")));
                        $modulo->setLink((Page::getOpcional("txtLink_".$i, "")));
                        
                        $modulo->Guardar();
                        
                        if($modulo->getID()>0)
                        {
                            $handle = new upload($_FILES["imagen_".$i]);
                            if ($handle->uploaded)
                            {
                                $nombreArchivoSinExt = strtotime("now");
                                $handle->file_new_name_body = $nombreArchivoSinExt;
                                //$handle->jpeg_quality = 80;
                                $handle->file_overwrite = true;
                                $handle->Process(Config::PATH_IMAGENES);
                                if ($handle->processed)
                                {
                                    $nombre = $nombreArchivoSinExt.".".$handle->file_src_name_ext;
                                    $nombre = str_replace(array(' ', '-'), array('_','_'), $nombre) ;
                                    $handle->clean();
                                    $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
                                    $db = new Mysqlidb($mysqli);

                                    $data = Array (
                                            "Imagen" => $nombre
                                    );
                                    $db->where ('ID', $modulo->getID());
                                    $ok = $db->update ('bid_news_modulo_info', $data);
                                }
                            }
                        }
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
        $oObjeto->setIDNew(Page::getOpcional("id", ""));
        $oObjeto->setTitulo(Page::getOpcional("txtTitulo", ""));  
        
        $oObjeto->setFacebook(Page::getOpcional("txtFacebook", ""));
        $oObjeto->setTwitter(Page::getOpcional("txtTwitter", ""));
        $oObjeto->setYoutube(Page::getOpcional("txtYoutube", ""));
        $oObjeto->setInstagram(Page::getOpcional("txtInstagram", ""));
        $oObjeto->setGoogle(Page::getOpcional("txtGoogle", ""));

        try
        {
            $oObjeto->editar();

            if($oObjeto->getIDNew()>0)
            {
                
                $cantInf = Page::getOpcional("hidCantInf",0);
                for($i=0;$i<$cantInf;$i++)
                {
                    $modulo = new BidNewModuloInfo();
                    if(isset($_POST["txtTitulo_".$i]) && $_POST["txtTitulo_".$i] != ""){
                        if(isset($_POST["hidIDModulo_".$i]))
                            $modulo->setID($_POST["hidIDModulo_".$i]);
                        $modulo->setIDNew($oObjeto->getIDNew());
                        $modulo->setTitulo($_POST["txtTitulo_".$i]);
                        $modulo->setDescripcion((Page::getOpcional("txtDescripcion_".$i, "")));
                        $modulo->setTituloNota($_POST["txtTituloNota_".$i]);
                        $modulo->setLink((Page::getOpcional("txtLink_".$i, "")));

                        $modulo->Guardar();
                        
                        $handle = new upload($_FILES["imagen_".$i]);
                        if ($handle->uploaded)
                        {
                            $nombreArchivoSinExt = strtotime("now");
                            $handle->file_new_name_body = $nombreArchivoSinExt;
                            //$handle->jpeg_quality = 80;
                            $handle->file_overwrite = true;
                            $handle->Process(Config::PATH_IMAGENES);
                            if ($handle->processed)
                            {
                                $nombre = $nombreArchivoSinExt.".".$handle->file_src_name_ext;
                                $nombre = str_replace(array(' ', '-'), array('_','_'), $nombre) ;
                                $handle->clean();
                                $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
                                $db = new Mysqlidb($mysqli);

                                $data = Array (
                                        "Imagen" => $nombre
                                );
                                $db->where ('ID', $modulo->getID());
                                $ok = $db->update ('bid_news_modulo_info', $data);
                            }
                        }
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
        $oObjeto->setIDNew(Page::get("id"));
        $oObjeto->eliminar();
        echo '{"tipo":"OK","mensaje": "Se elmino correctamente."}';
        exit;
        break;
    default:
    {
        $strFilter=" 1=1 ";
        if($sidx=="" || $sidx=="id")
        {
            $sidx = "IDNew";
            $sord = "DESC";
        }
        //echo JqGrid::armarJson(BidNew::obtenerListado($page, $rows, $sidx, $sord , $searchField, $searchOper, $searchString,$strFilter));
        $sWhere="";
        echo BidNew::obtenerListado($sWhere);
    }
}
?>