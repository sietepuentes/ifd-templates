<?php
    require_once '../../../frm/core/ini.php';
    Jota::incluir(   array  ('helpers' => array('PageBo','Page','Html','Json'),
                             'clases' => array('BidNew', 'BidNewModuloInfo')
                            )
    );

//header('Content-Type: text/html; charset=utf8');
$ID = Page::getOpcional("id","");

$objeto = BidNew::obtenerId($ID);
$data=array();
if(!is_null($objeto))
{
    $data['txtTitulo'] = $objeto->getTitulo();
    
    $data['txtFacebook'] = $objeto->getFacebook();
    $data['txtTwitter'] = $objeto->getTwitter();
    $data['txtYoutube'] = $objeto->getYoutube();
    $data['txtInstagram'] = $objeto->getInstagram();
    $data['txtGoogle'] = $objeto->getGoogle();
    
    //Modulos
    $modulos = BidNewModuloInfo::listadoModulos($ID);
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
                    <div class="col-lg-11">
                        <div class="form-group">
                            <input class="form-control" placeholder="Titulo Nota" id="txtTituloNota_'.$i.'" name="txtTituloNota_'.$i.'" required autofocus value="'.$modulos[$i]->getTituloNota().'">
                        </div>
                    </div>
                    <div class="col-lg-11">
                        <div class="form-group">';
                    if($modulos[$i]->getImagen()!="")
                            $tabla.='<a href="newsletter/'.$modulos[$i]->getImagen().'" target="_blank"><img src="newsletter/'.$modulos[$i]->getImagen().'" width="200px"></a>&nbsp;&nbsp;&nbsp;';
                            
                    $tabla.='<div class="btn btn-success" style="width:70%"><input type="file" name="imagen_'.$i.'" id="imagen_'.$i.'"><em style="font-size: 10px;">(Tamaño ideal Ancho 238px y 312px de Alto)</em></Div>
                        </div>
                    </div>
                    <div class="col-lg-11">
                        <div class="form-group">
                            <input class="form-control" placeholder="Link" id="txtLink_'.$i.'" name="txtLink_'.$i.'" value="'.$modulos[$i]->getLink().'">
                        </div>
                    </div>
                    <div class="col-lg-12"><hr></div>
                </div>';
    }
    $data['info'] = $tabla;
    $data['cantInf'] = $i;
    
}
echo JSon::encode($data);
?>