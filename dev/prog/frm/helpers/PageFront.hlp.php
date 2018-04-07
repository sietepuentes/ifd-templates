<?php

Jota::incluir(  array(  'helpers'=> array(  'Page',
                                            'ErrorHandler'
                                        ),
                        'clases'=> 'Registrado'
                )
        );


class PageFront
{
    //claves de COOKIE
    const KEY_IMAGEN_LIBRO= "d4c2c8eac8c3babe0baef9d51d4454cd7776bcef";
    //paginas
    const PAGINA_LOGIN= "login.php";
    const PAGINA_LISTADO_USUARIOS= "abmUsuariosBo.php";

    public static function getSecreto()
    {
        $sRetorno="";
        if(isset ($_COOKIE[PageFront::KEY_REGISTRADO_LOGEADO]))
            $sRetorno= $_COOKIE[PageFront::KEY_REGISTRADO_LOGEADO];

        return $sRetorno;
    }

    public static function getImagenLibro()
    {
                $sRetorno="";
        if(isset ($_COOKIE[PageFront::KEY_IMAGEN_LIBRO]))
            $sRetorno= $_COOKIE[PageFront::KEY_IMAGEN_LIBRO];

        return $sRetorno;
    }

    public static function guardarImagenLibroEnCookie($nombre)
    {
        setcookie(PageFront::KEY_IMAGEN_LIBRO, $nombre);
    }

    public static function desloguear()
    {
        //borramos la cookie
        setcookie(PageFront::KEY_REGISTRADO_LOGEADO, "", time() - 350);
    }

    public static function logearRegistrado($sSecreto)
    {
        setcookie(PageFront::KEY_REGISTRADO_LOGEADO, $sSecreto);
    }

    public static function estaLogeado()
    {
        return!Data::vacio(PageFront::getSecreto());
    }

    public static function verificarSesion()
    {
        if (!PageFront::estaLogeado())
        {
            Page::redirect(PageFront::PAGINA_LOGIN);
        }
        else
        {
            try
            {
                $oUsuario = Registrado::login(null, null, PageFront::getSecreto());
                if ($oUsuario == null)
                    Page::redirect(PageFront::PAGINA_LOGIN);
            }
            catch (Exception $ex)
            {
                ErrorHandler::manejarError($ex, PageFront::PAGINA_LOGIN);
            }
        }
    }

}
?>
