<?php

class Html {
    public static function getSelect($arrResults, $sAtributos, $sCampoNombre= null, $sCampoId= null, $sTextoVacio= null, $iIdSeleccionado= null)
    {
        $sRetorno= "<select $sAtributos >";

        $sCampoNombre= Data::ifNull($sCampoNombre, 'nombre');
        $sCampoId= Data::ifNull($sCampoId, 'id');
        $iIdSeleccionado= Data::ifNull($iIdSeleccionado, "0");

        if (!Data::vacio($sTextoVacio))
        {
            $sRetorno.= "<option value=\"0\" " .
                    (Data::vacio($iIdSeleccionado) ? "SELECTED " : "") .
                    " >$sTextoVacio</option>";
        }

        foreach ($arrResults as $fila)
            $sRetorno.= "<option value=\"".
                $fila[$sCampoId]."\" ".
                ($fila[$sCampoId]== $iIdSeleccionado? "SELECTED ": "").
                ">".
                $fila[$sCampoNombre].
                "</option>";

        $sRetorno.= "</select>";

        return $sRetorno;
    }
}
?>
