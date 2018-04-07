$(document).ready(function(){   
    $("#button").button();

    $('#formulario').ajaxForm({
            url         : 'prog/app/mcc/modelos/bo.login.mod.php',
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
                Jota.redirect('bienvenido.php');
                break;
            case 'USUARIO_INVALIDO':
                Jota.alert(data.mensaje, 'Datos incorrectos');
                break;
            case 'CUSTOM_EX':
                Jota.alert(data.mensaje,'Error');
                break;
        }
    }
}



function validate(formData, jqForm, options) {
    var error = "";
    Jota.ui.setLoader('button', 'img/ajax-loader.gif');
    var form = jqForm[0];
    var campos = {
            fields: [
                {
                    nombre:"txtUsuario",
                    tipo: "",
                    mensajeVacio: "Por favor, ingresá tu usuario.",
                    mensajeErroneo: ""
                },
                {
                    nombre:"txtPass",
                    tipo: "",
                    mensajeVacio: "Por favor, ingresá tu password.",
                    mensajeErroneo: ""
                }
            ]

    };

    if(!$('#frm').validar(campos))
    {
        Jota.alert(campos.message,'<p>@</p>',{title:'Atención'});
    	Jota.ui.removeLoader('#button');
        return false;
    }
    else
        return true;

}
