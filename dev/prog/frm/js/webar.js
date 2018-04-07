Webar= function()
{
    return {

        redirect: function(ruta, stayIfEmpty)
        {
	    if (ruta==null)
	        ruta = "";
	    if (ruta == "" && stayIfEmpty)
	        return;
            window.location= ruta;
        },
        setCookie: function(arrCookies, opciones)
        {
//            var options = { path: '/', expires: 10 };

            $(arrCookies).each(function(index){
                $.cookie(arrCookies[index].clave, arrCookies[index].valor);
            });
        },
        url: {
                entera: function(){
                    return location.href;
                },
                encode: function(sDato)
                {
                    return encodeURIComponent(sDato);
                },
                decode: function(sDato)
                {
                    return decodeURIComponent(sDato);
                },
                relativa: function(){
                    var ruta= location.href.split("/");
                    return ruta[ruta.length -1];
                },
                nombrePagina: function(){
                    var retorno= Jota.url.relativa().split('?');
                    return retorno[0].split('#')[0];
                },
                id: function(){
                    var retorno= Jota.url.relativa().split('#');
                    if(retorno.length> 1)
                    {
                        return retorno[retorno.length-1];
                    }
                    else
                        {
                            return '';
                        }
                },
                queryString: function(sNombreParam){
                    //[dami]aca hago un polimorfismo machazo
                    var retorno= {
                        toda: function(){
                            var retorno= Jota.url.relativa().split('?');
                            return (retorno[1]? retorno[1]: '');
                        },
                        parametro:function(sNombre){
                            var retorno= {};
                            var e,
                                d = function (s)
                                {
                                    return decodeURIComponent(s.replace(/\+/g, " "));
                                },
                                q = window.location.search.substring(1),
                                r = /([^&=]+)=?([^&]*)/g;
                            while ((e = r.exec(q)))
                            {
                                retorno[d(e[1])] = d(e[2]);
                            }
                            return retorno[sNombre];
                        }
                    };

                    if(!sNombreParam)
                    {
                        return retorno.toda();
                    }
                    else
                        {
                            return retorno.parametro(sNombreParam);
                        }
                }
        },
        utf8: {
	// public method for url encoding
	encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	},

	// public method for url decoding
	decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;

		while ( i < utftext.length ) {

			c = utftext.charCodeAt(i);

			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}

		}

		return string;
	}

        },

        forms: {
            procesar: function(config)
            {
                var con = jQuery.extend({   configLoader: { datosAdicionales:   { } },
                                            fnAlerta: function(msg){
                                                Webar.alert(Webar.html.create('p', msg));
                                            }
                                        }, config);

                //comportamiento del boton enviar
                $(con.idBoton).click(function() {
                    $(con.idForm).submit();
                });

                var validacionDelForm= function(){
                    Webar.ui.setLoader(con.idLoaderContainer, con.configLoader);

                    if(!$(con.idForm).validar(con.validacion))
                    {
                        Webar.ui.removeLoader(con.idLoaderContainer);
                        con.fnAlerta(con.validacion.message);
                        return false;
                    }
                    else
                        return true;
                }

                var callBackRespuesta= function(responseText, statusText){
                    Webar.ui.removeLoader(con.idLoaderContainer);

                    if(statusText== 'success')
                    {
                        try
                        {
                            var jsonResp= jQuery.parseJSON(responseText);
                            con.manejarRespuesta(jsonResp);
                        }
                        catch(err)
                        {
                            con.fnAlerta("Error de conexi�n");
                        }
                    }
                    else
                        {
                            con.fnAlerta("Error de conexi�n");
                        }
                }

                //configuramos el form
                var frmOptions = {  forceSync: true,
                                    beforeSubmit: validacionDelForm,
                                    success: callBackRespuesta,
                                    url: con.url,
                                    type: 'post',
                                    data: con.datosAdicionales
                                };
                $(con.idForm).ajaxForm(frmOptions);
            },
            soloNumeros: function(sIdInput)
            {
                $('#'+ sIdInput).keypress(  
                    function(event)
                    {
                        var charCode = ((event.which) ? event.which : event.keyCode);
                        if (charCode > 31 && (charCode < 48 || charCode > 57))
                        {
                            return false;
                        }
                        else
                            {
                                return true;
                            }
                    }
                );
            },
            llenarCombosFecha: function(cmbDia,cmbMes,cmbAnio)
            {
                            $(cmbDia).append("<option selected value=''>--</option>");
                            $(cmbMes).append("<option selected value=''>--</option>");
                            $(cmbAnio).append("<option selected value=''>--</option>");
                            //dia
                            for(i=1;i<=31;i++)
                            {
                                    $(cmbDia).append(
                                            $('<option></option>').val(Webar.data.strPad(i,2,'0','STR_PAD_LEFT')).html(i)
                                    );
                            }
                            //mes
                            for(i=1;i<=12;i++)
                            {
                                    $(cmbMes).append(
                                            $('<option></option>').val(Webar.data.strPad(i,2,'0','STR_PAD_LEFT')).html(i)
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


            },
             cargarCombo: function(objeto, valores, urlDestino, valorSeleccionado){
                    $.ajax({
                            url: urlDestino,
                            data: valores,
                            dataType: "json",
                            success: function(respuesta){
                                    $(objeto +' option').remove();
                                    //console.log(respuesta);
                                    $.each(respuesta, function(key, val) {
                                        $(objeto).append("<option value='"+val[0]+"'>"+val[1]+"</option>");
                                    });
                                    $('select'+objeto+' option[value='+valorSeleccionado+']').attr("selected","selected");

                            }
                    });
            }
        },
        data:
        {
            str:
            { luky: function (array, separador){
                        var response = "";
                        var sep      = separador ? separador : ',';


                        $.each(array,
                                    function(i, l)
                                    {
                                        if (sep.indexOf("@") > -1)
                                            response += sep.replace('@',l);
                                        else
                                            response += l + sep;
                                    }
                        );
                        return response;
                    },
                    fromArray: function(arrEstringuis){
                        var sRetorno= '';

                        if(Webar.data.isArray(arrEstringuis))
                        {
                            $.each(arrEstringuis, function(index){
                                sRetorno+= arrEstringuis[index]+ '\n';
                            });
                        }
                        else
                            {
                                sRetorno= arrEstringuis;
                            }

                        return sRetorno;
                    }
            },
            /* [dami] le queria poner boolean pero es una palabra reservada */
            bool:   {   parse: function(sValor){
                        if(sValor=== 'true'|| sValor=== true|| parseInt(sValor)> 0)
                        {
                            return true;
                        }
                        else
                            {
                                return false;
                            }
                    }},
            isArray: function(valor)
            {
                if (valor.constructor.toString().indexOf("Array") == -1)
                {
                  return false;
                }
                else
                {
                  return true;
                }
            },
            vacio: function(dato)
            {
                return (dato=== "");
            },
            strPad: function (input, pad_length, pad_string, pad_type)
            {
                var half = '', pad_to_go;

                var str_pad_repeater = function (s, len) {
                    var collect = '', i;

                    while (collect.length < len) {collect += s;}
                    collect = collect.substr(0,len);

                    return collect;
                };

                input += '';
                pad_string = pad_string !== undefined ? pad_string : ' ';

                if (pad_type != 'STR_PAD_LEFT' && pad_type != 'STR_PAD_RIGHT' && pad_type != 'STR_PAD_BOTH') {pad_type = 'STR_PAD_RIGHT';}
                if ((pad_to_go = pad_length - input.length) > 0) {
                    if (pad_type == 'STR_PAD_LEFT') {input = str_pad_repeater(pad_string, pad_to_go) + input;}
                    else if (pad_type == 'STR_PAD_RIGHT') {input = input + str_pad_repeater(pad_string, pad_to_go);}
                    else if (pad_type == 'STR_PAD_BOTH') {
                        half = str_pad_repeater(pad_string, Math.ceil(pad_to_go/2));
                        input = half + input + half;
                        input = input.substr(0, pad_length);
                    }
                }
                return input;
            },
            recortar: function(sTexto, iLargo)
            {
                var sRetorno = sTexto;
                if (sTexto.length > iLargo)
                {
                    sRetorno = sTexto.substring(0, iLargo - 3) + "...";
                }
                return sRetorno;
            }
        },
        html:
        {
            create: function(sTag, valor)
            {
                var retorno = "";

                if(Webar.data.isArray(valor))
                {
                    $.each(valor, function(i, l){
                        retorno += '<'+ sTag+'>' + l + '</'+ sTag+'>';
                    });
                }
                else
                    {
                        retorno += '<'+ sTag+'>' + valor + '</'+ sTag+'>';
                    }

                return retorno;
            },
            incluir: function (filename, filetype){
             
                 var fileref;
                 if (filetype=="js"){ //if filename is a external JavaScript file
                  fileref=document.createElement('script')
                  fileref.setAttribute("type","text/javascript")
                  fileref.setAttribute("src", filename)
                 }
                 else if (filetype=="css"){ //if filename is an external CSS file
                  fileref = document.createElement("link")
                  fileref.setAttribute("rel", "stylesheet")
                  fileref.setAttribute("type", "text/css")
                  fileref.setAttribute("href", filename)
                 }
                 if (typeof fileref!="undefined")
                  document.getElementsByTagName("head")[0].appendChild(fileref)
            }
        },
        ajax:{  manejar: function(opciones)
                {
                    var opt = $.extend( {   fnSuccess: function(data){
                                                //se supone que esto se sobreescrive
                                            },
                                            fnError: function(data){
                                                if(data.mensaje)
                                                {
                                                    this.fnAlerta(  data.mensaje,
                                                                    {   destino: data.destino }
                                                    );
                                                    this.fnOnError();
                                                }
                                                else
                                                    {
                                                        if(data.destino)
                                                        {
                                                            Webar.redirect(data.destino);
                                                        }
                                                        else
                                                            {
                                                                this.fnAlerta('Error de conexi�n');
                                                            }
                                                    }
                                            },
                                            fnAlerta: function(msj, conf)
                                            {
                                                Webar.alert(msj, conf);
                                            },
                                            fnOnError: function()
                                            {

                                            },
                                            fnFinaly:function(){
                                                
                                            }
                                        }, opciones);
                          
                    try
                    {
                        if(opt.data.estado> 0)
                        {
                            opt.fnSuccess(opt.data);
                        }
                        else
                            {
                                opt.fnError(opt.data);
                            }

                        opt.fnFinaly();
                    }
                    catch(err)
                    {
                        Webar.alert('Sucedi� un error inesperado');
                        if(console){console.log(err);}
                    }                       
                },
                cargarContenido: function ( opciones )
                {
                    $.ajax({
                       url: opciones.ruta,
                       data: (opciones.datos ? opciones.datos : null),
                       success: function (html){
                           $('#' + opciones.contenedor).html(html);
                           if (opciones.alFinalizar)
                                   opciones.alFinalizar();
                       }
                     });
                }
            },

        ui: {
            habilitarLink: function(sSearch, fnAccion){
                $(sSearch).removeClass('ui-state-disabled');
                $(sSearch).unbind("click");
                if(fnAccion)
                {
                    $(sSearch).click(fnAccion);
                }
            },
            deshabilitarLink: function(sSearch){
                $(sSearch).addClass('ui-state-disabled');
                $(sSearch).unbind("click");
                $(sSearch).click( function(e){
                    e.preventDefault();
                });
            },
            setLoader: function(sSearch, opciones)
            {
                var opt = jQuery.extend( {sImgPath: './programacion/core/img/ajax-loader.gif'}, opciones);

                var sTmpId= $(sSearch).attr('id')+ '_Webar_ajax_loader';

                $(sSearch).before('<div class="Webar-ajax-loader" id="'+ sTmpId +'"'+
                    'style="'+ opt.sWrapperStyle +'"><img src="'+ opt.sImgPath+'"'+
                    (!opt.sImageStyle? '': 'style="'+ opt.sImageStyle+ '"')+
                    '" /></img></div>');

                $('#'+ sTmpId).height($(sSearch).height());

                Webar.ui.centrar('#'+ sTmpId+ ' img');
                Webar.ui.ocultar(sSearch);
            },
            removeLoader: function(sSearch)
            {
                var sTmpId= $(sSearch).attr('id')+ '_Webar_ajax_loader';

                Webar.ui.mostrar(sSearch);
                $('#'+ sTmpId).remove();
            },
            ocultar: function(nodo)
            {
                $(nodo).addClass("ui-helper-hidden");
                $(".ui-helper-hidden").hide();
            },
            mostrar: function(nodo)
            {
                $(nodo).removeClass("ui-helper-hidden").show();
            },
            centrar: function(sSelector)
            {
                $(sSelector).css('position', 'absolute').css('top','50%').css('left','50%').css('margin-top','-'+($(sSelector).height()/2+ 'px')).css('margin-left','-'+($(sSelector).width()/2+ 'px')).parent().css('position', 'relative').css('text-align', 'center');
            },
            setLoaderFB: function (pParam)
            {
                var opt = jQuery.extend({
                        image: './prog/frm/lib/facebox/src/loading.gif'
                }, pParam);
                
                jQuery.facebox(opt, 'my-groovy-style');
            },
            removeLoaderFB: function()
            {
                jQuery(document).trigger('close.facebox');
            }

        }
    };
}();