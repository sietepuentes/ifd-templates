<?php
    require_once '../../../frm/core/ini.php';
    Jota::incluir(   array  ('helpers' => array('PageBo','Page','Html','Json'),
                             'clases' => array('BidNewsletter', 'BidNewsletterModuloInfo')
                            )
    );

//header('Content-Type: text/html; charset=utf8');
$ID = Page::getOpcional("id","");

$objeto = BidNewsletter::obtenerId($ID);
$data=array();
if(!is_null($objeto))
{
    $data['txtTitulo'] = $objeto->getTitulo();
    $data['txtBajada'] = $objeto->getBajada();
    $data['txtTextoBarraAzul1'] = $objeto->getTextoBarraAzul1();
    $data['txtTextoBarraAzul2'] = $objeto->getTextoBarraAzul2();
    $data['txtTextoBarraAzul3'] = $objeto->getTextoBarraAzul3();
    $data['txtDescripcionFinal'] = $objeto->getDescripcionFinal();
    $data['chkColumna'] = $objeto->getColumna();
    $data['txtComentarioFinal'] = $objeto->getComentarioFinal();
    
    $data['txtFacebook'] = $objeto->getFacebook();
    $data['txtTwitter'] = $objeto->getTwitter();
    $data['txtYoutube'] = $objeto->getYoutube();
    $data['txtInstagram'] = $objeto->getInstagram();
    $data['txtGoogle'] = $objeto->getGoogle();
    
    if(is_null($objeto->getImagen()) || ($objeto->getImagen() == ""))
        $data['imagen'] = "";
    else
    {
        $data['imagen'] = $objeto->getImagen()."?r=".rand();
        $data['path_img'] = WEB_ROOT_URL.Config::PATH_IMAGENES_FRONT;
        $data['path_img_del'] = Config::PATH_IMAGENES;
    }
    
    //Modulos
    $modulos = BidNewsletterModuloInfo::listadoModulos($ID);
    $tabla = "";
    for($i=0;$i<count($modulos);$i++)
    {
        $tabla.='<input type="hidden" value="'.$modulos[$i]->getID().'" name="hidIDModulo_'.$i.'" id="hidIDModulo_'.$i.'">
                <div class="row" id="info_'.$i.'">
                    <div class="col-lg-11">
                        <div class="form-group">
                            <input class="form-control" placeholder="Titulo" id="txtTitulo_'.$i.'" name="txtTitulo_'.$i.'" required autofocus value="'.$modulos[$i]->getTitulo().'">
                        </div>
                    </div>
                    <div class="col-lg-1">
                        <div class="form-group">
                            <button id="button" onclick="eliminarModulo('.$i.');" class="btn btn-xs btn-primary" style="margin-left: -10px;margin-top: 4px;" type="button">X</button>
                        </div>
                    </div>
                    <div class="col-lg-11">
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Descripcion" id="txtDescripcion_'.$i.'" name="txtDescripcion_'.$i.'">'.$modulos[$i]->getDescripcion().'</textarea>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="form-group">
                            <input class="form-control posicionParaColor"  id="selected-color'.$i.'" name="selected-color'.$i.'" value="'.$modulos[$i]->getColor().'">
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="form-group">
                            <a class="btn btn-mini dropdown-toggle btn-primary" data-toggle="dropdown">Color Boton</a>
                            <ul class="dropdown-menu" style="position:relative">
                                <li><div id="colorpalette'.$i.'"></div></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-11">
                        <div class="form-group">
                            <input class="form-control" placeholder="Texto Boton" id="txtTextoBoton_'.$i.'" name="txtTextoBoton_'.$i.'" value="'.$modulos[$i]->getTextoBoton().'">
                        </div>
                    </div>
                    <div class="col-lg-11">
                        <div class="form-group">
                            <input class="form-control" placeholder="Link" id="txtLink_'.$i.'" name="txtLink_'.$i.'" value="'.$modulos[$i]->getLink().'">
                        </div>
                    </div>
                    <div class="col-lg-12"><hr></div>
                </div>';
        
        $tabla.="<script>$('#colorpalette".$i."').colorPalette().on('selectColor', function(e) {
                    $('#selected-color".$i."').val(e.color);
                });</script>";
    }
    $data['info'] = $tabla;
    $data['cantInf'] = $i;
    
}
echo JSon::encode($data);
?>