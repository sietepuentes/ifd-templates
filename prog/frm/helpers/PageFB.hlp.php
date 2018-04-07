<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Luki
 */
Jota::incluir(array (   'helpers'   => "Page",
                        'libs'      => "FBUtils"
                    )
);

class PageFB
{
    public static function conectar()
    {
        global $browser;
	$fb = FBUtils::getInstancia(array(
	                    "appId" => FB_APP_ID,               //Id de la aplicacion
	                    "secret"=> FB_APP_SECRET,           //Secret de la aplicacion
	                    "key"   => FB_APP_KEY,              //Key de la aplicacion
	                    "perms" => FB_APP_PERMISOS,         //Permisos que se van a requerir
	                    "urlApp"=> FB_APP_URL,              //Url de la aplicacion (Para el login)
	                    "browser"=> $browser->getBrowser()  //Navegador que usa el usuario.
	));
	//$me  = $fb->getUserInfo();
        return $fb;
    }

    public static function redirect($destino){
        Page::redirect(self::armarLink($destino));
    }


    public static function armarLink($destino)
    {
        global $fb;
        if ($fb->getBrowser() == Browser::BROWSER_SAFARI)
            return (strpos($destino, '?')? (strpos($destino, '&') ? $destino . "&session=" . urlencode($_GET['session']) : $destino . "session=" . urlencode($_GET['session'])) : $destino . "?session=" . urlencode($_GET['session']));
        else
            return $destino;
    }
}
?>
