<?php
    require_once '../../../frm/core/ini.php';
    Jota::incluir(   array  ('helpers' => array('PageBo','Page','Html','Json'),
                             'clases' => array('ChequeCuenta')
                            )
    );

//header('Content-Type: text/html; charset=utf8');
$ID = Page::getOpcional("id","");

$objeto = ChequeCuenta::obtener($ID);
$data=array();
if(!is_null($objeto))
{
    $data['txtNumero'] = $objeto->getNumero();
    $data['cmbCooperativa'] = $objeto->getIDCooperativa();
}
echo JSon::encode($data);
?>