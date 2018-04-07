<?php
/**
 * Clase helper para trabajar con encodings
 *
 * @author dami
 */

class Encoding
{
    public static function toUTF8($dato)
    {
        return Data::deep($dato, 'utf8_encode');
    }

    public static function fromUTF8($dato)
    {
        return Data::deep($dato, 'utf8_decode');
    }
}
?>
