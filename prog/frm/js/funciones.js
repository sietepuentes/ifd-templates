
function cargarCombo(objeto, valores, urlDestino){
	
	$.ajax({
		url: urlDestino,
		data: valores,
		dataType: "json",
		success: function(respuesta){
			$(objeto +' option').remove();
			
				$.each(respuesta, function(val, text) {
					$(objeto).append("<option value='"+val+"'>"+text+"</option>");
				});

		}
	});

}

function str_pad (input, pad_length, pad_string, pad_type) {
    // Returns input string padded on the left or right to specified length with pad_string  
    // *     example 1: str_pad('Kevin van Zonneveld', 30, '-=', 'STR_PAD_LEFT');
    // *     returns 1: '-=-=-=-=-=-Kevin van Zonneveld'
    // *     example 2: str_pad('Kevin van Zonneveld', 30, '-', 'STR_PAD_BOTH');
    // *     returns 2: '------Kevin van Zonneveld-----'
    var half = '', pad_to_go;

    var str_pad_repeater = function (s, len) {
        var collect = '', i;

        while (collect.length < len) {collect += s;}
        collect = collect.substr(0,len);

        return collect;
    };

    input += '';
    pad_string = pad_string !== undefined ? pad_string : ' ';
    
    if (pad_type != 'STR_PAD_LEFT' && pad_type != 'STR_PAD_RIGHT' && pad_type != 'STR_PAD_BOTH') { pad_type = 'STR_PAD_RIGHT'; }
    if ((pad_to_go = pad_length - input.length) > 0) {
        if (pad_type == 'STR_PAD_LEFT') { input = str_pad_repeater(pad_string, pad_to_go) + input; }
        else if (pad_type == 'STR_PAD_RIGHT') { input = input + str_pad_repeater(pad_string, pad_to_go); }
        else if (pad_type == 'STR_PAD_BOTH') {
            half = str_pad_repeater(pad_string, Math.ceil(pad_to_go/2));
            input = half + input + half;
            input = input.substr(0, pad_length);
        }
    }

    return input;
}

function combosFechas(){

   strSelects = "Fecha desde: <select id='cmbDiaD'></select> <select id='cmbMesD'></select> <select id='cmbAnioD'></select> ";
   strSelects += "Fecha hasta: <select id='cmbDiaH'></select> <select id='cmbMesH'></select> <select id='cmbAnioH'></select> ";
   $('#combosFecha').append(strSelects);
   cargarComboFecha("#cmbDiaD","#cmbMesD","#cmbAnioD");
   cargarComboFecha("#cmbDiaH","#cmbMesH","#cmbAnioH");

}

function cargarComboFecha(cmbDia,cmbMes,cmbAnio)
{	
		
		$(cmbDia).append("<option value=''>dia</option>");
		$(cmbMes).append("<option value=''>mes</option>");
		$(cmbAnio).append("<option value=''>a�o</option>");
		//dia
		
		for(i=1;i<=31;i++)
		{
			$(cmbDia).append(
				$('<option></option>').val(str_pad(i,2,'0','STR_PAD_LEFT')).html(i)
			);
		}
		//mes
		for(i=1;i<=12;i++)
		{
			$(cmbMes).append(
				$('<option></option>').val(str_pad(i,2,'0','STR_PAD_LEFT')).html(i)
			);
		}
		//anio
		var fecha 	= new Date();
		var anio 	= fecha.getFullYear();
		for(i=anio;i>=1900;i--)
		{
			$(cmbAnio).append(
				$('<option></option>').val(i).html(i)
			);
		}	
}

function validarEmail(email)
{
    var emailReg   = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if( !emailReg.test(email) )
        return false;
    else
        return true;
}

function validar(elForm,idProducto) {
   
    var msg   = "";
	var nick  =  $.trim(elForm.txtNick.value);
	var email =  $.trim(elForm.txtEmailComent.value);
	var ciudad = $.trim(elForm.txtCiudad.value);
	var comentario =  $.trim(elForm.txtComentario.value);
	var emailReg   = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	var strDatos   = "";
	
	if ( nick  == "" )
      msg += "Por favor, ingresa tu nick.\n";
    
	if ( email  == "" )
      msg += "Por favor, ingresa tu email.\n";
	else {
      if ( !emailReg.test(email) )
		msg += "Por favor, revisa tu e-mail, parece incorrecto.\n";
	}
	
	if (ciudad == "")
	  msg+= "Por favor, ingresa tu ciudad.\n";
	  
	if ( comentario == "" )		
	  msg += "Por favor, ingresa tu comentario.";
	
	if (msg == "" ){	
	strDatos  = "txtNick="+ nick +"&txtEmail="+ email +"&txtComentario="+ comentario 
	strDatos += "&idProducto="+ idProducto +"&txtCiudad="+ ciudad; 
	
	$.ajax({
	   type: "post",
	   url: "consultas/guardarComentarios.php",
	   data: strDatos ,
	   success: function(msg){
		 // alert("Gracias por dejar tu comentario");
		 limpiarDatos(elForm);
		 $("#divFormComentarios").hide();
		 $("#comentar_gracias_error").show();		 
	   }
	});	
				
	}	 
	else {
	  alert(msg);	
	  return false; 
    }   
 }


function limpiarDatos(frm) {	
	frm.txtNick.value = "";
	frm.txtEmailComent.value ="";
	frm.txtCiudad.value = "";
	frm.txtComentario.value = "";	
}


function mostrarComentarios() {

	 $("#comentar_gracias_error").hide();
	 var divContenedor = document.getElementById("contenedor") ;
       
     if ( divContenedor.style.display == "none" )
	   $("#contenedor").show('slow');
     else
	   $("#contenedor").hide();      
}


function mostrarFormComentario() {
	
	 $("#comentar_gracias_error").hide();
	
	 var divFrmComentarios = document.getElementById("divFormComentarios") ;
       
     if ( divFrmComentarios.style.display == "none" )
	   $("#divFormComentarios").show('slow');
     else
	   $("#divFormComentarios").hide();   
	
}


 /*
	  FUNCION LIMITCHARS
	 
	1) Agregar la funcion
    2) Agregar en el textArea : onkeyup="javascript:limitChars(this, 1000, 'spnCaracteres')"
	   Ej:	
       <asp:TextBox TextMode="MultiLine" runat="server" id="txtRespuestaPregunta"  cols="70" rows="4" width="100%" ></asp:TextBox>
 
    3) Agregar un span donde se mostraran los caracteres disponibles:
       <span id="spnCaracteres" class="Txt11celeste">1000 caracteres disponibles.</span>
 */

    function limitChars(textarea, limit, infodiv) {
        var text       = textarea.value;
        var textlength = text.length;
        // var info = document.getElementById(infodiv);
		
        if (textlength > limit) {
            /*info.innerHTML = 'No puedes usar mas de ' + limit + ' caracteres.';*/
            textarea.value = text.substr(0, limit);
            return false;
        } else {
            /*info.innerHTML = (limit - textlength) + ' caracteres disponibles.';*/
            return true;
        }
    } // limitChars


function solonumeros(e)
{
	var key;
	if(window.event) // IE
	{
		key = e.keyCode;
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		key = e.which;
	} 
        
	if ((key < 48 || key > 57) && key != 8 && key!=44 && key !=46)
            return false;
	return true;
}

function eliminarImagen(id, campo, div, nombreId, tabla, campoTipo)
{
    $.ajax(
            {
                type: "POST",
                url: "../mcc/helpers/deleteImage.php",
                data: "id="+id+"&campoID="+nombreId+"&campo="+campo+"&tabla="+tabla+"&campoTipo="+campoTipo,
                success: function(respuesta){
                    if(respuesta == "1")
                    {
                        Jota.alert("Se elimino el archivo.",{title:'Archivo'});
                        $("#"+div).remove();
                    }
                    else
                        Jota.alert("Error al eliminar el archivo.",{title:'Archivo'});
                }
            });
}

function verArchivo(id, campo, path, tags, cid, t)
{
    window.open('../mcc/helpers/verArchivoPop.php?cid='+cid+'&t='+t+'&id='+id+'&c='+campo+'&p='+path+'&tags='+tags,'ver', 'toolbar=0, status=0, width=650, height=450');
}