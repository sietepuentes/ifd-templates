<?php
    require_once '../../../frm/core/ini.php';
    Jota::incluir(   array  ('helpers' => array('DBHelper','PageBo','Page','Html','Json'),
                             'clases' => array('ProyectoArchivo', 'PublicacionArchivo','GestionConocimientoNotaVideo','GestionConocimientoNotaArchivo','EventoAcademicoForoArchivo')
                            )
    );

//header('Content-Type: text/html; charset=iso-8859-1');

$ID = Page::get("id");
$tabla = Page::get("t");

    if($tabla == "proyecto_archivos")
    {
        $eliminar = new ProyectoArchivo();
        $eliminar->setID($ID);
        $eliminar->eliminar();
        
        echo "1";
    }
    elseif($tabla == "publicacion_archivos")
    {
        $eliminar = new PublicacionArchivo();
        $eliminar->setID($ID);
        $eliminar->eliminar();
        
        echo "1";
    }
    elseif($tabla == "gestion_conocimiento_nota_videos")
    {
        
        $eliminar = new GestionConocimientoNotaVideo();
        $eliminar->setID($ID);
        $eliminar->eliminar();
        
        echo "1";
    }
    elseif($tabla == "gestion_conocimiento_nota_archivos")
    {
        $eliminar = new GestionConocimientoNotaArchivo();
        $eliminar->setID($ID);
        $eliminar->eliminar();
        
        echo "1";
    }
    elseif($tabla == "evento_academico_foro_archivos")
    {
        $eliminar = new EventoAcademicoForoArchivo();
        $eliminar->setID($ID);
        $eliminar->eliminar();
        
        echo "1";
    }
    
    	
        	
?>