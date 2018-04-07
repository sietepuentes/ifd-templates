<?php

/**
 * Description of generador
 *
 * @author leao
 */

class GeneradorAbm {

    private static function obtenerColumnas($tabla) {
        $dbh = Database::getInstance();

        $stmt = $dbh->prepare("SHOW COLUMNS FROM " . $tabla);
        $stmt->execute();
        $columnas = $stmt->fetchAll();
        return $columnas;
    }

    public static function armarComboTablas(){
        $dbh = Database::getInstance();
        $dbName = Config::get('dbName');
        $stmt = $dbh->prepare("SHOW TABLES FROM " . $dbName);
        $stmt->execute();
        $tablas = $stmt->fetchAll();
        $combo = "<select id='cmbTabla'>";
        foreach($tablas as $tabla){
            $combo .= "<option value='".$tabla[0]."'>".$tabla[0]."</option>";
        }
        $combo .= "</select>";
        return $combo;

    }

    public static function armar($t) {

        $head = <<<EOF
        <table border='0'>
            <tr class="ui-widget-header">
            <td>campo</td>
            <td>incluir</td>
            <td>oculto</td>
            <td>editable</td>
            <td>busquedas</td>
       </tr>
EOF;
        $foot = "</table>";
        $body = '';

        $columnas = self::obtenerColumnas($t);
        $x = 0;
        foreach ($columnas as $col) {
           $c = $col[0];
           $body .= <<<EOF
           <tr>
           <td>$c <input type="hidden" name="hid_$x" id="hid_$x" value='$c' /></td>
              <td align='center'><input type='checkbox' name='chkIncluir_$x' id='chkIncluir_$x' checked /></td>
              <td align='center'><input type='checkbox' name='chkOculto_$x' id='chkOculto_$x' /></td>
              <td align='center'><input type='checkbox' name='chkEditable_$x' id='chkEditable_$x' /></td>
              <td align='center'><input type='checkbox' name='chkBuscar_$x' id='chkBuscar_$x' /></td>
           </tr>

EOF;
           $x++;
        }

        return $head . $body . $foot;
    }

    private static function asignarRequests($campos,$objeto){
        $str = "";
        $arrCampos = explode(',',$campos);
        $id = array_shift($arrCampos);
        foreach($arrCampos as $campo){
            $upCampo = ucfirst($campo);
            $str .= $objeto."->set$upCampo(Page::get('$upCampo'));".chr(13);
        }
        return $str;
    }

    private static function generarModelo($clase,$campos,$file){

        $objeto = "\$o$clase";
        $enter = chr(13);
        $req = '';
        $req = self::asignarRequests($campos, $objeto);


        $str = <<<EOF

<?php
Jota::incluir(   array  (   'clases' => array('$clase'),
                            'helpers' => array('PageBo','Page','JqGrid')
                        )
);
\$operacion = Page::getOpcional("oper", "");
\$page = Page::getOpcional("page", "");
\$rows = Page::getOpcional("rows", "");
\$searchField = Page::getOpcional("searchField", "");
\$searchOper = Page::getOpcional("searchOper", "");
\$searchString = Page::getOpcional("searchString", "");
\$strFilter  = "";
switch (\$operacion)
{
    case "add":
        $objeto = new $clase();
        $req
        $objeto\->guardar();

        break;
    case "edit":

        $objeto = new $clase(Page::get("id"));
        $req
        $objeto\->guardar();
        break;
    case "del":

        $objeto = new $clase(Page::get("id"));
        $objeto\->eliminar();

        break;
    default:
        echo JqGrid::armarJson($clase::obtenerListado(\$page, \$rows, 'id', 'DESC', \$searchField, \$searchOper, \$searchString, \$strFilter));
}
?>
       
EOF;
    $archivo = APP_PATH ."/mcc/modelos/bo.$file.mod.php";
    file_put_contents($archivo, stripslashes($str));

    }

    public static function generarArchivo($post){

        $combosFecha = $post['combosFecha'];
        $exportar = $post['exportar'];
        $clase = $post['clase'];
        $campos = $post['campos'];
        $grid = $post['grid'];
        $grid = stripslashes( $grid );
        $grid = str_replace("#E", chr(13), $grid);
        $file = $post['file'];

        self::generarModelo($clase,$campos,$file);

        $combos = '';
        $readyCombos = '';
        $fnFiltrarExportar = '';
        if ($combosFecha == 1){    
            $combos = "Desde<select id='cmbDiaD'></select><select id='cmbMesD'></select><select id='cmbAnioD'></select>";
            $combos .= "Hasta<select id='cmbDiaH'></select><select id='cmbMesH'></select><select id='cmbAnioH'></select>";
            $combos .= "<button id='btnFiltrar'>Filtrar</button>";
            $readyCombos = "Jota.forms.llenarCombosFecha('#cmbDiaD','#cmbMesD','#cmbAnioD');";
            $readyCombos .= "Jota.forms.llenarCombosFecha('#cmbDiaH','#cmbMesH','#cmbAnioH');";
            $readyCombos .= "$('#btnFiltrar').button().click(function(){filtrarExportar('filtrar');});";

            $fnFiltrarExportar .= <<<EOF
            function filtrarExportar(accion)
{
    var diaD = $('#cmbDiaD').val();
    var mesD = $('#cmbMesD').val();
    var anioD = $('#cmbAnioD').val();
    var diaH = $('#cmbDiaH').val();
    var mesH = $('#cmbMesH').val();
    var anioH = $('#cmbAnioH').val();
    var desde = anioD + mesD + diaD;
    var hasta = anioH + mesH + diaH;
    if(desde == "")  desde = "19000101";
    if(hasta == "")  hasta = "29000101";

    if(accion == 'filtrar'){

	$("#grid").setGridParam({url:"../mcc/modelos/bo.$file.mod.php?oper=filtrar&desde="+desde+"&hasta="+hasta,page:1}).trigger("reloadGrid");

    }else{
      location.href = "../mcc/modelos/bo.$file.mod.php?oper=export&desde="+desde+"&hasta="+hasta;

    }

}

EOF;

        }

        if ($exportar == 1){
            $exportar = "<button id='btnExportar'>Exportar</button>";
            $readyExportar = "$('#btnExportar').button().click(function(){filtrarExportar('exportar');});";
        }

        $html =<<<EOF

   <?php
    Jota::incluir(   array  (  'clases' => array('$clase'),
                             'helpers' => array('PageBo','Page','Html')
                            )
    );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>Backoffice</title>

<!-- css -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/dark-hive/jquery-ui.css" type="text/css" />
<link href="../../frm/rec/css/LoaderCss.php" rel="stylesheet" type="text/css" />

<!-- jquery -->
<script language="javascript" type="text/javascript" src="../../../js/jquery-1.4.2.js"></script>
<!-- js del core -->
<script type="text/javascript" src="../../frm/js/LoaderJs.php?inc=ABM"></script>

</head>
<body>
<div id="wrapper">
	<div id="container">
            <div id="header">
                <a href="menu.php" id="menu">Menu</a>
                <a href="login.php" class="fg-button ui-widget ui-state-default ui-corner-all">Log Out</a>
            </div>
            <div id="toolbar" class="ui-widget">
                $combos $exportar
            </div>
            <br style="clear:both" />
            <table id="grid"></table>
            <div id="gridpager"></div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){

  $('#menu').menu('menu.php');
  $readyCombos
  $readyExportar
EOF;

        $html .= $grid;

        $html .= <<<EOF

  $("#grid").jqGrid('navGrid','#gridpager',{search:false, edit:true, add:true, del:true});
});
$fnFiltrarExportar;
</script>
</body>
</html>

EOF;
        $archivo = APP_PATH ."/bo/$file.php";
        file_put_contents($archivo, $html);
        return $file;

    }
}
?>