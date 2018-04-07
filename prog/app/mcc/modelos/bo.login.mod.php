<?php
require_once "../../../frm/core/ini.php";
Jota::incluir(   array(  'clases' => array('BidUsuarioBo'),
                         'helpers' => array('Page',
                                             'ErrorHandler',
                                             'PageBo'
                         )
                 )
);

 try {
    if (!Data::vacio(Page::getOpcional('txtUsuario')))
    {
        $sUsusario = Page::get('txtUsuario');
        $sPass = Page::get('txtPass');

        $oUsuario = BidUsuarioBo::login($sUsusario, $sPass);

        //if ($oUsuario != null)
        if ($oUsuario->getIDUsuario() > 0)
        {
            $_SESSION[PageBo::KEY_USUARIO_BO] = $oUsuario->getIDUsuario();
            $_SESSION["Nombre".PageBo::KEY_USUARIO_BO] = $oUsuario->getNombre()." ".$oUsuario->getApellido();
            
            echo '{"tipo":"OK","mensaje": "' .('El usuario se ha logeado correctamente.') .'"}';
        }
        else
        {
            echo '{"tipo":"USUARIO_INVALIDO","mensaje": "' .('Nombre de usuario o contraseña inválido.') .'"}';
        }
    }
} catch (WebarException $wex) {
    echo '{"tipo":"CUSTOM_EX","mensaje": "' . ($wex->getMessage()) . '"}';
    
}
catch (Exception $ex)
{
    ErrorHandler::manejarError($ex);
    
}
?>