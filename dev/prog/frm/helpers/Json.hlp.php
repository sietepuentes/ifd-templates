<?php

/**
 * Clase helper para facil la creacion de jsons copantes.
 *
 * @author dami
 */

//Jota::incluir( array( 'helpers'=> 'Encoding' ) );


class JSon
{
    public static function encode($data, $encondearEnUtf8= false)
    {
        if($encondearEnUtf8)
            return json_encode(Encoding::toUTF8 ($data));
        else
            return json_encode($data);
    }

    public static function decode($data)
    {
        return json_decode($data);
    }
}
?>
