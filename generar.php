<?php
    require_once "prog/frm/core/ini.php";
    Jota::incluir(   array  ('helpers' => array('PageBo','Page','Html','Json'),
                             'clases' => array('BidPublicacion', 'BidPublicacionAutor', 'BidPublicacionLink', 'BidNewsletter', 'BidNewsletterModuloInfo', 'BidNew', 'BidNewModuloInfo')
                            )
    );

$ID = Page::getOpcional("id","");
$tipo = Page::getOpcional("tipo","");
$oper = Page::getOpcional("oper","");

if($tipo == "news")
{
    $objeto = BidNewsletter::obtenerId($ID);
    if(!is_null($objeto))
    {
        $textoAzul="";
        if($objeto->getTextoBarraAzul1() != "")
            $textoAzul.= '<strong>'.$objeto->getTextoBarraAzul1().'</strong>';
        if($objeto->getTextoBarraAzul2() != "")
        {
            if($textoAzul!="")
                $textoAzul.=" · ";
            $textoAzul.= $objeto->getTextoBarraAzul2();
        }
        if($objeto->getTextoBarraAzul3() != "")
        {
            if($textoAzul!="")
                $textoAzul.=" · ";
            $textoAzul.= $objeto->getTextoBarraAzul3();
        }
        
        $imagen="";
        if($objeto->getImagen() != "")
        {
            $imagen='<div class="movableContent">
                        <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                          <tr>
                            <td>
                              <div class="contentEditableContainer contentImageEditable">
                                <div class="contentEditable">
                                  <img src="http://ifd-templates.org/'.Config::PATH_IMAGENES_FRONT.$objeto->getImagen().'" alt="'.$objeto->getTitulo().'" data-default="placeholder" data-max-width="600" width="600" height="162" >
                                </div>
                              </div>
                            </td>
                          </tr>
                        </table>
                      </div>';
        }
        
        
        $html='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
          <title>BID</title>
          <style type="text/css">
          body {
           padding-top: 0 !important;
           padding-bottom: 0 !important;
           padding-top: 0 !important;
           padding-bottom: 0 !important;
           margin:0 !important;
           width: 100% !important;
           -webkit-text-size-adjust: 100% !important;
           -ms-text-size-adjust: 100% !important;
           -webkit-font-smoothing: antialiased !important;
              font-family: "arial", sans-serif;
         }
         .tableContent img {
           border: 0 !important;
           display: block !important;
           outline: none !important;
         }

        p, h2{
          margin:0;
        }

        div,p,ul,h2,h2{
          margin:0;
        }

        h2.bigger,h2.bigger{
          font-size: 32px;
          font-weight: normal;
        }

        h2.big,h2.big{
          font-size: 21px;
          font-weight: normal;
        }

        a.link1{
          color:#69C374;font-size:13px;font-weight:bold;text-decoration:none;
        }

        a.link2{
          padding:8px;background:#69C374;font-size:13px;color:#ffffff;text-decoration:none;font-weight:bold;
        }

        a.link3{
          background:#69C374; color:#ffffff; padding:8px 10px;text-decoration:none;font-size:13px;
        }
        .bgBody{
        background: #F6F6F6;
        }
        .bgItem{
        background: #ffffff;
        }
        </style>
        <script type="colorScheme" class="swatch active">
          {
            "name":"Default",
            "bgBody":"F6F6F6",
            "link":"69C374",
            "color":"999999",
            "bgItem":"ffffff",
            "title":"555555"
          }
        </script>

        </head>
        <body paddingwidth="0" paddingheight="0"   style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" offset="0" toppadding="0" leftpadding="0">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableContent bgBody" align="center"  style="font-family:Arial, Helvetica, sans-serif">

            <!--  =========================== The header ===========================  -->      

            <tr>
              <td height="25" bgcolor="#50748b" colspan="3"></td>
            </tr>

            <tr>
              <td height="130" bgcolor="#50748b">&nbsp;</td>
              <td rowspan="2" width="600" valign="top">
                <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" >

                  <!--  =========================== The body ===========================  -->   


                  <tr>
                    <td class="movableContentContainer">

                      <div class="movableContent">
                        <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                          <tr>
                            <td bgcolor="#50748b" valign="top">
                              <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                                <tr>
                                  <td align="left" valign="middle" >
                                    <div class="contentEditableContainer contentImageEditabl">
                                      <div class="contentEditable" >
                                        <img src="http://ifd-templates.org/invitacion2/images/logo.png" alt="Compagnie logo" data-default="placeholder" data-max-width="300" width="118" height="48">
                                      </div>
                                    </div>
                                  </td>

                                  <td align="right" valign="top" >
                                    <div class="contentEditableContainer contentTextEditable" style="display:inline-block;margin-top:20px;">
                                      <div class="contentEditable" >
                                        <a target="_blank" href="http://ifd-templates.org/generar.php?id='.$ID.'&tipo=news&oper=HTML" style="color:#A8B0B6;font-size:13px;text-decoration:none;">Open in your browser</a>
                                      </div>
                                    </div>
                                  </td>
                                  <td width="18" align="right" valign="top">
                                    <div class="contentEditableContainer contentImageEditable" style="display:inline-block;margin-top:20px;">
                                      <div class="contentEditable" >
                                        <a target="_blank" href="http://ifd-templates.org/generar.php?id='.$ID.'&tipo=news&oper=HTML"><img src="http://ifd-templates.org/invitacion2/images/openBrowser.png" alt="open in browser image" data-default="placeholder" width="15" height="15" style="padding-left:10px;"></a>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </div>

                      <div class="movableContent">
                        <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                          <tr><td height="25" bgcolor="#50748b"></td></tr>
                            <tr><td height="20" class="bgItem"></td></tr>
                            <tr>
                                <td>
                                <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top" class="bgItem">
                                <tr><td colspan="3" height="0" ></td></tr>
                                <tr>

                                 <td  width="80"></td>
                                    <td  align="center" width="440">
                                        <div class="contentEditableContainer contentTextEditable">
                                            <div class="contentEditable" style="font-size:24.51pt;color:#51748A;font-weight:normal;">
                                                <p style="font-size:24.51pt;">'.$objeto->getTitulo().'</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td  width="80"></td>
                                </tr>

                              <tr><td colspan="3" height="5" ></td></tr>

                                <tr>
                                    <td width="80"></td>
                                    <td  align="center" width="440">
                                      <div >
                                        <div style="font-size:12pt;color:#7B6C64;line-height:15pt;">
                                          <p>'.$objeto->getBajada().'</p>
                                        </div>
                                      </div>
                                    </td>
                                    <td  width="80"></td>
                               </tr>

                               <tr><td colspan="3" height="20" ></td></tr>
                            </table>
                          </td>
                        </tr>
                        </table>
                      </div>

                        <div class="movableContent">
                        <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                          <tr><td height="18" bgcolor="#2B8DC8"></td></tr>
                            <tr>
                              <td>
                                <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top" bgcolor="2B8DC8">
                                  <tr>
                                    <td width="50"></td>
                                    <td width="475" valign="middle">
                                      <div class="contentEditableContainer contentTextEditable">
                                        <div class="contentEditable" style="font-size:13.5pt;line-height:20pt;">
                                          <p style="color:#FFF; text-align:center">'.$textoAzul.'</p>
                                        </div>
                                      </div>
                                    </td>        
                                    <td width="50"></td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                            <tr><td height="10" bgcolor="#2B8DC8"></td></tr>
                        </table>
                      </div>

                           '.$imagen;
        if($objeto->getColumna()!="1")
        {
            $html.='<div class="movableContent">
                              <table width="600"  border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                                <tr><td height="20">&nbsp;</td></tr>
                              </table>
                            </div>';
        }
        else
        {
            $html.='<div class="movableContent">
                              <table width="600" bgcolor="#F6F6F6" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                                <tr><td height="20"> </td></tr>
                              </table>
                            </div>';
        }
                            
        //Modulos
        
        $infoArray = BidNewsletterModuloInfo::listadoModulos($ID);
        $link="";
        for($i=0;$i<count($infoArray);$i++)
        {
            if($infoArray[$i]->getColor()!="" && $infoArray[$i]->getTextoBoton())
            {
                $colorBoton = $infoArray[$i]->getColor();
                $textoBoton = $infoArray[$i]->getTextoBoton();
                $url="#";
                $cursor="cursor: context-menu";
                if($infoArray[$i]->getLink() != "")
                {
                    $cursor = '';
                    $url = $infoArray[$i]->getLink();
                }
                
                
                if($objeto->getColumna()!="1")
                {
                    $link="<tr height='33'>
                                <td width='20'></td>
                                <td ></td>
                                <td bgcolor='".$colorBoton."' width='90' align='center'><a href='".$url."' style='color:#FFF;text-aling:center; font-size:18pt; font-weight:bold; text-decoration:none; ".$cursor."'>".$textoBoton."</a></td>
                                <td width='20'></td>
                            </tr>";
                }
                else
                {
                    $link="<tr><td>&nbsp;</td></tr><tr height='33'>
                                <td width='20'>&nbsp;</td>
                                <td width='200'>&nbsp;</td>
                                <td bgcolor='".$colorBoton."' width='95' align='center'><a href='".$url."' style='color:#FFF;text-aling:center; font-size:18pt; font-weight:bold; text-decoration:none; ".$cursor."'>".$textoBoton."</a></td>
                                <td width='20'></td>
                            </tr>";
                }
            }
            
            if($objeto->getColumna()=="1")
            {
                $posCol = "right";
                if(($i%2) == 0)
                {
                    $posCol = "left";
                    if($i>0)
                    {
                        $html.='</div>
                                    <!--separador-->
                                    <div class="movableContent">
                                       <table width="600" bgcolor="#F6F6F6"  border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                                           <tr><td height="20">&nbsp;</td></tr>
                                       </table>
                                    </div>';
                    }
                    $html.='<div class="movableContent">';
                }   
                $html.='<table width="290" border="0" cellspacing="0" height="200px" cellpadding="0" align="'.$posCol.'" valign="top">                  
                        <tr>
                              <td bgcolor="#ffffff" style="padding:8px 0;">
                                <table width="290" height="180px" border="0" cellspacing="0" cellpadding="0" align="'.$posCol.'" valign="top">
                                  <tr>
                                    <td width="18"></td>
                                    <td align="left" colspan=2 valign="top" width="370">
                                      <div class="contentEditableContainer contentTextEditable">
                                        <div class="contentEditable" style="color:#124567;font-size:13pt;line-height:19px; font-weight:bold;margin-top:15px;">
                                          <p>'.$infoArray[$i]->getTitulo().'</p>                                       
                                        </div>
                                          <div class="contentEditable" style="color:#7B6C64;font-size:12pt;line-height:19px;margin-top:10px;margin-right:30px;">
                                            <p>'.nl2br($infoArray[$i]->getDescripcion()).'</p>
                                          </div>
                                      </div>
                                    </td>
                                    <!--<td width="20"></td>-->
                                  </tr>
                                '.$link.'   
                                </table>
                              </td>
                            </tr>            
                        <tr><td height="10" bgcolor="#FFF"></td></tr>
                    </table>';

            }
            else
            {
                $html.='<!-- contenedor de info '.$i.' -->
                        <div class="movableContent">
                        <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">                  
                            <tr>
                                  <td bgcolor="#ffffff" style="padding:8px 0;">
                                    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                                      <tr>
                                        <td width="18"></td>
                                        <td align="left" valign="top" width="370">
                                          <div class="contentEditableContainer contentTextEditable">
                                            <div class="contentEditable" style="color:#124567;font-size:13pt;line-height:19px; font-weight:bold;margin-top:15px;">
                                              <p>'.$infoArray[$i]->getTitulo().'</p>                                       
                                            </div>
                                              <div class="contentEditable" style="color:#7B6C64;font-size:12pt;line-height:19px;margin-top:10px;margin-right:30px;">
                                                <p>'.nl2br($infoArray[$i]->getDescripcion()).'</p>
                                              </div>
                                          </div>
                                        </td>
                                        <td width="20"></td>
                                      </tr>
                                    '.$link.'   
                                    </table>
                                  </td>
                                </tr>            
                            <tr><td height="10" bgcolor="#FFF"></td></tr>
                        </table>
                      </div>
                        <!--separador-->
                         <div class="movableContent">
                            <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                                <tr><td height="20"> </td></tr>
                            </table>
                         </div>
                        <!--fin contenedor '.$i.'-->';
            }
            
            if($objeto->getColumna()=="1" && $i>0)
            {
                $html.='</div>';
            }
        }
        
        if($objeto->getColumna()=="1"){
            $html.='<div class="movableContent">
                                    <table width="600" bgcolor="#F6F6F6"  border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                                        <tr><td height="20">&nbsp;</td></tr>
                                    </table>
                                </div>';
        }

        $html.='        
                         <!--  =========================== The footer ===========================  -->
                          <div class="movableContent">
                        <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">                  
                            <tr>
                                  <td style="padding:8px 0;">
                                    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                                        <tr>
                                        <td width="18"></td>
                                        <td align="center" valign="top" width="370">
                                            <table  align="center">
                                                <tr>';
                                    if($objeto->getFacebook()!="") $html.='<td><a href="'.$objeto->getFacebook().'" target="_blank" style="padding-left:5px;"><img src="http://ifd-templates.org/img/e_facebook.png"></a></td>';
                                    if($objeto->getTwitter()!="") $html.='<td><a href="'.$objeto->getTwitter().'" target="_blank" style="padding-left:5px;"><img src="http://ifd-templates.org/img/e_twitter.png"></a></td>';
                                    if($objeto->getYoutube()!="") $html.='<td><a href="'.$objeto->getYoutube().'" target="_blank" style="padding-left:5px;"><img src="http://ifd-templates.org/img/e_youtube.png"></a></td>';
                                    if($objeto->getInstagram()!="") $html.='<td><a href="'.$objeto->getInstagram().'" target="_blank" style="padding-left:5px;"><img src="http://ifd-templates.org/img/e_instagram.png"></a></td>';
                                    if($objeto->getGoogle()!="") $html.='<td><a href="'.$objeto->getGoogle().'" target="_blank" style="padding-left:5px;"><img src="http://ifd-templates.org/img/e_google.png"></a></td>';
                                                $html.='</tr>
                                            </table>
                                          </td>
                                        </tr>
                                    </table>
                                  </td>
                            </tr>
                            <tr>
                                  <td style="padding:8px 0;">
                                    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">';
        if($objeto->getDescripcionFinal()!="")
        {
            $html.='                 <tr>
                                        <td width="18"></td>
                                        <td align="center" valign="top" width="370">
                                              <div class="contentEditable" style="color:#50748b;font-size:12pt;line-height:19px;margin-top:0px;margin-right:30px;margin-left:30px;">
                                                <p>'.nl2br($objeto->getDescripcionFinal()).'</p>
                                              </div>
                                        </td>
                                        <td width="20"></td>
                                      </tr>';
        }
        if($objeto->getComentarioFinal()!="")
        {
            $html.='                 <tr>
                                        <td width="18"></td>
                                        <td align="center" valign="top" width="370">
                                              <div class="contentEditable" style="color:#dd0d27;font-size:12pt;font-weight:bold;line-height:19px;margin-top:15px;margin-right:30px;margin-left:30px;">
                                                <p>'.nl2br($objeto->getComentarioFinal()).'</p>
                                              </div>
                                        </td>
                                        <td width="20"></td>
                                      </tr>';
        }
        $html.='
                                    </table>
                                  </td>
                                </tr>            

                        </table>
                      </div>
                        <!--separador-->
                         <div class="movableContent">
                            <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                                <tr><td height="20"> </td></tr>
                            </table>
                         </div>


                      <!--  =========================== The footer ===========================  -->
              </td>
            </tr>   



              <tr><td height="28">&nbsp;</td></tr>

            </table>
          </td>
          <td height="130" bgcolor="#50748b">&nbsp;</td>
        </tr>

        <tr>
          <td class="bgBody">  &nbsp;</td>
          <td class="bgBody">  &nbsp;</td>
        </tr>




        </table>

          </body>
        </html>';
    }
}
elseif($tipo == "pub")
{
    $objeto = BidPublicacion::obtenerId($ID);
    if(!is_null($objeto))
    {
        $color = $objeto->getColor();
        if($color == "")
            $color="#65b0df";
        
        $paises="";
        //<p style="font-size:10pt;font-family:Arial,Tahoma,sans-serif; font-weight:bold;">PORTUGUESE: <a style="color:#FFF;font-size:9pt;font-weight:normal;" href="#">http://publications.iadb.org/handle/11319/6654?locale-attribute=pt&locale-attribute=es&</a></p>
        $autores="";
        //<p style="margin:0px;font-size:11pt;color:#000;font-family:Arial,Tahoma,sans-serif; font-weight:bold;line-height:14pt;">Fabiano Bastos</p>

        //Autores
        $autoresArray = BidPublicacionAutor::listadoAutores($ID);
        for($i=0;$i<count($autoresArray);$i++)
            $autores.='<p style="margin:0px;font-size:11pt;color:#000;font-family:Arial,Tahoma,sans-serif; font-weight:bold;line-height:14pt;">'.$autoresArray[$i]->getNombre().'</p>';

        $links = BidPublicacionLink::listadoLinks($ID);
        for($i=0;$i<count($links);$i++)
            $paises.='<p style="font-size:10pt;font-family:Arial,Tahoma,sans-serif; font-weight:bold;">'.$links[$i]->getPais().' <a style="color:#FFF;font-size:9pt;font-weight:normal;" href="'.$links[$i]->getLink().'">'.$links[$i]->getLink().'</a></p>';

        $html = '
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>BID</title>
        </head>

        <body bgcolor="f4f3f5" style="width:100%;margin:0;padding:0;background:#f4f3f5;">
          <!-- main table wrapper -->
          <table border="0" cellspacing="0" cellpadding="0" width="100%" style="background:#f4f3f5" bgcolor="#f4f3f5">
                  <tbody>
                          <tr>
                                  <td>
                                        <!-- top head links -->


                                    <!-- logo and default heading -->
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" height="60">
                                      <tbody>
                                        <tr style="background:#FFF;">
                                          <td width="430" style="padding-left:30px;">
                                            <p style="font-size:14pt;color:#000;font-family:Arial,Tahoma,sans-serif; font-weight:bold;">NEW PUBLICATION IFD</p>
                                          </td>
                                          <td width="150" align="right" style="padding-right:30px;">
                                  <a href="#" target="_blank"><img src="http://ifd-templates.org/publicaciones/images/logo.jpg" alt="BID" /></a>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>

                          <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" height="387">
                                      <tbody>
                                        <tr style="background:#e2e2e4;">
                                          <td rowspan="3" width="250" style="padding-left:30px;">
                                            <img width="238" height="312" src="http://ifd-templates.org/'.  Config::PATH_IMAGENES_FRONT.$objeto->getImagen().'">
                                          </td>
                                          <td width="350" VALIGN=TOP style="padding-right:40px;padding-left:30px;padding-top:15px;">
                                  <p style="font-size:14pt;color:#000;font-family:Arial,Tahoma,sans-serif; font-weight:bold;">'.$objeto->getTitulo().'</p>
                                  <p style="font-size:13pt;color:#000;font-family:Arial,Tahoma,sans-serif; font-weight:normal;margin-top:20px;line-height:17pt;">'.nl2br($objeto->getBajada()).'</p>
                              </td>
                                </tr>
                              <tr style="background:#e2e2e4;">
                                  <td width="350" VALIGN=BOTTOM style="padding-right:40px;padding-left:30px;padding-top:15px;">
                                  '.$autores.'
                                  <!--<p style="margin:0;font-size:11pt;color:#000;font-family:Arial,Tahoma,sans-serif; font-weight:bold;line-height:14pt;">Rogério Boueri</p>
                                  <p style="margin:0px;font-size:11pt;color:#000;font-family:Arial,Tahoma,sans-serif; font-weight:bold;line-height:14pt;">Maria Cristina Mac Dowell</p>
                                  <p style="margin:0px;font-size:11pt;color:#000;font-family:Arial,Tahoma,sans-serif; font-weight:bold;line-height:14pt;">Emilio Pineda</p>
                                  <p style="margin:0px;font-size:11pt;color:#000;font-family:Arial,Tahoma,sans-serif; font-weight:bold;line-height:14pt;">Fabiano Bastos</p>-->
                                  </td>                       
                                        </tr>
                              <tr style="background:#e2e2e4; height:37px;"><td></td></tr>
                                      </tbody>
                                    </table>
                          <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" height="15">
                                      <tbody>
                                        <tr style="background:#FFF;">
                                          <td width="30" style="background:#FFF;"></td>
                              <td width="150" style="background:'.$color.';"></td>
                              <td width="420" style="background:#FFF;"></td>
                            </tr>
                              </tbody>
                                    </table>

                              <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
                                      <tbody>
                            <tr style="background:#FFF;">  
                                          <td width="100%" VALIGN=TOP style="padding-right:30px;padding-left:30px;padding-top:5px;">
                                  <p style="font-size:10pt;color:#3c3c3b;font-family:Arial,Tahoma,sans-serif; font-weight:normal;">'.nl2br($objeto->getDescripcion()).'</p>
                                          </td>
                                        </tr>
                              <tr style="height:30px; background:#FFF;"><td></td></tr>
                                      </tbody>
                                    </table>
                          <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" height="15">
                                      <tbody>
                                        <tr style="background:'.$color.';">
                                          <td width="30" style="background:'.$color.';"></td>
                              <td width="150" style="background:#FFF;"></td>
                              <td width="420" style="background:'.$color.';"></td>
                            </tr>
                                 </tbody>
                                    </table>

                              <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
                                      <tbody>
                            <tr style="background:'.$color.';">  
                                          <td width="62%" VALIGN=TOP style="padding-right:30px;padding-left:30px;padding-top:5px;color:#FFF">
                                          '.$paises.'
                                  <!--<p style="font-size:10pt;font-family:Arial,Tahoma,sans-serif; font-weight:bold;">PORTUGUESE: <a style="color:#FFF;font-size:9pt;font-weight:normal;" href="#">http://publications.iadb.org/handle/11319/6654?locale-attribute=pt&locale-attribute=es&</a></p>
                                  <p style="font-size:10pt;font-family:Arial,Tahoma,sans-serif; font-weight:bold;">SPANISH: <a style="color:#FFF;font-size:9pt;font-weight:normal;" href="#">http://publications.iadb.org/handle/11319/6654?locale-attribute=es&locale-attribute=en&locale-attribute=pt&locale-attribute=es&</a></p>
                                  <p style="font-size:10pt;font-family:Arial,Tahoma,sans-serif; font-weight:bold;">ENGLISH: <a style="color:#FFF;font-size:9pt;font-weight:normal;" href="#">http://publications.iadb.org/handle/11319/6654?locale-attribute=en&locale-attribute=pt&locale-attribute=es&</a></p>
                                  <p style="font-size:10pt;font-family:Arial,Tahoma,sans-serif; font-weight:bold;">BRIK: <a style="color:#FFF;font-size:9pt;font-weight:normal;" href="#">http://brik.iadb.org/handle/iadb/86554</a></p>-->
                                    </td>
                                    <td width="38%" align="right" VALIGN=TOP style="padding-right:30px;padding-left:5px;padding-top:18px;color:#FFF">';
                                    
                                    if($objeto->getFacebook()!="") $html.='<a href="'.$objeto->getFacebook().'" target="_blank" style="padding-left:5px;"><img src="http://ifd-templates.org/img/facebook.png"></a>';
                                    if($objeto->getTwitter()!="") $html.='<a href="'.$objeto->getTwitter().'" target="_blank" style="padding-left:5px;"><img src="http://ifd-templates.org/img/twitter.png"></a>';
                                    if($objeto->getYoutube()!="") $html.='<a href="'.$objeto->getYoutube().'" target="_blank" style="padding-left:5px;"><img src="http://ifd-templates.org/img/youtube.png"></a>';
                                    if($objeto->getInstagram()!="") $html.='<a href="'.$objeto->getInstagram().'" target="_blank" style="padding-left:5px;"><img src="http://ifd-templates.org/img/instagram.png"></a>';
                                    if($objeto->getGoogle()!="") $html.='<a href="'.$objeto->getGoogle().'" target="_blank" style="padding-left:5px;"><img src="http://ifd-templates.org/img/google.png"></a>';

                                    
                        $html.='    </td>
                        </tr>
                               <tr style="height:10px; background:#3c3c3b;margin-top:20px;"><td colspan=2></td></tr>
                                      </tbody>
                                    </table>

                                    <!-- newsletter content area -->


                                    <!-- main footer credits -->
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
                                      <tbody>
                                        <tr style="height:10px; background:#3c3c3b;"><td></td></tr>
                                      </tbody>
                                    </table>
                                  </td>
                          </tr>
                  </tbody>
          </table>
        </body>
        </html>';   
    }
}
elseif($tipo == "ifdnew")
{
    $objeto = BidNew::obtenerId($ID);
    if(!is_null($objeto))
    {
        $html='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>BID</title>
        <style type="text/css">
            body {
                padding-top: 0 !important;
                padding-bottom: 0 !important;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
                margin:0 !important;
                width: 100% !important;
                -webkit-text-size-adjust: 100% !important;
                -ms-text-size-adjust: 100% !important;
                -webkit-font-smoothing: antialiased !important;
                font-family: "arial", sans-serif;
            }
            .tableContent img {
                border: 0 !important;
                display: block !important;
                outline: none !important;
            }

            p, h2{
                margin:0;
            }

            div,p,ul,h2,h2{
                margin:0;
            }

            h2.bigger,h2.bigger{
                font-size: 32px;
                font-weight: normal;
            }

            h2.big,h2.big{
                font-size: 21px;
                font-weight: normal;
            }

            a.link1{
                color:#69C374;font-size:13px;font-weight:bold;text-decoration:none;
            }

            a.link2{
                padding:8px;background:#69C374;font-size:13px;color:#ffffff;text-decoration:none;font-weight:bold;
            }

            a.link3{
                background:#69C374; color:#ffffff; padding:8px 10px;text-decoration:none;font-size:13px;
            }
            .bgBody{
                background: #F6F6F6;
            }
            .bgItem{
                background: #ffffff;
            }
        </style>
        <script type="colorScheme" class="swatch active">
            {
            "name":"Default",
            "bgBody":"F6F6F6",
            "link":"69C374",
            "color":"999999",
            "bgItem":"ffffff",
            "title":"555555"
            }
        </script>

    </head>
    <body paddingwidth="0" paddingheight="0"   style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" offset="0" toppadding="0" leftpadding="0">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableContent bgBody" align="center"  style="font-family:Arial, Helvetica, sans-serif">

            <!--  =========================== The header ===========================  -->      

            <tr>
                <td height="130" bgcolor="#f5f5f5">&nbsp;</td>
                <td rowspan="2" width="600" valign="top">
                    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" >

                        <!--  =========================== The body ===========================  -->   


                        <tr>
                            <td class="movableContentContainer">

                                <div class="movableContent">
                                    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                                        <tr>
                                            <td bgcolor="#f5f5f5" valign="top">
                                                <table width="650" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                                                    <tr>
                                                        <td align="left" valign="middle" >
                                                            <div class="contentEditableContainer contentImageEditabl">
                                                              <div class="contentEditable" style="padding:0px">
                                                                <img src="http://ifd-templates.org/img/pixel.png">
                                                              </div>
                                                            </div>
                                                        </td>

                                                        <td align="right" valign="top" style= padding-right: 10px;"text-align: right;">
                                                            <div class="contentEditableContainer contentTextEditable" style="display:inline-block;margin-top:20px;padding:0px">
                                                                <div class="contentEditable" style="padding:0px">
                                                                    <a target="_blank" href="http://ifd-templates.org/generar.php?id='.$objeto->getIDNew().'&tipo=ifdnew&oper=HTML" style="color:#827e7b;font-size:14px;">Open in your browser</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td width="18" align="right" valign="top">
                                                            <div class="contentEditableContainer contentImageEditable" style="display:inline-block;margin-top:20px;padding:0px">
                                                                <div class="contentEditable" style="padding:0px">
                                                                    <a target="_blank" href="http://ifd-templates.org/generar.php?id='.$objeto->getIDNew().'&tipo=ifdnew&oper=HTML"><img src="http://ifd-templates.org/img/browserifdnew.png" alt="open in browser image" data-default="placeholder" width="15" height="15" style="padding-left:10px;"></a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="movableContent">
                                    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                                        <tr><td height="12" bgcolor="#f5f5f5"></td></tr>
                                        <tr >
                                            <td bgcolor="#f5f5f5"> 
                                                <div class="contentEditableContainer contentImageEditable">
                                                    <div class="contentEditable">
                                                      <img src="http://ifd-templates.org/img/headerifdnew.png"  data-default="placeholder" data-max-width="600" width="600" height="180" >
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                

                                <div class="movableContent">
                                    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                                        <tr><td height="18" bgcolor="#f5f5f5"></td></tr>
                                        <tr>
                                            <td>
                                                <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top" bgcolor="f5f5f5">
                                                    <tr>
                                                        <td width="450" align="right" valign="top">
                                                            <hr style="background-color: #949494;border: 1px solid #949494;color: #949494;height: 0;margin-right: 20px;">
                                                        </td>
                                                        <td width="150" align="right" valign="middle">
                                                            <div class="contentEditableContainer contentTextEditable">
                                                                <div align="right" class="contentEditable" style="font-size:13.5pt;line-height:20pt; text-align: right">
                                                                    <img src="http://ifd-templates.org/img/logoifd.png" data-default="placeholder" data-max-width="150" width="150" height="40" >
                                                                </div>
                                                            </div>
                                                        </td>        
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>';
        //Modulos
        
        $infoArray = BidNewModuloInfo::listadoModulos($ID);
        $link="";
        $modulos="";
        for($i=0;$i<count($infoArray);$i++)
        {
            $imagen="";
            if($infoArray[$i]->getImagen()!="")
                $imagen=$infoArray[$i]->getImagen();
            $modulos.='            <div class="movableContent">
                                    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">                  
                                        <tr>
                                            <td style="padding:8px 0;">
                                                <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">
                                                    <tr>
                                                        <td align="left" valign="top" width="600">
                                                            <div class="contentEditableContainer contentTextEditable">';
            if($infoArray[$i]->getTitulo()!="")
            {
                $modulos.='
                                                                <div class="contentEditable" style="color:#252525;font-size:15pt;line-height:19px; font-weight:bold;margin-top:0px;margin-bottom:22px;">
                                                                    <p>'.$infoArray[$i]->getTitulo().'</p>                                       
                                                                </div>';
            }
            if($imagen != "")
            {
                $modulos.='                                        <div>
                                                                    <img src="http://ifd-templates.org/'.  Config::PATH_IMAGENES_FRONT.$imagen.'" width="600">
                                                                </div>';
            }
            
            if($infoArray[$i]->getTituloNota()!=""){
                $modulos.='                                                    
                                                                <div>
                                                                    <p style="color:#5297d1; font-size: 22px; margin-top: 33px">'.$infoArray[$i]->getTituloNota().'</p>
                                                                </div>';
            }
            
            $modulos.='                                                    
                                                                <div class="contentEditable" style="color:#252525;font-size:12pt;line-height:24px;margin-top:15px;">
                                                                    <p>'.nl2br($infoArray[$i]->getDescripcion()).'</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>';
            if($infoArray[$i]->getLink()!="")
            {
                $textoBoton="MÁS INFO";
                if($infoArray[$i]->getTextoBoton()!="")
                    $textoBoton=$infoArray[$i]->getTextoBoton();
                
          
                $modulos.='<tr ><td ><div style="margin-top:40px"><a href="'.$infoArray[$i]->getLink().'">
                                <table><tr><td bgcolor="#1e4566" align="center" width="145" height="30px" style="text-decoration:none; color:#fff"><a style="height:30px; color:#FFF !important;text-align:center; font-size:11pt; text-decoration:none; cursor: url()" href="'.$infoArray[$i]->getLink().'"><span style="color:#fff">'.$textoBoton.'</span></a></td></tr></table>
                        </a></Div></td></tr>';
            }
            $modulos.='
                                                </table>
                                            </td>
                                        </tr>            
                                        <tr><td height="10" width="600"><hr style="width: 100%; margin-bottom: 20px; margin-top:34px; background-color: #949494;border: 1px solid #949494;color: #949494;height: 0;margin-right: 20px;"></td></tr>
                                    </table>
                                </div>';
        }
        $html=$html.$modulos;
        $html.='        
                        <!--  =========================== The footer ===========================  -->
                                <div class="movableContent">
                                    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" valign="top">                  
                                        <tr>
                                            <td style="">
                                                <table width="600" border="0" cellspacing="0" cellpadding="0" align="left" valign="top">
                                                    <tr>
                                                        <td align="left" valign="top" width="600">
                                                            <table  width="230px" align="left">
                                                                <tr>';
                                                                if($objeto->getFacebook()!="") $html.='<td><a href="'.$objeto->getFacebook().'" target="_blank" style="padding-left:5px;"><img src="http://ifd-templates.org/img/e_facebook.png"></a></td>';
                                                                if($objeto->getTwitter()!="") $html.='<td><a href="'.$objeto->getTwitter().'" target="_blank" style="padding-left:5px;"><img src="http://ifd-templates.org/img/e_twitter.png"></a></td>';
                                                                if($objeto->getYoutube()!="") $html.='<td><a href="'.$objeto->getYoutube().'" target="_blank" style="padding-left:5px;"><img src="http://ifd-templates.org/img/e_youtube.png"></a></td>';
                                                                if($objeto->getInstagram()!="") $html.='<td><a href="'.$objeto->getInstagram().'" target="_blank" style="padding-left:5px;"><img src="http://ifd-templates.org/img/e_instagram.png"></a></td>';
                                                                if($objeto->getGoogle()!="") $html.='<td><a href="'.$objeto->getGoogle().'" target="_blank" style="padding-left:5px;"><img src="http://ifd-templates.org/img/e_google.png"></a></td>';
        $html.='
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!--  =========================== The footer ===========================  -->
                            </td>
                        </tr>   
                        <tr><td height="28">&nbsp;</td></tr>

                    </table>
                </td>
                <td height="130" bgcolor="#f5f5f5">&nbsp;</td>
            </tr>

            <tr>
                <td class="bgBody">  &nbsp;</td>
                <td class="bgBody">  &nbsp;</td>
            </tr>
        </table>
    </body>
</html>'; 
    }
}


if($oper == "HTML")
    echo $html;
elseif($oper == "DOWN")
{
    // Send Headers
    header('Content-Type: text/html');
    header('Content-Disposition: attachment; filename="test.html"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');

    // Send Headers: Prevent Caching of File
    header('Cache-Control: private');
    header('Pragma: private');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

    echo $html;
}
elseif($oper == "PDF")
{
    require_once('html2pdf.class.php');
    $pdf = new HTML2PDF('L', 'A4', 'fr', true, 'UTF-8', 10);
    $pdf->pdf->SetDisplayMode('fullpage');
    ob_end_clean();

    $pdf->writeHTML($html, isset($_GET['vuehtml']));
    $pdf->output();
    exit;
}
?>