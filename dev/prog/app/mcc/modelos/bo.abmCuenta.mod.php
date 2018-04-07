<?php
require_once "../../../frm/core/ini.php";
Jota::incluir(   array  (   'clases' => array('ChequeCuenta'),
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

$IDCooperativa = Page::getOpcional("cooperativa", "");
$Buscar = Page::getOpcional("buscar", "");

$oObjeto = new ChequeCuenta();

switch ($operacion)
{
    case "add":
        $oObjeto->setNumero(Page::getOpcional("txtNumero", ""));
        $oObjeto->setIDCooperativa(Page::getOpcional("cmbCooperativa", ""));
        try
        {
            $oObjeto->agregar();
            if($oObjeto->getIDCuenta()>0)
                echo '{"tipo":"OK","mensaje": "' .utf8_encode('Se cargo correctamente.') .'"}';
            else
                echo '{"tipo":"ERROR_ALTA_USUARIO","mensaje": "Error, intente nuevamente."}';
        }
        catch( WebarException $ex)
        {
            echo '{"tipo":"ERROR_ALTA_USUARIO","mensaje": "'.$ex->getMessage().'"}';
        }

        break;
    case "edit":
        $oObjeto->setIDCuenta(Page::get("id"));
        $oObjeto->setNumero(Page::getOpcional("txtNumero", ""));
        $oObjeto->setIDCooperativa(Page::getOpcional("cmbCooperativa", ""));      
        try
        {
            $oObjeto->editar();
            if($oObjeto->getIDCuenta()>0)
                echo '{"tipo":"OK","mensaje": "' .utf8_encode('Se modifico correctamente.') .'"}';
            else
                echo '{"tipo":"ERROR_ALTA_USUARIO","mensaje": "Error, intente nuevamente."}';
        }
        catch( WebarException $ex)
        {
            echo '{"tipo":"ERROR_ALTA_USUARIO","mensaje": "'.$ex->getMessage().'"}';
        }

        break;
    case "imp": 
        $IDCuenta = Page::getOpcional("id", "");

        $fechaDesde = "1990-01-01";
        $fechaHasta = "2100-01-01";
        
        if($IDCuenta != "")
        {
            $oObjeto = ChequeCuenta::obtener($IDCuenta);
            if($oObjeto->getIDCuenta() >0)
            {
                require_once('../../../../html2pdf.class.php');
                $pdf = new HTML2PDF('L', 'A4', 'fr', true, 'UTF-8', 10);
                $pdf->pdf->SetDisplayMode('fullpage');
                ob_end_clean();
                
                $top = "<table style='width: 100%; height:100%; border: solid 0px #000000'>
                            <tr >
                                <td>
                                    <font style='font-size: 12px; font-weight: bold'>".date('d-m-Y')."</font>
                                </td>                            
                            </tr>
                            <tr>
                                <td style='width: 80%; text-align: left;'>
                                    <font style='font-size: 22px; font-weight: bold'>Historial Creditos/Adelantos de  ".$oObjeto->getApellido()." ".$oObjeto->getNombre()." </font><br>
                                </td>
                            </tr>
                        </table>
                        <br>";
                $pdf->writeHTML($top, isset($_GET['vuehtml']));
                
                $query="SELECT e.IDCuenta, e.Nombre, e.Apellido, c.MontoTotal, c.MontoCuota, c.CantidadCuota, DATE_FORMAT(c.FechaPrimeraCuota, '%d-%c-%Y') as FechaPrimeraCuota, DATE_FORMAT(c.FechaUltimaCuota, '%d-%c-%Y') as FechaUltimaCuota, co.Nombre as Comercio
                        FROM sueldo_creditos c
                            INNER JOIN sueldo_empleados e ON e.IDCuenta = c.IDCuenta
                            INNER JOIN sueldo_comercios co ON co.IDComercio = c.IDComercio
                        WHERE c.IDCuenta = '".$oObjeto->getIDCuenta()."' AND
                                ((c.FechaPrimeraCuota < '".$fechaHasta."' AND c.FechaUltimaCuota >= '".$fechaHasta."')
                                    OR
                                (c.FechaPrimeraCuota < '".$fechaHasta."' AND c.FechaUltimaCuota BETWEEN '".$fechaDesde."' AND '".$fechaHasta."'))
                        ORDER BY e.Apellido, e.Nombre"; 
                
                $r= PDOHelper::query($query);
                $i=0;
                $top="";
                foreach ($r as $arrRes) {
                    $IDCuenta = $arrRes ['IDCuenta'];
                    $Nombre = $arrRes ['Comercio'];       
                    $FechaPrimeraCuota = $arrRes ['FechaPrimeraCuota'];       
                    $FechaUltimaCuota = $arrRes ['FechaUltimaCuota'];       
                    $MontoTotal = $arrRes ['MontoTotal'];        
                    $MontoCuota = $arrRes ['MontoCuota'];        
                    $CantidadCuota = $arrRes ['CantidadCuota'];        

                    if(($i%30) == 0)
                    {   
                        if($i!=0)
                        {
                            $top.="</table>";
                            $pdf->writeHTML($top, isset($_GET['vuehtml']));
                            $pdf->pdf->AddPage();
                        }
                        $top= "<table style='width: 100%; height:100%;' border='1' cellpadding='0' cellspacing='0' bordercolor='#000000'>";
                        
                        $top.="<tr style='font-size: 17px; font-weight: bold; background-color:#ccc'>
                                <td style='width:270px;'>Razon Social</td>
                                <td style='width:140px; text-align:center'>Monto Total</td>
                                <td style='width:160px; text-align:center'>Monto de la Cuota</td>
                                <td style='width:120px; text-align:center'>Cant. Cuota</td>
                                <td style='width:120px; text-align:center'>1º Cuota</td>
                                <td style='width:120px; text-align:center'>Ult. Cuota</td>
                            </tr>";
                    }
                    
                    $top.="<tr >
                                <td style=' text-align: left;'><font style='font-size: 14px; font-weight: bold'>".$Nombre."</font></td>
                                <td align='right' style=''>$".$MontoTotal."</td>
                                <td align='right' style=''>$".$MontoCuota."</td>
                                <td align='right' style=''>".$CantidadCuota."</td>
                                <td align='right' style=''>".$FechaPrimeraCuota."</td>
                                <td align='right' style=''>".$FechaUltimaCuota."</td>
                            </tr>";
                    
                    $i++;
                }
                if($i>0)
                    $top.="</table><br><br>";
                $pdf->writeHTML($top, isset($_GET['vuehtml']));
                
                //ADELANTOS
                $query="SELECT e.IDCuenta, e.Nombre, e.Apellido, a.Monto, DATE_FORMAT(a.Fecha, '%d-%c-%Y') as Fecha
                        FROM sueldo_adelantos a
                            INNER JOIN sueldo_empleados e ON e.IDCuenta = a.IDCuenta
                        WHERE a.IDCuenta = '".$oObjeto->getIDCuenta()."'
                        ORDER BY a.Fecha"; 
                
                $r= PDOHelper::query($query);
                $i=0;
                $top="";
                foreach ($r as $arrRes) {
                    $IDCuenta = $arrRes ['IDCuenta'];
                    $Fecha = $arrRes ['Fecha'];           
                    $Monto = $arrRes ['Monto'];  
                    $Nombre = $arrRes["Apellido"]." ".$arrRes["Nombre"];

                    if(($i%30) == 0)
                    {   
                        if($i!=0)
                        {
                            $top.="</table>";
                            $pdf->writeHTML($top, isset($_GET['vuehtml']));
                            $pdf->pdf->AddPage();
                        }
                        $top= "<table style='width: 100%; height:100%;' border='1' cellpadding='0' cellspacing='0' bordercolor='#000000'>";
                        
                        $top.="<tr style='font-size: 17px; font-weight: bold; background-color:#ccc'>
                                <td style='width:270px;'>Adelantos</td>
                                <td style='width:140px; text-align:center'>Fecha</td>
                                <td style='width:160px; text-align:center'>Monto</td>
                            </tr>";
                    }
                    
                    $top.="<tr >
                                <td style=' text-align: left;'><font style='font-size: 14px; font-weight: bold'>".$Nombre."</font></td>
                                <td align='right' style=''>".$Fecha."</td>
                                <td align='right' style=''>$".$Monto."</td>
                            </tr>";
                    
                    $i++;
                }
                if($i>0)
                    $top.="</table>";
                $pdf->writeHTML($top, isset($_GET['vuehtml']));
                
                $pdf->output();
                exit;      
            }
        }
        break;
    case "del":
        $oObjeto->setIDCuenta(Page::get("id"));
        $oObjeto->eliminar();
        break;
    default:
    {
        $strFilter=" 1=1 ";

        if($IDCooperativa != "" && $IDCooperativa != "undefined")
            $strFilter.=" AND e.IDCooperativa = '".$IDCooperativa."' ";
        if($Buscar != "" && $Buscar != "undefined")
            $strFilter.=" AND (e.Numero like '%".$Buscar."%') ";        
        
        if($sidx=="" || $sidx=="id")
        {
            $sidx = "e.IDCuenta";
            $sord = "DESC";
        }
        echo JqGrid::armarJson(ChequeCuenta::obtenerListado($page, $rows, $sidx, $sord , $searchField, $searchOper, $searchString,$strFilter));
    }
}
?>