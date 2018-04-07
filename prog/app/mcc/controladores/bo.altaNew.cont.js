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

    modulo+= ' <div class="row" id="info_'+i+'"> <div class="col-lg-11">     <div class="form-group">         <input class="form-control" placeholder="Titulo" id="txtTitulo_'+i+'" name="txtTitulo_'+i+'"  autofocus>     </div> </div> <div class="col-lg-1">     <div class="form-group">         <button id="button" onclick="eliminarModulo('+i+');" class="btn btn-xs btn-primary" style="margin-left: -10px;margin-top: 4px;" type="button">X</button>     </div> </div> <div class="col-lg-11">     <div class="form-group">         <input class="form-control" placeholder="Titulo Nota" id="txtTituloNota_'+i+'" name="txtTituloNota_'+i+'"  autofocus>     </div> </div> <div class="col-lg-11">     <div class="form-group">         <textarea class="form-control" placeholder="Descripcion" id="txtDescripcion_'+i+'" name="txtDescripcion_'+i+'"></textarea>     </div> </div> <div class="col-lg-11">         <div class="form-group">                              <label>Imagen <em style="font-size: 10px;">(Tamaño ideal Ancho 238px y 312px de Alto)</em></label>                              <div id="imagen_txt"></div>                              <input type="hidden" name="imagen_'+i+'" id="imagen_'+i+'" value="" />                              <div id="userpic_'+i+'" class="userpic">                                  <div class="js-preview userpic__preview"></div>                                  <div class="btn btn-success js-fileapi-wrapper">                                     <div class="js-browse">                                        <span class="btn-txt"></span>                                        <input name="filedata" type="file">                                     </div>                                     <div class="js-upload" style="display: none;">                                        <div class="progress progress-success"><div class="js-progress bar"></div></div>                                        <span class="btn-txt">Uploading</span>                                     </div>                                  </div>                              </div>                          </div> </div> <div class="col-lg-11">     <div class="form-group">         <input class="form-control" placeholder="Link Boton" id="txtLink_'+i+'" name="txtLink_'+i+'" >     </div> </div> <div class="col-lg-11">     <div class="form-group">         <input class="form-control" placeholder="Texto Boton" id="txtTextoBoton_'+i+'" name="txtTextoBoton_'+i+'" >     </div> </div> <div class="col-lg-12"><hr></div>              </div>';
    $("#listadoInfo").append(modulo);

    $("#hidCantInf").val(parseInt($("#hidCantInf").val())+1);



        $('#userpic_'+i+'').fileapi({
            url: 'uploadImagen.php',
            accept: 'image/*',
            imageSize: { minWidth: 238, minHeight: 312 },
            elements: {
               active: { show: '.js-upload', hide: '.js-browse' },
               preview: {
                  el: '.js-preview',
                  width: 238,
                  height: 312
               },
               progress: '.js-progress'
            },
            onComplete: function (err, test){
                if(test.result.ok!="")
                {
                    $("#imagen_txt").empty();
                    $("#hidImagen").val(test.result.ok);
                }
            },
            onSelect: function (evt, ui){
               var file = ui.files[0];
               if( !FileAPI.support.transform ) {
                  alert('Your browser does not support Flash :(');
               }
               else if( file ){
                  $('#popup').modal({                  
                     closeOnEsc: true,
                     closeOnOverlayClick: false,
                     onOpen: function (overlay){
                        $(overlay).on('click', '.js-upload', function (){
                           $.modal().close();
                           $('#userpic_'+i+'').fileapi('upload');
                        });
                        $('.js-img', overlay).cropper({
                           file: file,
                           bgColor: '#fff',
                           maxSize: [$(window).width()-100, $(window).height()-100],
                           minSize: [238, 312],
                           selection: '90%',
                           onSelect: function (coords){
                              $('#userpic_'+i+'').fileapi('crop', file, coords);
                           }
                        });
                     }
                  }).open();
               }
            }
         });
    

    

    

}



function eliminarModulo(id)

{

    $("#info_"+id).remove();

}

