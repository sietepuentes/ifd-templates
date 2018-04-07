var enviando = false;
///////////// CONTROLADOR /////////////////////
$(document).ready(function(){
    $("#oper").val(Jota.url.queryString("oper"));
    $("#id").val(Jota.url.queryString("id"));

    $("#button").button();

    $(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function(event) {
        event.preventDefault();
        return $(this).ekkoLightbox();
    });
    


    if(Jota.url.queryString("oper") == "edit")
    {
        $.ajax(
        {
            type: "POST",
            url: "prog/app/mcc/helpers/obtenerDatosNew.php",
            data: "id="+Jota.url.queryString("id"),
            dataType : 'json',
            success: function(respuesta){
                $("#txtTitulo").val(respuesta.txtTitulo);
                
                $("#txtFacebook").val(respuesta.txtFacebook);
                $("#txtTwitter").val(respuesta.txtTwitter);
                $("#txtYoutube").val(respuesta.txtYoutube);
                $("#txtInstagram").val(respuesta.txtInstagram);
                $("#txtGoogle").val(respuesta.txtGoogle);
                
                $("#listadoInfo").append(respuesta.info);
                $("#hidCantInf").val(parseInt(respuesta.cantInf));   
            }
        });
    }
    
    $('#formulario').ajaxForm({
            url         : 'prog/app/mcc/modelos/bo.abmNew.mod.php',
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
                window.parent.top.location="news.php";

                break;
            case 'ERROR_ALTA':
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
                    nombre:"txtTitulo",
                    tipo: "",
                    mensajeVacio: "Por favor, ingresá el titulo.",
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

function eliminarImagen(id, campo, foto, div, path)
{
    $.ajax(
    {
        type: "POST",
        url: "prog/app/mcc/helpers/deleteImage.php",
        data: "id="+id+"&cid=IDNew&c="+campo+"&t=news&f="+foto+"&p="+path,
        success: function(respuesta){
            if(respuesta == "1")
            {
                Jota.alert("Se elimino el archivo.",'Archivo');
                $("#"+div).remove();
            }
            else
                Jota.alert("Error al eliminar el archivo.",'Archivo');
        }
    });
}

function agregarModulo()
{
    var i = $("#hidCantInf").val();
    var modulo = '';
    modulo+= ' <div class="row" id="info_'+i+'">\n\
                    <div class="col-lg-11">\n\
                        <div class="form-group">\n\
                            <input class="form-control" placeholder="Titulo" id="txtTitulo_'+i+'" name="txtTitulo_'+i+'" required autofocus>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-1">\n\
                        <div class="form-group">\n\
                            <button id="button" onclick="eliminarModulo('+i+');" class="btn btn-xs btn-primary" style="margin-left: -10px;margin-top: 4px;" type="button">X</button>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-11">\n\
                        <div class="form-group">\n\
                            <textarea class="form-control" placeholder="Descripcion" id="txtDescripcion_'+i+'" name="txtDescripcion_'+i+'"></textarea>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-11">\n\
                        <div class="form-group">\n\
                            <input class="form-control" placeholder="Titulo Nota" id="txtTituloNota_'+i+'" name="txtTituloNota_'+i+'" required autofocus>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-11">\n\
                        <div class="form-group">\n\
                            <div class="btn btn-success" style="width:70%"><input type="file" name="imagen_'+i+'" id="imagen_'+i+'"><em style="font-size: 10px;">(Tamaño ideal Ancho 238px y 312px de Alto)</em></Div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-11">\n\
                        <div class="form-group">\n\
                            <input class="form-control" placeholder="Link Boton" id="txtLink_'+i+'" name="txtLink_'+i+'" >\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-12"><hr></div>\n\
                </div>';
    
    $("#listadoInfo").append(modulo);
    $("#hidCantInf").val(parseInt($("#hidCantInf").val())+1);
    
    
    
}

function eliminarModulo(id)
{
    $("#info_"+id).remove();
}
