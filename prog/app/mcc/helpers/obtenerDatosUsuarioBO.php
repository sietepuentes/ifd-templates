<?php
    require_once '../../../frm/core/ini.php';
    Jota::incluir(   array  ('helpers' => array('PageBo','Page','Html','Json'),
                             'clases' => array('BidUsuarioBo')
                            )
    );

//header('Content-Type: text/html; charset=utf8');
$ID = Page::getOpcional("id","");

$usuario = BidUsuarioBo::obtenerId($ID);
$data=array();
if(!is_null($usuario))
{
    $data['txtNombre'] = $usuario->getNombre();
    $data['txtApellido'] = $usuario->getApellido();
    $data['txtUsuario'] = $usuario->getUsuario();
    $data['txtPassword'] = $usuario->getClave();
    $data['txtEmail'] = $usuario->getEmail();
}
echo JSon::encode($data);
?>