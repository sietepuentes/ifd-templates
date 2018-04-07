var path_marca_logo = "../../../images/logo/";
var path_eventos = "../../../images/eventos/";
var path_listas = "../../../listas/";

Jota= function() {
    return {
        alert: function(texto, opciones) {
            var opt = jQuery.extend({title: 'Error',
                                        width: '325px',
                                        modal: true,
                                        resizable: false,
                                        buttons: {"Aceptar": function() {
                                                $(this).dialog("close");}
                                        },
                                        dialogClass: 'dialogo'
                                    }, opciones);

            try {
                //[dami]antes usabamos !$(texto).is('p') pero pincha para el
                //re carajo con los stack trace de las excepciones
                //if(!$(texto).is('p'))
                if(texto.substring(0, 3)!== "<p>") {
                    texto= Jota.html.create('p', texto);
                }
            } catch(err) {
                //[dami]esto parece una negrada pero funca de diez
                texto= Jota.html.create('p', texto);
            }

			if(opt.fnCallBack){
				$(Jota.html.create('div', texto)).dialog(opt).bind( "dialogclose", function(event, ui) {
						opt.fnCallBack();
				});
			} else {
				if(opciones&& opciones.destino) {
					$(Jota.html.create('div', texto)).dialog(opt).bind( "dialogclose", function(event, ui) {
							Jota.redirect(opciones.destino);
						});
				} else {
						$(Jota.html.create('div', texto)).dialog(opt);
					}
			}

        },
        confirm: function(texto, fnAceptar){
            Jota.alert(texto, {
                buttons: {"Aceptar": fnAceptar,
                            "Cancelar": function() {
                                $(this).dialog("close");
                            }
                        }
                    }
            );
        },
        redirect: function(ruta, stayIfEmpty) {
            if (ruta=== null){
                ruta = "";
            }

            if (ruta === "" && stayIfEmpty){
                return;
            }
            window.location= ruta;
        },
        setCookie: function(arrCookies, opciones) {
            var options = {expires: 1};
            var dateCookie = new Date();
            dateCookie.setTime(dateCookie.getTime() + (120 * 60 * 1000));
            $(arrCookies).each(function(index){
                $.cookie(arrCookies[index].clave, arrCookies[index].valor, { expires: dateCookie });
            });
        },
        console: {
            log: function(algo){
                if(console){
                    if(!$.browser.msie){
                        console.log(algo);
                    } else {
                        if(algo.constructor.toString()=== 'TypeError'){
                            console.log(    'description: '+algo.description+ '\n'+
                                            'message: '+ algo.message+ '\n'+
                                            'name: '+ algo.name+ '\n'+
                                            'number: '+ algo.number
                            );
                        } else {
                            console.log(algo);
                        }
                    }
                }
            }
        },
        url: {
                entera: function(){
                    return location.href;
                },
                encode: function(sDato) {
                    return encodeURIComponent(sDato);
                },
                decode: function(sDato) {
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
                    if(retorno.length> 1) {
                        return retorno[retorno.length-1];
                    } else {
                            return '';
                        }
                },
                raiz: function(){
                  return Jota.url.entera().replace(Jota.url.relativa(), '');
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
                            while ((e = r.exec(q))) {
                                retorno[d(e[1])] = d(e[2]);
                            }
                            return retorno[sNombre];
                        }
                    };

                    if(!sNombreParam) {
                        return retorno.toda();
                    } else {
                            return retorno.parametro(sNombreParam);
                        }
                }
        },
        forms: {
            procesar: function(config) {
                var con = jQuery.extend({configLoader: {datosAdicionales:   { }},
                                            fnAlerta: function(msg){
                                                //Jota, pongo decode porque la pagina esta en ISO
                                                //Jota.alert(Utf8.decode(Jota.html.create('p', msg)));
                                                Jota.alert(Jota.html.create('p', msg));
                                            }
                                        }, config);

                //comportamiento del boton enviar
                $(con.idBoton).click(function() {
                    $(con.idForm).submit();
                });

				if(!con.idLoaderContainer) {
					con.idLoaderContainer= con.idBoton;
				}

                var validacionDelForm= function(){
                    Jota.ui.setLoader(con.idLoaderContainer, con.configLoader);

                    if(!$(con.idForm).validar(con.validacion)) {
                        Jota.ui.removeLoader(con.idLoaderContainer);
                        con.fnAlerta(con.validacion.message);
                        return false;
                    } else {
                        return true;
                    }
                };

                var callBackRespuesta= function(responseText, statusText){
                    Jota.ui.removeLoader(con.idLoaderContainer);

                    if(statusText=== 'success') {
                        try {
                            var jsonResp= jQuery.parseJSON(responseText);
                            con.manejarRespuesta(jsonResp);
                        } catch(err) {
                            con.fnAlerta("Error de conexión");
                        }
                    } else {
                            con.fnAlerta("Error de conexión");
                        }
                };

                //configuramos el form
                var frmOptions = {forceSync: true,
                                    beforeSubmit: validacionDelForm,
                                    success: callBackRespuesta,
                                    url: con.url,
                                    type: 'post',
                                    data: con.datosAdicionales
                                };
                $(con.idForm).ajaxForm(frmOptions);
            },
            soloNumeros: function(sSearchStr)
            {
                $(sSearchStr).keypress(
                    function(event)
                    {
                        var charCode = ((event.which) ? event.which : event.keyCode);
                        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                            return false;
                        } else {
                                return true;
                            }
                    }
                );
            },
            llenarCombosFecha: function(cmbDia,cmbMes,cmbAnio)
            {
                            $(cmbDia).append("<option value=''>dia</option>");
                            $(cmbMes).append("<option value=''>mes</option>");
                            $(cmbAnio).append("<option value=''>año</option>");
                            //dia
                            for(i=1;i<=31;i++) {
                                    $(cmbDia).append(
                                            $('<option></option>').val(Jota.data.strPad(i,2,'0','STR_PAD_LEFT')).html(i)
                                    );
                            }
                            //mes
                            for(i=1;i<=12;i++) {
                                    $(cmbMes).append(
                                            $('<option></option>').val(Jota.data.strPad(i,2,'0','STR_PAD_LEFT')).html(i)
                                    );
                            }
                            //anio
                            var fecha= new Date();
                            var anio= fecha.getFullYear();
                            for(i=anio;i>=1900;i--) {
                                $(cmbAnio).append(
                                    $('<option></option>').val(i).html(i)
                                );
                            }
            },
            llenarCombosFechaSel: function(cmbDia,cmbMes,cmbAnio, diaSeleccionado, mesSeleccionado, anioSeleccionado)
            {
                            $(cmbDia).append("<option value=''>Día</option>");
                            $(cmbMes).append("<option value=''>Mes</option>");
                            $(cmbAnio).append("<option value=''>Año</option>");
                            //dia
                            for(i=1;i<=31;i++)
                            {
                                    $(cmbDia).append(
                                            $('<option></option>').val(Jota.data.strPad(i,2,'0','STR_PAD_LEFT')).html(i)
                                    );
                            }
                            $('select'+cmbDia+' option[value='+diaSeleccionado+']').attr("selected","selected");
                            //mes
                            for(i=1;i<=12;i++)
                            {
                                    $(cmbMes).append(
                                            $('<option></option>').val(Jota.data.strPad(i,2,'0','STR_PAD_LEFT')).html(i)
                                    );
                            }
                             $('select'+cmbMes+' option[value='+mesSeleccionado+']').attr("selected","selected");
                            //anio
                            var fecha 	= new Date();
                            var anio 	= fecha.getFullYear();
                            for(i=anio;i>=1900;i--)
                            {
                                    $(cmbAnio).append(
                                            $('<option></option>').val(i).html(i)
                                    );
                            }
                             $('select'+cmbAnio+' option[value='+anioSeleccionado+']').attr("selected","selected");
            },
             cargarCombo: function(objeto, valores, urlDestino, valorSeleccionado){
                    $.ajax({
                            url: urlDestino,
                            data: valores,
                            dataType: "json",
                            success: function(respuesta){
                                    $(objeto +' option').remove();
                                    $.each(respuesta, function(val, text) {
                                        $(objeto).append("<option value='"+val+"'>"+text+"</option>");
                                    });
                                    $('select'+objeto+' option[value='+valorSeleccionado+']').attr("selected","selected");

                            }
                    });
            },
             cargarComboMultiple: function(objeto, valores, urlDestino, valorSeleccionado){
                    $.ajax({
                            url: urlDestino,
                            data: valores,
                            dataType: "json",
                            success: function(respuesta){
                                $(objeto +' option').remove();
                                $.each(respuesta, function(val, text) {
                                    $(objeto).append("<option value='"+val+"'>"+text+"</option>");
                                });
                                arrayAux = valorSeleccionado.split(",");
                                for(i=0;i<arrayAux.length;i++)
                                {
                                    $('select'+objeto+' option[value='+arrayAux[i]+']').attr("selected","selected");
                                }
                            }
                    });
            }
        },
        data: {
            str: {
                luky: function (array, separador){
                        var response = "";
                        var sep      = separador ? separador : ',';


                        $.each(array,
                                    function(i, l)
                                    {
                                        if (sep.indexOf("@") > -1) {

                                            response += sep.replace('@',l);
                                        } else{
                                            response += l + sep;
                                        }
                                    }
                        );
                        return response;
                    },
                    fromArray: function(arrEstringuis){
                        var sRetorno= '';

                        if(Jota.data.isArray(arrEstringuis)) {
                            $.each(arrEstringuis, function(index){
                                sRetorno+= arrEstringuis[index]+ '\n';
                            });
                        } else {
                                sRetorno= arrEstringuis;
                            }

                        return sRetorno;
                    },
                    toHtml: function (str, is_xhtml){
                        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '' : '<br>';
                        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
                    }
            },
            /* [dami] le queria poner boolean pero es una palabra reservada */
            bool:   {parse: function(sValor){
                        if(sValor=== 'true'|| sValor=== true|| parseInt(sValor, 10)> 0) {
                            return true;
                        } else {
                                return false;
                            }
                    }},
            isArray: function(valor)
            {
                if (valor.constructor.toString().indexOf("Array") === -1) {
                  return false;
                } else {
                  return true;
                }
            },
            arrayToStr : function (array,separador)
             {
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
            vacio: function(dato)
            {
                return (!dato|| dato=== "");
            },
            isNull:function(dato, reemplazo){
                if(Jota.data.vacio(dato)) {
                    return reemplazo;
                } else {
                        return dato;
                    }
            },
            strPad: function (input, pad_length, pad_string, pad_type)
            {
                var half = '', pad_to_go;

                var str_pad_repeater = function (s, len) {
                    var collect = '';
                    var i;

                    while (collect.length < len) {
                        collect += s;
                    }
                    collect = collect.substr(0,len);

                    return collect;
                };

                input += '';
                pad_string = pad_string !== undefined ? pad_string : ' ';

                if (    pad_type !== 'STR_PAD_LEFT' &&
                        pad_type !== 'STR_PAD_RIGHT' &&
                        pad_type !== 'STR_PAD_BOTH'
                ) {
                        pad_type = 'STR_PAD_RIGHT';
                }
                if ((pad_to_go = pad_length - input.length) > 0) {
                    if (pad_type === 'STR_PAD_LEFT') {
                        input = str_pad_repeater(pad_string, pad_to_go) + input;
                    } else {
                        if (pad_type === 'STR_PAD_RIGHT') {
                            input = input + str_pad_repeater(pad_string, pad_to_go);
                        } else {
                            if (pad_type === 'STR_PAD_BOTH') {
                                half = str_pad_repeater(pad_string, Math.ceil(pad_to_go/2));
                                input = half + input + half;
                                input = input.substr(0, pad_length);
                            }
                        }
                    }
                }
                return input;
            },
            recortar: function(sTexto, iLargo) {
                if(!sTexto) {
                    sTexto= '';
                }
                
                var sRetorno = sTexto;
                if (sTexto.length > iLargo) {
                    sRetorno = sTexto.substring(0, iLargo - 3) + "...";
                }
                return sRetorno;
            }
        },
        html: {
            create: function(sTag, valor) {
                var retorno = "";

                if(Jota.data.isArray(valor)) {
                    $.each(valor, function(i, l){
                        retorno += '<'+ sTag+'>' + l + '</'+ sTag+'>';
                    });
                } else {
                        retorno += '<'+ sTag+'>' + valor + '</'+ sTag+'>';
                    }

                return retorno;
            }
        },
        ajax: {
            manejar: function(opciones){
                    var opt = $.extend( {fnSuccess: function(data){
                                                //se supone que esto se sobreescrive
                                            },
                                            fnError: function(data){
                                                if(data.mensaje) {
                                                    this.fnAlerta(  data.mensaje,
                                                                    {destino: data.destino}
                                                    );
                                                    this.fnOnError();
                                                } else {
                                                        if(data.destino) {
                                                            Jota.redirect(data.destino);
                                                        } else {
                                                                this.fnAlerta('Error de conexión');
                                                            }
                                                    }
                                            },
                                            fnAlerta: function(msj, conf)
                                            {
                                                Jota.alert(msj, conf);
                                            },
                                            fnOnError: function()
                                            {

                                            },
                                            fnFinaly:function(){
                                                
                                            }
                                        }, opciones);
                          
                    try {
                        if(opt.data.estado> 0) {
                            opt.fnSuccess(opt.data);
                        } else {
                                opt.fnError(opt.data);
                            }

                        opt.fnFinaly();
                    } catch(err) {
                        opt.fnAlerta('Sucedió un error inesperado');
                        Jota.console.log(err);
                    }
                },
            analizarUrl: function(navegacion){
                var direccion= Jota.url.id();

                if(direccion) {
                    var palabra;
                    var fnHandler;
                    var parametro;
                    palabra= direccion.split('.');
                    if(palabra.length> 1) {
                        $.each(navegacion.secciones, function(ind, seccion){
                            if(seccion.nombre=== palabra[0]) { //seccion

                                $.each(seccion.comandos, function(ind, subSeccion){
                                    if(subSeccion.nombre=== palabra[1]) {
                                        fnHandler= subSeccion.handler;
                                        if(palabra.length> 2) {
                                            parametro= palabra[2];
                                        }
                                        return false;
                                    }
                                });
                                return false;
                            }
                        });

                        if(fnHandler) {
                            fnHandler(parametro);
                        }
                } else {
                    navegacion.porDefault();
                }
            } else {
                navegacion.porDefault();
            }
        }
    },
        ui: {
            habilitarLink: function(sSearch, fnAccion){
                $(sSearch).removeClass('ui-state-disabled');
                $(sSearch).unbind("click");
                if(fnAccion) {
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
                var opt = jQuery.extend( {sImgPath: 'images/ajax-loader.png'}, opciones);

                var sTmpId= $(sSearch).attr('id')+ '_leao_ajax_loader';

                $(sSearch).before('<div class="leao-ajax-loader" id="'+ sTmpId +'"'+
                    'style="'+ opt.sWrapperStyle +'"><img src="'+ opt.sImgPath+'"'+
                    (!opt.sImageStyle? '': 'style="'+ opt.sImageStyle+ '"')+
                    '" /></img></div>');

                $('#'+ sTmpId).height($(sSearch).height());

                Jota.ui.centrar('#'+ sTmpId+ ' img');
                Jota.ui.ocultar(sSearch);
            },
            removeLoader: function(sSearch)
            {
                var sTmpId= $(sSearch).attr('id')+ '_leao_ajax_loader';

                Jota.ui.mostrar(sSearch);
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
            redimAncho: function(sSelector, iNuevoAncho){
                Jota.ui.redim(sSelector, 'ancho', iNuevoAncho);
            },
            redimAlto: function(sSelector, iNuevoAncho){
                Jota.ui.redim(sSelector, 'alto', iNuevoAncho);
            },
            encuadrar: function(sSelector, sCriterio){
                //esta funcion redimensiona un nodo segun el espacio de su contenedor
                var coso= $(sSelector);

                $.each(coso, function(indice, nodo){
                    Jota.ui.encuadrarNodo(nodo, sCriterio);
                    Jota.ui.centrarNodo(nodo);
                });
            },
            centrar2: function(sSelector){
                var coso= $(sSelector);
                $.each(coso, function(indice, nodo){
                    Jota.ui.centrarNodo(nodo);
                });
            },
            centrarNodo: function(nodo){
                var contenedor= $(nodo).parent();

                var ancho= $(nodo).width();
                var alto= $(nodo).height();
                var anchoCont= contenedor.width();
                var altoCont= contenedor.height();

                var bEsMasLargo= (ancho> anchoCont);
                var bEsMasCorto= (ancho< anchoCont);
                var bEsMasAlto= (alto> altoCont);
                var bEsMasBajo= (alto< altoCont);

                if(bEsMasCorto) {
                    contenedor.css('text-align', 'center');
                } else {
                    if(bEsMasBajo) {
                        $(nodo).css('margin-top', (altoCont/2- alto/2));
                    } else {
                        if(bEsMasLargo) {
                            $(nodo).css('position', 'relative');
                            $(nodo).css('left', '-'+ (ancho/2- anchoCont/2));
                        } else {

                            if(bEsMasAlto){

                                $(nodo).css('position', 'relative');
                                $(nodo).css('top', '-'+ (alto/2- altoCont/2));
                            }
                        }
                    }
                }
            },
            encuadrarNodo: function(nodo, sCriterio){
               var contenedor= $(nodo).parent();
               var bEsVertical= ($(nodo).width()< $(nodo).height());
               var bContenedorEsVertical= ($(contenedor).width()< $(contenedor).height());
               var bContenedorEsCuadrado= ($(contenedor).width()=== $(contenedor).height());

               //[dami]esto podria resumirse en menos ifes pero seria menos legible
               if(bContenedorEsCuadrado) {
                   if(!bEsVertical) {//horizontal
                        if(sCriterio=== 'ajustar') {
                            Jota.ui.redimAncho(nodo, contenedor.width());
                        } else {
                                Jota.ui.redimAlto(nodo, contenedor.height());
                            }
                   } else  {//vertical
                           if(sCriterio=== 'ajustar') {
                                Jota.ui.redimAlto(nodo, contenedor.height());
                            } else {
                                    Jota.ui.redimAncho(nodo, contenedor.width());
                                }
                       }
               } else {
                   if(bContenedorEsVertical)  { // contenedor vertical
                       if(!bEsVertical)  {//horizontal
                            if(sCriterio=== 'ajustar') {
                                Jota.ui.redimAncho(nodo, contenedor.width());
                            } else  {// completar
                                    Jota.ui.redimAlto(nodo, contenedor.height());
                                }
                       } else {//vertical
                               if(sCriterio=== 'ajustar') {
                                    Jota.ui.redimAncho(nodo, contenedor.width());
                                } else { // completar
                                        Jota.ui.redimAlto(nodo, contenedor.height());
                                    }
                           }
                   } else {
                       if(!bContenedorEsVertical) { // contenedor horizontal
                           if(!bEsVertical) { //horizontal
                                if(sCriterio=== 'ajustar') {
                                    Jota.ui.redimAlto(nodo, contenedor.height());
                                } else {// completar
                                        Jota.ui.redimAncho(nodo, contenedor.width());
                                    }
                           } else { //vertical
                                   if(sCriterio=== 'ajustar') {
                                        Jota.ui.redimAlto(nodo, contenedor.height());
                                    } else {// completar
                                            Jota.ui.redimAncho(nodo, contenedor.width());
                                        }
                               }
                       }
                   }
               }
            },
            redim: function (sSelector, sDimension, iNuevoValor){
                var coso= $(sSelector);
                var iAnchoOriginal= coso.width();
                var iAltoOriginal= coso.height();

                var iNuevoAncho;
                var iNuevoAlto;
                if(sDimension=== 'alto') {
                    iNuevoAlto= iNuevoValor;
                    //regla de tres simple
                    iNuevoAncho= iAnchoOriginal* iNuevoAlto/ iAltoOriginal;
                } else  {//ancho
                        iNuevoAncho= iNuevoValor;
                        //regla de tres simple
                        iNuevoAlto= iNuevoAncho* iAltoOriginal/ iAnchoOriginal;
                    }

                coso.width(iNuevoAncho);
                coso.height(iNuevoAlto);
            },
            /**
             * Este metodo es un wrapper para usar fancybox, toma automaticamente el ancho del contenido
             * cosa que resulta muy util. Tambien resuelve algunos dramas que le pasan al IE 7
             * y a los diseñadores les encanta =D
             */
            fancy: function(sSelector, opciones){

                var coso= $(sSelector);

                $('body').append('<iframe style="display:none;" frameborder="0" scrolling="false" src="'+
                    coso.attr('href')+
                    '" hspace="0" name="peron" id="tempFancyDami"></iframe>');

                var alto= $('#tempFancyDami').height();
                var ancho= $('#tempFancyDami').width();

                var opt = $.extend( {
                   'frameHeight': alto,
                    'frameWidth': ancho,
/*                    // 'transitionIn'	:	'elastic',
                    // 'transitionOut'	:	'elastic',
                    'showCloseButton': false,
                    'enableEscapeButton': true,
                    'hideOnContentClick': true,
                    'padding': '0',
                    'urlDestino': null*/
                    'type'			: 'iframe'
                }, opciones );


                $('#tempFancyDami').remove();

                //[dami]esto lo pongo porque el pto ie7 abre los fancys con ancho 0
                if($.browser.msie&& $.browser.version=== "7.0") {
                    //si es ie 7 le metemos un callback que le setee el alto y ancho a los fancys
                    var fnArreglarIe7= function(){
                        var nAlto= $('#fancybox-inner').children(':first-child').height();
                        var nAncho= $('#fancybox-inner').children(':first-child').width();
                        $('#fancybox-inner').width(nAncho).height(nAlto);
                        $('#fancybox-wrap').width(nAncho).height(nAlto);
                        $('#fancybox-outer').width(nAncho).height(nAlto);
                        Jota.ui.centrar('#fancybox-wrap');
                    };
                    if(opt.onComplete) {
                        var fn= opt.onComplete;
                        opt.onComplete= function(){
                            fnArreglarIe7();
                            fn();
                        };
                    } else {
                        opt.onComplete= fnArreglarIe7;
                    }
                }

                if(!opt.urlDestino) {
                    $.each(coso, function(i, e){
                        $(e).fancybox(opt);
                    });
                } else {
                    $('<a class="iframe" href="' + opt.urlDestino + '"></a>').fancybox(opt).click();
                }
            },
            popapi: function(sArchivo, opciones){
                var config = $.extend( {
                                                            type : 'ajax',
                                                            urlDestino : sArchivo,
                                                            hideOnContentClick:true
                                                    }, opciones);

                Jota.ui.fancy(null, config);
            }
        },
	net:{
	    twitter: {
		generarUrlShare: function(opt){
		    var sRetorno= 'http://twitter.com/share?';

		    var opciones = $.extend(	{texto: 'Texto texto texto texto',
						    url: 'www.urldelsitio.com'
						},
				    opt);

		    sRetorno+= 'url='+ Jota.url.encode(opciones.url)+
				'&text='+ Jota.url.encode(opciones.texto);

		    return sRetorno;
		}
	    },
	    facebook: {
            generarUrlShare: function(opt){
                var sRetorno='http://www.facebook.com/sharer.php?s=100&p[medium]=106&';

                var opciones = $.extend(	{titulo: 'Titulo del sitio',
                                bajada: 'Bajada bajada bajada bajada bajada bajada bajada.',
                                url: 'www.urldelsitio.com',
                                imagenes: []
                            },
                        opt);

                sRetorno+= 'p[title]='+ Jota.url.encode(opciones.titulo)+
                    '&p[summary]='+ Jota.url.encode(opciones.bajada)+
                    "&p[url]="+ Jota.url.encode(opciones.url);

                $.each(opciones.imagenes, function(ind, el){
    //			sRetorno+= '&p[images]['+ ind +']='+ Jota.url.encode(el);
                sRetorno+= '&p[images]['+ ind +']='+ el;
                });

                return sRetorno;
            }
	    }
	},
     ckeditor:{
         editorSimple: function(nameDiv, largo)
         {
             if(largo=="")
                 largo=60;
             /*CKEDITOR.replace( nameDiv,
                {
                        extraPlugins : 'uicolor',
                        toolbar :
                        [
                                ['Source', '-', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],
                                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']
                        ],
                        height: largo
                } );*/
            $(function(){
                var config = {
                    toolbar:
                            [
                                ['Source', '-', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],
                                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']
                            ],
                    height: largo
                };
                $(nameDiv).ckeditor(config);
            });
         }
     }
};
}();