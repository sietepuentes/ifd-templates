<?php
$arrayMeses = array('','Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

function NombrePaginaActual(){
	$pagName = explode('/', $_SERVER['SCRIPT_FILENAME']);
	$pagName = end($pagName);
	return $pagName;
}

function armarHtml($pagina)
{
	$textoHtml="";

	if (file_exists($pagina)) 
	{
		$fp = fopen($pagina,"r");
		while ($linea= fgets($fp,1024))
			$textoHtml .= $linea;
	}
	return $textoHtml;
}
function enviarEmail($subject, $emailDestino, $cc, $bcc, $from, $fromEmail, $body)
{
	$headers = "From: $from<$fromEmail>\n";
	$headers = "Reply-To: $fromEmail\n";
	$headers.= "X-Mailer: PHP\r\n";
	$headers.= "X-Priority: Normal\r\n";

	if($cc!="")
		$headers.= "cc: ".$cc."\n";
	if($bcc!="")
		$headers.= "bcc: ".$bcc."\n";
	$headers.= "MIME-Version: 1.0\r\n";
	$headers.= "Content-type: text/html; charset=iso-8859-1\r\n";

	@mail($emailDestino,$subject,$body, $headers);
}
function estilo($pagBoton)
{
	$pagName = NombrePaginaActual();
	$class = "class='current'";
	$pagBoton = explode(',', $pagBoton);
	foreach ($pagBoton as $key => $value)
	{
		if ($pagName == $value)
		{
			echo $class;
		}
	}
}
function formatoFecha($fechain, $separador)
{

	$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	
	if($separador != "mes"){
	$dia = date("j", strtotime($fechain));
	$mes = date("n", strtotime($fechain));
	$ano = date("Y", strtotime($fechain));
	
	}
		
	switch($separador)
	{
	case "de":
		$mes = $meses[(date("n", strtotime($fechain)))-1];
		return $dia . " de " . $mes . " de " . $ano;
		break;
        case "deEvento":
                //Lunes, 08 de noviembre de 2011
                $diaSem = dia_semana($fechain);
		$mes = $meses[(date("n", strtotime($fechain)))-1];
		return $diaSem . ",  " . $dia." de ". $mes . " de " . $ano;
		break;
	case "/":
		return $dia . "/" . $mes . "/" . $ano;
		break;
	case "-":
		return $dia . "-" . $mes . "-" . $ano;
		break;
	case "mes":
		return $meses[$fechain];
		break;
	}
	
}

function fix_slashes($arr = '')
{
        if(is_null($arr) || $arr == '') return null;
        if(!get_magic_quotes_gpc()) return $arr;
        return is_array($arr) ? array_map('fix_slashes', $arr) : stripslashes($arr);
}

function limpiarSql($unValor) {

 	if( get_magic_quotes_gpc() )
	{
		$unValor = stripslashes( $unValor );
	}
	$unValor = htmlspecialchars($unValor, ENT_QUOTES);
	return $unValor;

}

function requestString($valor){
	
	$valor = isset($_REQUEST[$valor]) ? $_REQUEST[$valor] : "";
	
	if( get_magic_quotes_gpc() )
	{
			 $valor = stripslashes( $valor );
	}
		
	return ( htmlspecialchars($valor) );
}

function requestNum($valor){
	
	$valor = isset($_REQUEST[$valor]) ? $_REQUEST[$valor] : 0;
	
	if(!is_numeric($valor))
	{
		exit;
		
	}else{

		if( get_magic_quotes_gpc() )
		{
			 $valor = stripslashes( $valor );
		}

		
		return ( htmlspecialchars($valor) );
	}
	
}

function redirect($url = null)
{
        if(is_null($url)) $url = $_SERVER['PHP_SELF'];
        header("Location: $url");
        exit();
}

function reemplazarExcel($dato)
{
    $dato = str_replace(array("\r", "\n"), '', $dato);
    return str_replace(";", " ", str_replace(",", " ", $dato));
}

function extension_archivo ($ruta) {
    $res = explode(".", $ruta);
    $extension = $res[count($res)-1];
    return $extension ;
}// fin extension_archivo


function is_fecha($fecha)
{
    $arrayAux = explode("-", $fecha);
    if(count($arrayAux)==3)
    {
        if((int)$arrayAux[0]>1900 && (int)$arrayAux[0]<=date('Y'))
            if((int)$arrayAux[1]>0 && (int)$arrayAux[1]<13)
                if((int)$arrayAux[2]>0 && (int)$arrayAux[2]<32)
                    return true;
    }
    return false;
}

function edad($fecha_nac){
    $dia=date("j");
    $mes=date("n");
    $anno=date("Y");
    $dia_nac=substr($fecha_nac, 8, 2);
    $mes_nac=substr($fecha_nac, 5, 2);
    $anno_nac=substr($fecha_nac, 0, 4);
    if($mes_nac>$mes){
        $calc_edad= $anno-$anno_nac-1;
    }else{
        if($mes==$mes_nac AND $dia_nac>$dia){
            $calc_edad= $anno-$anno_nac-1;
        }else{
            $calc_edad= $anno-$anno_nac;
        }
    }
    return $calc_edad;
}

function generarClave($cant=6)
{
    $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $cad = "";
    for($i=0;$i<$cant;$i++) {
        $cad .= substr($str,rand(0,62),1);
    }
    return $cad;
}

function dia_semana($fecha) {
    $aux = explode(" ",$fecha);

    $aux = explode("-",$aux[0]);
    $dia = $aux[2];
    $mes = $aux[1];
    $ano = $aux[0];
    $dias = array('Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado');
    return $dias[date("w", mktime(0, 0, 0, $mes, $dia, $ano))];
}

function filedata($path) {
		// Vaciamos la caché de lectura de disco
		clearstatcache();
		// Comprobamos si el fichero existe
		$data["exists"] = is_file($path);
		// Comprobamos si el fichero es escribible
		//$data["writable"] = is_writable($path);
		// Leemos los permisos del fichero
		//$data["chmod"] = ($data["exists"] ? substr(sprintf("%o", fileperms($path)), -4) : FALSE);
		// Extraemos la extensión, un sólo paso
		$data["ext"] = substr(strrchr($path, "."),1);
		// Primer paso de lectura de ruta
		//$data["path"] = array_shift(explode(".".$data["ext"],$path));
		// Primer paso de lectura de nombre
		//$data["name"] = array_pop(explode("/",$data["path"]));
		// Ajustamos nombre a FALSE si está vacio
		//$data["name"] = ($data["name"] ? $data["name"] : FALSE);
		// Ajustamos la ruta a FALSE si está vacia
		//$data["path"] = ($data["exists"] ? ($data["name"] ? realpath(array_shift(explode($data["name"],$data["path"]))) : realpath(array_shift(explode($data["ext"],$data["path"])))) : ($data["name"] ? array_shift(explode($data["name"],$data["path"])) : ($data["ext"] ? array_shift(explode($data["ext"],$data["path"])) : rtrim($data["path"],"/")))) ;
		// Ajustamos el nombre a FALSE si está vacio o a su valor en caso contrario
		//$data["filename"] = (($data["name"] OR $data["ext"]) ? $data["name"].($data["ext"] ? "." : "").$data["ext"] : FALSE);
		// Devolvemos los resultados
		return $data;
}

function limpiarTagP($texto)
{
    $aux = str_replace("<p>", "", $texto);
    $aux = str_replace("</p>", "<br><br>", $aux);
    return $aux;
}

function thumbvideo($tabvideo)
{
    //Primero me fijo en que lugar esta subido, por ahora youtube y vimeo
    //VIMEO
    $pos = strpos($tabvideo, "vimeo.com");
    if ($pos !== false) {
        //No hay forma de obtener la imagen
        return "images/icono-videos.png";
    }
    
    //YOUTUBE
    $pos = strpos($tabvideo, "youtube.com");
    if ($pos !== false) {
        //<iframe width="480" height="360" src="http://www.youtube.com/embed/zKsQafgEwyE" frameborder="0" allowfullscreen></iframe>
        $array1 = explode("youtube.com/embed/", $tabvideo);
        //el array me quedaria con 2 posiciones en una "<iframe width="480" height="360" src="http://www.youtube.com/embed/" y en otra zKsQafgEwyE" frameborder="0" allowfullscreen></iframe>
        if(count($array1)==2)
        {
            $array2 = explode('"', $array1[1]);
            if(count($array2)>0)
                return "http://img.youtube.com/vi/".$array2[0]."/1.jpg";
            else
                return "images/icono-videos.png";
        }
        else
            return "images/icono-videos.png";
    }
    
    
    
    return "images/icono-videos.png";
}

function botonesDosEstilos($texto)
{
    $array = explode(' ', $texto);
    if(count($array)>1)
    {
        $txt = $array[0]."<br><p class='segundaLinea'>";
        for($i=1;$i<count($array);$i++)
            $txt.=$array[$i]." ";
        $txt.="</p>";
        $aux["txt"] = $txt;
        $aux["cantLinea"] = 2;
    }
    else
    {
        $txt = $texto;
        $aux["txt"] = $txt;
        $aux["cantLinea"] = 1;
    }
    return $aux;
}

function ip_real() {
       if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
              $ip=$_SERVER['HTTP_CLIENT_IP'];
       } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
              $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
       }else{
              $ip=$_SERVER['REMOTE_ADDR'];
       }
return $ip;
}


function monthsDif($start, $end)
{
    // Assume YYYY-mm-dd - as is common MYSQL format
    $splitStart = explode('-', $start);
    $splitEnd = explode('-', $end);

    if (is_array($splitStart) && is_array($splitEnd)) {
        $startYear = $splitStart[0];
        $startMonth = $splitStart[1];
        $startDay =  $splitStart[2];
        $endYear = $splitEnd[0];
        $endMonth = $splitEnd[1];
        $endDay = $splitEnd[2];
        
        if($startDay>20)
            $startMonth=$startMonth+1;

        $difYears = $endYear - $startYear;
        $difMonth = $endMonth - $startMonth;

        if (0 == $difYears && 0 == $difMonth) { // month and year are same
            //return 0;
            return 1;
        }
        else if (0 == $difYears && $difMonth > 0) { // same year, dif months
            return ($difMonth+1);
        }
        else if (1 == $difYears) {
            $startToEnd = 13 - $startMonth; // months remaining in start year(13 to include final month
            return (($startToEnd + $endMonth)); // above + end month date
        }
        else if ($difYears > 1) {
            $startToEnd = 13 - $startMonth; // months remaining in start year 
            $yearsRemaing = $difYears - 2;  // minus the years of the start and the end year
            $remainingMonths = 12 * $yearsRemaing; // tally up remaining months
            $totalMonths = $startToEnd + $remainingMonths + $endMonth; // Monthsleft + full years in between + months of last year
            return $totalMonths+1;
        }
    }
    else {
        return false;
    }
}



?>