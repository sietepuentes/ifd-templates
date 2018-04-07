function isEmpty(obj)
{
    for(var i in obj)
    {
        return false;
    }
    return true;
}

function isArray(obj)
{
    if (obj.constructor.toString().indexOf("Array") == -1)
        return false;
    else
        return true;
}


function trace(msg)
{
//    if(console)
//        console.debug(msg);
}
function debug(obj)
{
//    if(console)
//        console.log(obj);
}

var FBMetodo      = {
    /*USUARIOS*/
    USUARIO_GET_INFO                    : 'usuario.getInfo',
    USUARIO_GET_AMIGOS                  : 'usuario.getAmigos',
    USUARIO_GET_ALBUMS                  : 'usuario.getAlbums',
    USUARIO_GET_LIKES                   : 'usuario.getLikes',
    USUARIO_COMPARTIR                   : 'usuario.compartir',
    USUARIO_AGREGAR_AMIGO               : 'usuario.agregarAmigo',
    USUARIO_PUBLICAR                    : 'Usuario_publicar',
    USUARIO_POST_FEED                   : 'Usuario_postearFeed',
    USUARIO_GET_PERMISOS                : 'usuario.getPermisos',
    USUARIO_GET_FEED                    : 'Usuario.getFeed',
    USUARIO_ELIMINAR_FEED               : 'Usuario.eliminarFeed',
    USUARIO_GET_MSJ_RECIBIDOS           : 'Usuario.getMensajesRecibidos',
    USUARIO_ME_GUSTA                    : 'Usuario.meGusta',
    USUARIO_COMENTAR                    : 'Usuario.comentar',
    USUARIO_SET_ESTADO                  : 'Usuario.setEstado',

    /*ALBUMS*/
    ALBUM_GET_INFO                      : 'album.getInfo',
    ALBUM_CREAR_NUEVO                   : 'album.crearNuevo',
    ALBUM_GET_FOTOS                     : 'album.getFotos',

    /*FOTOS*/
    FOTO_GET_INFO                       : 'foto.getInfo',
    FOTO_SUBIR_FOTO                     : 'foto.subir',

    /*PAGES*/
    PAGE_GET_FEED                       : 'Page.getFeed'
}

var FBCampos       = {
    ALBUM       : 'aid,owner,cover_pid,name,created,modified,description,location,size,link,visible,modified_major,type,object_id',
    USUARIO     : 'uid,first_name,middle_name,last_name,name,pic_small,pic_big,pic_square,pic,affiliations,profile_update_time,timezone,religion,birthday,birthday_date,sex,hometown_location,meeting_sex,meeting_for',
    FOTO        : 'pid,aid,owner,src_small,src_small_height,src_small_width,src_big,src_big_height,src_big_width,src,src_height,src_width,link,caption,created,modified,object_id',
    STREAM      : 'post_id,source_id,updated_time,created_time,filter_key,attribution,actor_id,target_id,message,app_data,action_links,attachment,comments,likes,privacy,type,permalink',
    PERMISOS    : 'user_about_me,user_activities,user_birthday,user_education_history,user_events,user_groups,user_hometown,user_interests,user_likes,user_location,user_notes,user_online_presence,user_photo_video_tags,user_photos,user_relationships,user_relationship_details,user_religion_politics,user_status,user_videos,user_website,user_work_history,email,read_friendlists,read_insights,read_mailbox,read_requests,read_stream,xmpp_login,ads_management,user_checkins,publish_stream,create_event,rsvp_event,sms,offline_access,manage_pages'
}

function armarSalida  (response,metodo)
{
    var respuesta;

    metodo = (metodo != null ? metodo : '');
        
    switch (metodo){
        case FBMetodo.USUARIO_AGREGAR_AMIGO:
            respuesta   =   {
                estado      : response && !response.error && !response.error_code && response.action ? 1 : 0,
                datos       : null,
                error       : response && (response.error || response.error_code) ? (response.error ? response.error : response.error_msg) : null
            };
        break;
        case FBMetodo.USUARIO_COMPARTIR:
            respuesta   =   {
                estado      : response != null && !response.error && !response.error_code ? 1 : 0,
                datos       : null,
                error       : response && (response.error || response.error_code) ? (response.error ? response.error : response.error_msg) : null

            };
        break;
        case FBMetodo.USUARIO_SET_ESTADO:
            respuesta   =   {
                estado      : response != null && !response.error && !response.error_code ? 1 : 0,
                datos       : null,
                error       : response && (response.error || response.error_code) ? (response.error?response.error:response.error_msg) : null
            };
        break;
        case FBMetodo.USUARIO_ELIMINAR_FEED:
            respuesta   =   {
                estado      : response != null && response.error == null && !response.error_code ? 1 : 0,
                datos       : null,
                error       : response && response.error ? response.error : null
            };
        break;
        case FBMetodo.USUARIO_ME_GUSTA:
            respuesta   =   {
                estado      : response != null && response.error == null && !response.error_code ? 1 : 0,
                datos       : null,
                error       : response && response.error ? response.error : null
            };
        break;
        case FBMetodo.USUARIO_COMENTAR:
            respuesta   =   {
                estado      : response != null && response.error == null && !response.error_code ? 1 : 0,
                datos       : null,
                error       : response && response.error ? response.error : null
            };
        break;
        case FBMetodo.USUARIO_GET_PERMISOS:
            respuesta   =   {
                estado      : response && !response.error && !isEmpty(response) ? 1 : 0,
                datos       : response  ? response[0] : null,
                error       : response && response.error ? response.error : null
            };
        break;
        case FBMetodo.ALBUM_CREAR_NUEVO:
            respuesta   =   {
                estado      : response && response.id ? 1 : 0,
                datos       : response && !response.error ? response : null,
                error       : response && response.error ? response.error : null
            };
        break;
        case FBMetodo.FOTO_SUBIR_FOTO:
            respuesta   =   {
                estado      : response && response.data ? 1 : 0,
                datos       : response && response.data ? response.data : null,
                error       : response && response.error ? response.error : null
            };
        break;
        default:
            respuesta   =   {
                estado      : response && !response.error && !isEmpty(response) ? 1 : 0,
                datos       : response && response.data ? response.data : response,
                error       : response && response.error ? response.error : null
            };
        break;
    }
    
    return respuesta;
}


    WFB = {
        /*PROPIEDADES PRIVADA*/
        _app    : null,
        _session : null,

        /*CONSTANTES*/


        /*METODOS Y FUNCIONES*/
        getApp: function (){
            
            return _app;
        },
        getSession: function () {
            
            return _session;
        },
        setApp: function(param){
            
            _app = jQuery.extend({
                                    id          : null,
                                    key         : null,
                                    secret      : null,
                                    nombre      : '',
                                    permisos    : ''
                                 }, param);
        },
        setSession: function(param){
            
            _session = jQuery.extend({
                                    id         : null,
                                    key         : null,
                                    token       : null,
                                    estado      : '',
                                    permisos    : null
                                 }, param);
        },

        init: function (param){
            
            _app = jQuery.extend({
                                    id          : null,
                                    key         : null,
                                    secret      : null,
                                    nombre      : '',
                                    permisos    : '',
                                    canal       : ''
                                 }, param);

            FB.init({appId: _app.id, status: true, cookie: true, xfbml: true, channelUrl : _app.canal});
            return this;
        },
        manejoSession: function (response){
            
           if (response.session != null){
               WFB.setSession({
                   id       : response.session.uid,
                   key      : response.session.session_key,
                   token    : response.session.access_token,
                   estado   : response.status,
                   permisos : (response.perms != null) ? response.perms : null
               });
           }else{
               WFB.setSession({
                   estado   : response.status
               });
           }
        },
        getEstadoLogin: function (param){
            
            FB.getLoginStatus(function (response) {
                WFB.manejoSession(response);
                if (param.alFinalizar != null && param.alFinalizar != '') param.alFinalizar(WFB.getSession());
            });
        },
        login: function (param){
            
            FB.login(function(response)
            {
                WFB.manejoSession(response);
                if (param.alFinalizar != null && param.alFinalizar != '') param.alFinalizar(WFB.getSession());
            },{perms: WFB.getApp().permisos.join(',')});
        },
        logout: function (param){
            
            FB.logout(function(response){
                WFB.manejoSession(response);
                if (param.alFinalizar != null && param.alFinalizar != '') param.alFinalizar(WFB.getSession());
            });
        },
        llamada: function(opciones){
            var sQuery  = '';
            _opt        = jQuery.extend({
                                metodo      :   null,
                                parametros  :   null,
                                alFinalizar :   null,
                                traeCampos  : function (){
                                    return this.parametros != null && this.parametros.campos != null && this.parametros.campos != '' ;
                                },
                                traeDatos   : function(){
                                    return this.parametros != null && this.parametros.datos != null;
                                }
                           }, opciones);

            
            switch (_opt.metodo){
                /*
                 ***************************************************************************************
                 * USUARIO
                 ***************************************************************************************
                 */
                case FBMetodo.USUARIO_GET_INFO:
                        sQuery =    "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.USUARIO);
                        sQuery +=   " from user where uid= " + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : 'me()');
                        

                        FB.api  ({
                                    method: 'fql.query',
                                    query: sQuery
                        },function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response));});
                break;

                case FBMetodo.USUARIO_GET_AMIGOS:
                        sQuery =    "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.USUARIO);
                        sQuery +=   " from user where uid in (select uid2 from friend where uid1 = " + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : 'me()') + ")";
                        

                        FB.api  ({
                                    method: 'fql.query',
                                    query: sQuery
                        },function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response));});

                break;
                case FBMetodo.USUARIO_GET_ALBUMS:

                        sQuery  =   "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.ALBUM);
                        sQuery  +=  " from album where owner  = " + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id)

                        if (_opt.parametros != null && _opt.parametros.aid)
                            sQuery  += ' and aid =\'' + _opt.parametros.aid + '\'';
                        if (_opt.parametros != null && _opt.parametros.nombre)
                            sQuery  += ' and name =\'' + _opt.parametros.nombre + '\'';


                        
                        FB.api  ({
                                    method: 'fql.query',
                                    query: sQuery
                        },function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response));});

                break;
                case FBMetodo.USUARIO_GET_MSJ_RECIBIDOS:
                        sQuery  = "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.STREAM);
                        sQuery += " from stream where source_id = " + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + " AND source_id = target_id AND message != \"\" ";
                        if (_opt.parametros != null && _opt.parametros.uidAmigo)
                            sQuery  += ' and actor_id = ' + _opt.parametros.uidAmigo;

                        if (_opt.parametros != null && _opt.parametros.fechaDesde)
                            sQuery  += ' and created_time >= ' + _opt.parametros.fechaDesde;

                        if (_opt.parametros != null && _opt.parametros.fechaHasta)
                            sQuery  += ' and created_time <= ' + _opt.parametros.fechaHasta;


                        FB.api  ({
                                    method: 'fql.query',
                                    query: sQuery
                        },function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response));});

                break;
                case FBMetodo.USUARIO_GET_LIKES:
                        FB.api  (
                                    '/' + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + '/likes?access_token=' + WFB.getSession().token
                        ,function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response));});
                break;
                case FBMetodo.USUARIO_PUBLICAR:
                        var publish = null;

                            publish = {
                                method                : 'stream.publish',
                                display               : _opt.parametros.display,
                                message               : _opt.parametros.message,
                                attachment            : _opt.parametros.attachment,
                                action_links          : _opt.parametros.action_links,
                                target_id             : _opt.parametros.target_id
                            };

                            FB.ui(
                                    publish
                            ,function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo));});

                break;
                case FBMetodo.USUARIO_GET_FEED:
                        
                        FB.api  (
                                    '/' + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + '/feed?access_token=' + WFB.getSession().token
                        ,function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response));});
                break;
                case FBMetodo.USUARIO_ELIMINAR_FEED:
                        FB.api  (
                                    _opt.parametros.id
                                    ,'delete'
                        ,function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo));});
                break;
                case FBMetodo.USUARIO_POST_FEED:
                        FB.api  (
                                    '/' + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + '/feed?access_token=' + WFB.getSession().token
                                    ,'post'
                                    ,{
                                        message: _opt.parametros.mensaje,
                                        picture: _opt.parametros.imagen,
                                        link: _opt.parametros.link,
                                        name: _opt.parametros.nombre,
                                        caption: _opt.parametros.subtitulo,
                                        description: _opt.parametros.descripcion,
                                        source: _opt.parametros.fuente,
                                        actions: _opt.parametros.links

                                    }
                        ,function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo));});
                break;
                case FBMetodo.USUARIO_AGREGAR_AMIGO:
                        var add = {
                            method: 'friends.add',
                            id: _opt.parametros.uid
                        }
                        FB.ui(
                            add
                        ,function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo));});
                break;
                case FBMetodo.USUARIO_COMPARTIR:
                        var share = {
                          method                : 'stream.share',
                          u                     : _opt.parametros.url
                        };

                        FB.ui(
                            share
                        ,function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo));});
                break;
                case FBMetodo.USUARIO_GET_PERMISOS:
                        sQuery =    "select " + (_opt.parametros != null && _opt.parametros.permisos ? _opt.parametros.permisos : FBCampos.PERMISOS);
                        sQuery +=   " from permissions where uid = me()";

                        FB.api  ({
                                    method: 'fql.query',
                                    query: sQuery
                        },function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo));});
                break;
                case FBMetodo.USUARIO_ME_GUSTA:
                        FB.api  (
                                    '/' + _opt.parametros.oid + '/likes?access_token=' + WFB.getSession().token
                                    ,'post'
                        ,function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo));});
                break;
                case FBMetodo.USUARIO_COMENTAR:
                        FB.api  (
                                    '/' + _opt.parametros.oid + '/comments?access_token=' + WFB.getSession().token
                                    ,'post'
                                    ,{message: _opt.parametros.comentario}
                        ,function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo));});
                break;
                case FBMetodo.USUARIO_SET_ESTADO:
                        FB.api  ({
                                    method: 'status.set',
                                    status: _opt.parametros.estado
                        },function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo));});
                break;
                /*
                 ***************************************************************************************
                 * ALBUM
                 ***************************************************************************************
                 */
                case FBMetodo.ALBUM_GET_FOTOS:
                        sQuery  =   "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.FOTO);
                        sQuery  +=  " from photo where 1=1 ";

                        if (_opt.parametros != null && _opt.parametros.aid)
                            sQuery  += ' and aid =\'' + _opt.parametros.aid + '\'';
                        if (_opt.parametros != null && _opt.parametros.pid)
                            sQuery  += ' and pid =\'' + _opt.parametros.pid + '\'';


                        FB.api  ({
                                    method: 'fql.query',
                                    query: sQuery
                        },function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo));});
                break;
                case FBMetodo.ALBUM_GET_INFO:
                        sQuery  =   "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.ALBUM);
                        sQuery  +=  " from album where 1=1 ";

                        if (_opt.parametros != null && _opt.parametros.aid)
                            sQuery  += ' and aid =\'' + _opt.parametros.aid + '\'';
                        if (_opt.parametros != null && _opt.parametros.uid)
                            sQuery  += ' and owner =\'' + _opt.parametros.uid + '\'';


                        FB.api  ({
                                    method: 'fql.query',
                                    query: sQuery
                        },function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo));});
                break;
                case FBMetodo.ALBUM_CREAR_NUEVO:

                        FB.api(
                            '/' + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + '/albums?access_token=' + WFB.getSession().token,
                            'post',
                            {
                                name    :   _opt.parametros.nombre,
                                message :   (_opt.parametros != null && _opt.parametros.mensaje != null ? _opt.parametros.mensaje : '')
                            }
                        ,function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo));});
                break;
                /*
                 ***************************************************************************************
                 * FOTO
                 ***************************************************************************************
                 */
                case FBMetodo.FOTO_GET_INFO:
                        sQuery  =   "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.FOTO);
                        sQuery  +=  " from photo where 1=1 ";

                        if (_opt.parametros != null && _opt.parametros.aid)
                            sQuery  += ' and aid =\'' + _opt.parametros.aid + '\'';
                        if (_opt.parametros != null && _opt.parametros.pid)
                            sQuery  += ' and pid =\'' + _opt.parametros.pid + '\'';

                        FB.api  ({
                                    method: 'fql.query',
                                    query: sQuery
                        },function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo));});
                break;
                case FBMetodo.FOTO_SUBIR_FOTO:

                        $.getJSON(  "ajax/upload.php",  {
                                                            'token'     : WFB.getSession().token,
                                                            'archivo'   : _opt.parametros.archivo,
                                                            'titulo'    : (_opt.parametros != null && _opt.parametros.titulo) ? _opt.parametros.titulo : '',
                                                            'aid'       : (_opt.parametros != null && _opt.parametros.aid) ? _opt.parametros.aid : 'me'
                                                        }
                        ,function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo));});

                break;
                /*
                 ***************************************************************************************
                 * PAGE
                 ***************************************************************************************
                 */
                case FBMetodo.PAGE_GET_FEED:
                        FB.api  (
                                    '/' + _opt.parametros.id + '/feed?access_token=' + WFB.getSession().token
                        ,function (response){if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response));});
                break;

                default:
                    //alert('No mandaste nada.');/

                break;

            }




        }
}



