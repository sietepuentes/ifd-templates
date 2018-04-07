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
            url: "prog/app/mcc/helpers/obtenerDatosNewsletter.php",
            data: "id="+Jota.url.queryString("id"),
            dataType : 'json',
            success: function(respuesta){
                $("#txtTitulo").val(respuesta.txtTitulo);
                $("#txtTextoBarraAzul1").val(respuesta.txtTextoBarraAzul1);
                $("#txtTextoBarraAzul2").val(respuesta.txtTextoBarraAzul2);
                $("#txtTextoBarraAzul3").val(respuesta.txtTextoBarraAzul3);
                $("#txtBajada").val(respuesta.txtBajada);
                $("#txtDescripcionFinal").val(respuesta.txtDescripcionFinal);
                $("#txtComentarioFinal").val(respuesta.txtComentarioFinal);
                
                $("#txtFacebook").val(respuesta.txtFacebook);
                $("#txtTwitter").val(respuesta.txtTwitter);
                $("#txtYoutube").val(respuesta.txtYoutube);
                $("#txtInstagram").val(respuesta.txtInstagram);
                $("#txtGoogle").val(respuesta.txtGoogle);
                
                if(respuesta.chkColumna == "1")
                    $( "#chkColumna").attr('checked', 1);
                
                if(respuesta.imagen !="" )
                {
                    $("#hidImagen").val(respuesta.imagen_sin_rand);
                    $("#imagen_txt").append("<a href='"+respuesta.path_img+respuesta.imagen+"' id='image"+Jota.url.queryString("id")+"'  target='_blank'><img src='"+respuesta.path_img+respuesta.imagen+"' width=40%</a>&nbsp;&nbsp;&nbsp;<a href='Javascript:eliminarImagen("+Jota.url.queryString("id")+", \"Imagen\",\""+respuesta.imagen+"\", \"imagen_txt\",\""+respuesta.path_img_del+"\")' class='btn btn-xs btn-danger'><i class='fa fa-trash-o'></i> Eliminar</a>");
                }
                $("#listadoInfo").append(respuesta.info);
                $("#hidCantInf").val(parseInt(respuesta.cantInf));   
            }
        });
    }
    
    $('#formulario').ajaxForm({
            url         : 'prog/app/mcc/modelos/bo.abmNewsletter.mod.php',
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
                window.parent.top.location="newsletters.php";

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
                ,
                {
                    nombre:"txtBajada",
                    tipo: "",
                    mensajeVacio: "Por favor, ingresá la bajada.",
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
        data: "id="+id+"&cid=IDNewsletter&c="+campo+"&t=newsletters&f="+foto+"&p="+path,
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
                    <div class="col-lg-5">\n\
                        <div class="form-group">\n\
                            <input class="form-control posicionParaColor"  id="selected-color'+i+'" name="selected-color'+i+'">\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-5">\n\
                        <div class="form-group">\n\
                            <a class="btn btn-mini dropdown-toggle btn-primary" data-toggle="dropdown">Color Boton</a>\n\
                            <ul class="dropdown-menu" style="position:relative">\n\
                                <li><div id="colorpalette'+i+'"></div></li>\n\
                            </ul>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-11">\n\
                        <div class="form-group">\n\
                            <input class="form-control" placeholder="Texto Boton" id="txtTextoBoton_'+i+'" name="txtTextoBoton_'+i+'" >\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-11">\n\
                        <div class="form-group">\n\
                            <input class="form-control" placeholder="Link Boton" id="txtLink_'+i+'" name="txtLink_'+i+'" >\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-12"><hr></div>\n\
                </div>';
    modulo+="<script>$('#colorpalette"+i+"').colorPalette().on('selectColor', function(e) {\n\
        $('#selected-color"+i+"').val(e.color);\n\
    });</script>";
    
    $("#listadoInfo").append(modulo);
    $("#hidCantInf").val(parseInt($("#hidCantInf").val())+1);
    
    
    
}

function eliminarModulo(id)
{
    $("#info_"+id).remove();
}
