<?php
    require_once '../../../frm/core/ini.php';
    Jota::incluir(   array  ('helpers' => array('PageBo','Page','Html','Json')
                            )
    );

$db = Database::getInstance();
$sql="select IDCuenta as ID, CONCAT(c.Numero, ' (', co.Nombre, ')') as Valor from cheque_cuentas c
                inner join sueldo_cooperativas co on co.IDCooperativa = c.IDCooperativa ORDER BY co.Nombre, c.Numero";

$db = Database::getInstance();
$consulta = $db->query($sql);
$consulta->setFetchMode(PDO::FETCH_ASSOC);
$r = $consulta->fetchAll();

$options = "{\"\": \"Seleccionar\"";

foreach ($r as $rs) {
        $options .= ",\"" . $rs['ID'] . "\": \"" . utf8_encode($rs['Valor']) . "\"";
}
$options .= "}";
echo $options;
?>