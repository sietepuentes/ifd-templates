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
            url: "prog/app/mcc/helpers/obtenerDatosUsuarioBO.php",
            data: "id="+Jota.url.queryString("id"),
            dataType : 'json',
            success: function(respuesta){
                $("#txtNombre").val(respuesta.txtNombre);
                $("#txtApellido").val(respuesta.txtApellido);
                $("#txtUsuario").val(respuesta.txtUsuario);
                $("#txtPassword").val(respuesta.txtPassword);
                $("#txtEmail").val(respuesta.txtEmail);
            }
        });
    }
    
    $('#formulario').ajaxForm({
            url         : 'prog/app/mcc/modelos/bo.abmUsuariosBo.mod.php',
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
                window.parent.top.location="administradores.php"

                break;
            case 'ERROR_ALTA_USUARIO':
                Jota.alert(data.mensaje,'Error/es');
                break;
        }
    }
}

function validate(formData, jqForm, options) {
    var error = "";
    //Jota.ui.setLoader('button', '../img/ajax-loader.gif');
    var form = jqForm[0];

    var campos = {
            fields: [
                {
                    nombre:"txtNombre",
                    tipo: "",
                    mensajeVacio: "Por favor, ingresá el nombre.",
                    mensajeErroneo: ""
                }
                ,
                {
                    nombre:"txtUsuario",
                    tipo: "",
                    mensajeVacio: "Por favor, ingresá el usuario.",
                    mensajeErroneo: ""
                },
                {
                    nombre:"txtEmail",
                    tipo: "email",
                    mensajeVacio: "Por favor, ingresá el email.",
                    mensajeErroneo: "El email ingresado no tiene un formato valido."
                }
                ,
                {
                    nombre:"txtPassword",
                    tipo: "",
                    mensajeVacio: "Por favor, ingresá el password.",
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