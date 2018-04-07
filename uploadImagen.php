<?php
require_once "prog/frm/core/ini.php";
Jota::incluir(array('clases' => array('Entidad'),
    'helpers' => array('Listado', 'DBHelper', 'Upload')
        )
);

//GUARDO LA IMAGEN
$handle = new upload($_FILES["filedata"]);
if ($handle->uploaded)
{
    $nombreArchivoSinExt = strtotime("now");
    $handle->file_new_name_body = $nombreArchivoSinExt;
    //$handle->jpeg_quality = 80;
    $handle->file_overwrite = true;
    $handle->Process(Config::PATH_IMAGENES_FRONT);
    if ($handle->processed)
    {
        $nombre = $nombreArchivoSinExt.".".$handle->file_src_name_ext;
        $nombre = str_replace(array(' ', '-'), array('_','_'), $nombre) ;
        $handle->clean();
        echo '{"ok":"'.$nombre.'"}';
        exit;
    }
}

