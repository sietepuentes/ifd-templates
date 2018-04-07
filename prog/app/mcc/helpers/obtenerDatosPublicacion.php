<?php
    require_once '../../../frm/core/ini.php';
    Jota::incluir(   array  ('helpers' => array('PageBo','Page','Html','Json'),
                             'clases' => array('BidPublicacion', 'BidPublicacionAutor', 'BidPublicacionLink')
                            )
    );

//header('Content-Type: text/html; charset=utf8');
$ID = Page::getOpcional("id","");

$objeto = BidPublicacion::obtenerId($ID);
$data=array();
if(!is_null($objeto))
{
    $data['txtTitulo'] = $objeto->getTitulo();
    $data['txtBajada'] = $objeto->getBajada();
    $data['txtDescripcion'] = $objeto->getDescripcion();
    $data['txtColor'] = $objeto->getColor();
    
    $data['txtFacebook'] = $objeto->getFacebook();
    $data['txtTwitter'] = $objeto->getTwitter();
    $data['txtYoutube'] = $objeto->getYoutube();
    $data['txtInstagram'] = $objeto->getInstagram();
    $data['txtGoogle'] = $objeto->getGoogle();
    
    if(is_null($objeto->getImagen()) || ($objeto->getImagen() == ""))
    {
        $data['imagen'] = "";
        $data['imagen_sin_rand']="";
    }
    else
    {
        $data['imagen'] = $objeto->getImagen()."?r=".rand();
        $data['imagen_sin_rand'] = $objeto->getImagen();
        $data['path_img'] = WEB_ROOT_URL.Config::PATH_IMAGENES_FRONT;
        $data['path_img_del'] = Config::PATH_IMAGENES;
    }
    
    //Autores
    $autores = BidPublicacionAutor::listadoAutores($ID);
    $tabla = "";
    for($i=0;$i<count($autores);$i++)
    {
        $tabla.='<input type="hidden" value="'.$autores[$i]->getID().'" name="hidIDAutor_'.$i.'" id="hidIDAutor_'.$i.'">
                <div class="row" id="autor_'.$i.'">
                    <div class="col-lg-11">
                        <div class="form-group">
                            <input class="form-control" placeholder="Autor" id="txtAutor_'.$i.'" name="txtAutor_'.$i.'" required autofocus value="'.$autores[$i]->getNombre().'">
                        </div>
                    </div>
                    <div class="col-lg-1">
                        <div class="form-group">
                            <button id="button" onclick="eliminarAutor('.$i.');" class="btn btn-xs btn-primary" style="margin-left: -10px;margin-top: 4px;" type="button">X</button>
                        </div>
                    </div>
                </div>';
    }
    $data['autores'] = $tabla;
    $data['cantAut'] = $i;
    
    //Links
    $links = BidPublicacionLink::listadoLinks($ID);
    $tabla = "";
    for($i=0;$i<count($links);$i++)
    {
        $tabla.='<input type="hidden" value="'.$links[$i]->getID().'" name="hidIDPais_'.$i.'" id="hidIDPais_'.$i.'">
                <div class="row" id="link_'.$i.'">
                    <div class="col-lg-11">
                        <div class="form-group">
                            <input class="form-control" placeholder="Pais" id="txtPais_'.$i.'" name="txtPais_'.$i.'" required autofocus value="'.$links[$i]->getPais().'">
                        </div>
                    </div>
                    <div class="col-lg-1">
                        <div class="form-group">
                            <button id="button" onclick="eliminarLink('.$i.');" class="btn btn-xs btn-primary" style="margin-left: -10px;margin-top: 4px;" type="button">X</button>
                        </div>
                    </div>
                    <div class="col-lg-11">
                        <div class="form-group">
                            <input class="form-control" placeholder="Link" id="txtLink_'.$i.'" name="txtLink_'.$i.'" required autofocus value="'.$links[$i]->getLink().'">
                        </div>
                    </div>
                    <div class="col-lg-12"><hr></div>
                </div>';
    }
    $data['links'] = $tabla;
    $data['cantLin'] = $i;
}
echo JSon::encode($data);
?>