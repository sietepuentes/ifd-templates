<?php

class PageBo
{
    //claves de session para el BO
    const KEY_USUARIO_BO = "coopintranet";
    //paginas
    const PAGINA_LOGIN= "login.php";
    const PAGINA_LISTADO_USUARIOS= "abmUsuariosBo.php";

    public static function getSecreto()
    {
        $sRetorno="";
        //if(isset ($_COOKIE[PageBo::KEY_USUARIO_BO]))
        //    $sRetorno= $_COOKIE[PageBo::KEY_USUARIO_BO];

        if(isset ($_SESSION[PageBo::KEY_USUARIO_BO]))
            $sRetorno= $_SESSION[PageBo::KEY_USUARIO_BO];
        
        return $sRetorno;
    }

    public static function desloguear()
    {
        //borramos la cookie
        //setcookie(PageBo::KEY_USUARIO_BO, "", time() - 350);
        $_SESSION[PageBo::KEY_USUARIO_BO] = null;
    }

    public static function logearUsuario($sSecreto)
    {
        //setcookie(PageBo::KEY_USUARIO_BO, $sSecreto);
        $_SESSION[PageBo::KEY_USUARIO_BO] = $sSecreto;
    }

    public static function estaLogeado()
    {
        return!Data::vacio(PageBo::getSecreto());
    }

    public static function verificarSesion()
    {
        if (!PageBo::estaLogeado())
        {
            Page::redirect(PageBo::PAGINA_LOGIN);
        }
        else
        {
            try
            {
//                $oUsuario = UsuarioBo::login(null, null, PageBo::getSecreto());
//                if ($oUsuario == null)
//                    Page::redirect(PageBo::PAGINA_LOGIN);
            }
            catch (Exception $ex)
            {
                ErrorHandler::manejarError($ex, PageBo::PAGINA_LOGIN);
            }
        }
    }
    
    public static function permisos()
    {
        //Verifico segun el perfil,
        //1 	Super Administrador
	//2 	Administrador
        $perfilBo="";
	if(isset($_SESSION["Perfil".PageBo::KEY_USUARIO_BO]))
        {
            $perfilBo = $_SESSION["Perfil".PageBo::KEY_USUARIO_BO]; 
            if($perfilBo==2)
            {
                /*Verifico si tiene permiso en la pagina*/
                $paginaActualPermiso = strtolower(basename($_SERVER['PHP_SELF']));

                switch ($paginaActualPermiso)
                {
                    case "abmusuariosbo.php":
                    case "altausuariobo.php":                       
                    case "abmcategorias.php":
                    case "altacategoria.php": 
                    case "abmadelantos.php":                     
                    case "abmcreditos.php":                     
                    case "abmsueldos.php":                     
                    case "abmempleados.php":                     
                    case "abmcomercios.php":
                    case "altaadelanto.php":                     
                    case "altacomercio.php":                                         
                    case "altacredito.php":    
                    case "altaempleado.php":        
                    case "altasueldos.php":                    
                        Page::redirect(PageBo::PAGINA_LOGIN);
                        break;
                }
            }
        }
        else
        {
            Page::redirect(PageBo::PAGINA_LOGIN);
        }
        return $perfilBo;
    }
}
?>
