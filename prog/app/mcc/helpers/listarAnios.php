<?php
    require_once '../../../frm/core/ini.php';
    Jota::incluir(   array  ('helpers' => array('PageBo','Page','Html','Json')
                            )
    );

$options = "{\"\": \"Seleccionar\"";

for($i=2014;$i<(date('Y')+4);$i++)
{
        $options .= ",\"" . $i . "\": \"" . utf8_encode($i) . "\"";
}
$options .= "}";
echo $options;
?>