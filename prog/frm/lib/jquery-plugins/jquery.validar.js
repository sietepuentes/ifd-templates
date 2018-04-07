/*
 * VALIDADOR
 * creado por Jota
 *
 * tipo de campos soportados:
 * email, dni, fecha, radio, checkbox, grupoChecks, longMin, combo
 */
(function($){
    $.fn.validar = function(elements/*, pOptions*/) {
//        var $this = $(this);
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var mensajeSalida = [];
//
//		var options = jQuery.extend({
//			title: 'Atención',
//			width: '321px',
//			modal: true,
//			resizable: false,
//			buttons: { "Ok": function() { $(this).dialog("close"); } }
//		}, pOptions);

        $.each(elements.fields, function(key) {
            var t = this.tipo;
            var campo = $.trim($('#'+this.nombre).val());
            switch (t) {
                case "email":
                    if(campo == "")
                    {
                        mensajeSalida.push(this.mensajeVacio);
                    }
                    else if (!emailReg.test(campo))
                    {
                        mensajeSalida.push(this.mensajeErroneo);
                    }
                    break;

                case "grupoEmails":
                    $(this.nombre).each(
                        function(intIndex) {
                            if (!emailReg.test($('#'+this).val())) {
                                mensajeSalida.push(elements.fields[key].mensajeErroneo.replace("[index]",intIndex+1));
                            }
                        }
                        );
                    break;

                case "dni":
                    if(campo == "")
                    {
                        mensajeSalida.push(this.mensajeVacio);
                    }else if(campo.length < 7 || campo.length > 8){
                        mensajeSalida.push(this.mensajeErroneo);
                    }
                    break;
                case "fecha":

                    var fecha = this.nombre.split("-");
                    var dia = $('#'+fecha[0] + " :selected").val();
                    var mes = $('#'+fecha[1] + " :selected").val();
                    var anio = $('#'+fecha[2] + " :selected").val();

                    if(!_isDate(dia, mes, anio))
                    {
                        mensajeSalida.push(this.mensajeVacio);
                    }

                    break;
                case "radio":
                    if (!$("input[name="+ this.nombre + "]:radio").is(':checked')){
                        mensajeSalida.push(this.mensajeVacio);
                    }
                    break;
                case "checkbox":
                    if (!$("#"+ this.nombre).is(':checked')){
                        mensajeSalida.push(this.mensajeVacio);
                    }

                    break;
                case "grupoChecks":
                    var sel = false;
                    var arrCheks = this.nombre.split('-');

                    $(arrCheks).each(
                        function( intIndex ){
                            if ($("#"+ arrCheks[intIndex]).is(':checked')){
                                sel = true;
                            }
                        }
                        );
                    if (sel == false){
                        mensajeSalida.push(this.mensajeVacio);
                    }
                    break;
                case "longMin":
                    if ($("#"+ this.nombre).val().length < 3){
                        mensajeSalida.push(this.mensajeVacio);
                    }
                    break;
                case "combo":
                    if(campo == "0" && $('#'+this.nombre).is(':visible'))
                    {
                        mensajeSalida.push(this.mensajeVacio);
                    }
                    break;
                case "custom":
                    if(!this.validacionCustom(campo))
                    {
                        mensajeSalida.push(this.mensajeVacio);
                    }
                    break;
                case 'rango':
					if(campo == "") {
                        mensajeSalida.push(this.mensajeVacio);
                    }
					else {
						if(campo< this.rango[0]|| campo> this.rango[1])
						{
							mensajeSalida.push(this.mensajeErroneo);
						}
					}
                    break;
                default:
					if(campo == "" /*&& $('#'+this.nombre).is(':visible')*/) {
						mensajeSalida.push(this.mensajeVacio);
					}
					else {
						if(this.igualA) {
							if(campo!= $('#'+ this.igualA).val()) {
								mensajeSalida.push(this.mensajeIgualdad);
							}
						}
					}
            }
        });

        if(mensajeSalida.length > 0)
        {
            elements.message = mensajeSalida;//$('<div>' + mensajeSalida + '</div>').dialog(options);
            return false;
        }else{
            return true;
        }

    };

    function _isDate(dd,mm,yyyy){
        var d = new Date(mm + "/" + dd + "/" + yyyy);
        return d.getMonth() + 1 == mm && d.getDate() == dd && d.getFullYear() == yyyy;
    }

})(jQuery);