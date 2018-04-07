var enviando = false;
///////////// CONTROLADOR /////////////////////
$(document).ready(function(){
    $("#oper").val(Jota.url.queryString("oper"));
    $("#id").val(Jota.url.queryString("id"));

    $("#button").button();

    if(Jota.url.queryString("oper") == "edit")
    {
        $.ajax(
        {
            type: "POST",
            url: "prog/app/mcc/helpers/obtenerDatosCuenta.php",
            data: "id="+Jota.url.queryString("id"),
            dataType : 'json',
            success: function(respuesta){
                $("#txtNumero").val(respuesta.txtNumero);
                Jota.forms.cargarCombo("#cmbCooperativa", '', "prog/app/mcc/helpers/listarCooperativas.php", respuesta.cmbCooperativa);
            }
        });
    }
    else
    {
        Jota.forms.cargarCombo("#cmbCooperativa", '', "prog/app/mcc/helpers/listarCooperativas.php", "");
    }
    $('#formulario').ajaxForm({
            url         : 'prog/app/mcc/modelos/bo.abmCuenta.mod.php',
            type        : 'POST',
            dataType    : 'json',
            beforeSubmit: validate,
            success     : hecho
    });
    
    
});

function hecho(data){
    Jota.ui.removeLoader('#button');

    if (data){
        switch(data.tipo){
            case 'OK':
                Jota.alert(data.mensaje,{title:'Menu'});
                window.parent.top.location="abmCuentas.php"

                break;
            case 'ERROR_ALTA_USUARIO':
                Jota.alert(data.mensaje,{title:'Error!'});
                break;
        }
    }
}

function validate(formData, jqForm, options) {
    var error = "";
    Jota.ui.setLoader('button', '../img/ajax-loader.gif');
    var form = jqForm[0];

    var campos = {
            fields: [
                {
                    nombre:"cmbCooperativa",
                    tipo: "",
                    mensajeVacio: "Por favor, seleccioná la cooperativa.",
                    mensajeErroneo: ""
                },{
                    nombre:"txtNumero",
                    tipo: "",
                    mensajeVacio: "Por favor, ingresá el numero.",
                    mensajeErroneo: ""
                }
            ]

    };

    if(!$('#frm').validar(campos))
    {
        Jota.alert(Jota.data.arrayToStr(campos.message,'<p>@</p>'),{title:'Atención'});
    	Jota.ui.removeLoader('#button');
        return false;
    }
    else
        return true;

}