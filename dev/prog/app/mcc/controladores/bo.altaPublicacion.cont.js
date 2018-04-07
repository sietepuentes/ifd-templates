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
    
    $('#colorpalette').colorPalette().on('selectColor', function(e) {
        $('#selected-color').val(e.color);
    });

    if(Jota.url.queryString("oper") == "edit")
    {
        $.ajax(
        {
            type: "POST",
            url: "prog/app/mcc/helpers/obtenerDatosPublicacion.php",
            data: "id="+Jota.url.queryString("id"),
            dataType : 'json',
            success: function(respuesta){
                $("#txtTitulo").val(respuesta.txtTitulo);
                $("#txtDescripcion").val(respuesta.txtDescripcion);
                $("#txtBajada").val(respuesta.txtBajada);
                $("#selected-color").val(respuesta.txtColor);
                
                $("#txtFacebook").val(respuesta.txtFacebook);
                $("#txtTwitter").val(respuesta.txtTwitter);
                $("#txtYoutube").val(respuesta.txtYoutube);
                $("#txtInstagram").val(respuesta.txtInstagram);
                $("#txtGoogle").val(respuesta.txtGoogle);
                
                if(respuesta.imagen !="" )
                {
                    $("#hidImagen").val(respuesta.imagen_sin_rand);
                    //$("#imagen_txt").append("<a href='"+respuesta.path_img+respuesta.imagen+"' id='image"+Jota.url.queryString("id")+"' data-toggle='lightbox'>Ver archivo</a>&nbsp;&nbsp;&nbsp;<a href='Javascript:eliminarImagen("+Jota.url.queryString("id")+", \"Imagen\",\""+respuesta.imagen+"\", \"imagen_txt\",\""+respuesta.path_img_del+"\")'>Eliminar</a>");
                    $("#imagen_txt").append("<a href='"+respuesta.path_img+respuesta.imagen+"' id='image"+Jota.url.queryString("id")+"' target='_blank'><img src='"+respuesta.path_img+respuesta.imagen+"' width='40%'></a>&nbsp;&nbsp;&nbsp;<a href='Javascript:eliminarImagen("+Jota.url.queryString("id")+", \"Imagen\",\""+respuesta.imagen+"\", \"imagen_txt\",\""+respuesta.path_img_del+"\")' class='btn btn-xs btn-danger'><i class='fa fa-trash-o'></i> Eliminar</a>");
                }
                $("#listadoAutores").append(respuesta.autores);
                $("#hidCantAut").val(parseInt(respuesta.cantAut));   
                
                $("#listadoLinks").append(respuesta.links);
                $("#hidCantLin").val(parseInt(respuesta.cantLin));
            }
        });
    }
    
    $('#formulario').ajaxForm({
            url         : 'prog/app/mcc/modelos/bo.abmPublicacion.mod.php',
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
                window.parent.top.location="publicaciones.php";

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
                ,
                {
                    nombre:"txtDescripcion",
                    tipo: "",
                    mensajeVacio: "Por favor, ingresá la descripcion.",
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
        data: "id="+id+"&cid=IDPublicacion&c="+campo+"&t=publicaciones&f="+foto+"&p="+path,
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

function agregarAutor()
{
    var i = $("#hidCantAut").val();
    var autores = '';
    autores+= ' <div class="row" id="autor_'+i+'">\n\
                    <div class="col-lg-11">\n\
                        <div class="form-group">\n\
                            <input class="form-control" placeholder="Autor" id="txtAutor_'+i+'" name="txtAutor_'+i+'" required autofocus>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-1">\n\
                        <div class="form-group">\n\
                            <button id="button" onclick="eliminarAutor('+i+');" class="btn btn-xs btn-primary" style="margin-left: -10px;margin-top: 4px;" type="button">X</button>\n\
                        </div>\n\
                    </div>\n\
                </div>';
    $("#listadoAutores").append(autores);
    $("#hidCantAut").val(parseInt($("#hidCantAut").val())+1);
    
}

function eliminarAutor(id)
{
    $("#autor_"+id).remove();
}

function agregarLink()
{
    var i = $("#hidCantLin").val();
    var links = '';
    links+= ' <div class="row" id="link_'+i+'">\n\
                    <div class="col-lg-11">\n\
                        <div class="form-group">\n\
                            <input class="form-control" placeholder="Pais" id="txtPais_'+i+'" name="txtPais_'+i+'" required autofocus>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-1">\n\
                        <div class="form-group">\n\
                            <button id="button" onclick="eliminarLink('+i+');" class="btn btn-xs btn-primary" style="margin-left: -10px;margin-top: 4px;" type="button">X</button>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-11">\n\
                        <div class="form-group">\n\
                            <input class="form-control" placeholder="Link" id="txtLink_'+i+'" name="txtLink_'+i+'" required autofocus>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-12"><hr></div>\<n\
                </div>';
    $("#listadoLinks").append(links);
    $("#hidCantLin").val(parseInt($("#hidCantLin").val())+1);
    
}

function eliminarLink(id)
{
    $("#link_"+id).remove();
}