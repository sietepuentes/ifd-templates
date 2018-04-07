<?php

require_once 'File.hlp.php';

class Mail {
   public static function enviar($sSubject, $sEmailDestino, $sCC, $sBCC, $sRemitente, $sEmailOrigen, $sHtmlBody= null, $sRutaArchivo= null)
    {
        if(Data::vacio($sHtmlBody)&& Data::vacio($sRutaArchivo))
        {
            throw new ErrorException("Debes indicar el body html o la ruta del archivo.");
        }

        if(Data::vacio($sHtmlBody))
            $sHtmlBody= File::leer ($sRutaArchivo);

        $headers = "From: $sRemitente<$sEmailOrigen>\n";
        $headers.= "Reply-To: $sEmailOrigen\n";
        $headers.= "X-Mailer: PHP\r\n";
        $headers.= "X-Priority: Normal\r\n";

        if($sCC!="")
            $headers.= "cc: ".$sCC."\n";

        if($sBCC!="")
            $headers.= "bcc: ".$sBCC."\n";
        
        $headers.= "MIME-Version: 1.0\r\n";
        $headers.= "Content-type: text/html; charset=iso-8859-1\r\n";

        @mail($sEmailDestino,$sSubject,$sHtmlBody, $headers);
    }
}
?>
