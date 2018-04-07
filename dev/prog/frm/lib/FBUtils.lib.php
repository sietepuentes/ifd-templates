<?php



Jota::incluir(array('libs'      => 'Facebook',
                    'helpers'   => array('Encoding','Json')
            )
);


class NecesitaLoginFBException extends Exception {}


final class FBUtils {

	private $facebook;
	private $session;
        private static $nroObjeto = 0;
        private $usuario;

	//private $uid;
	//private $fbme;
        
        private static $instancia;

        // <editor-fold defaultstate="collapsed" desc="CONSTANTES">
        const GET_ALBUMS    = 'usuario.getAlbums';
        const CREAR_ALBUM   = 'crearAlbum';
        const GET_INFO      = 'usuario.getInfo';
        const GET_AMIGOS    = 'usuario.getAmigos';
        const ES_FAN        = 'usuario.esFan';
        const GET_FOTOS     = 'album.getFotos';
        const SUBIR_FOTO    = 'subirFoto';
        const POST_FEED     = 'postFeed';

        // </editor-fold>


	public function __construct(){

            self::$nroObjeto++;
            //echo "Nro Objeto: ".self::$nroObjeto."<br>";
            $this->facebook = self::getInstanciaFB();
            $this->session  = null;
            $this->usuario = null;
            
            

        }

        public static function getInstanciaFB($params=null){
            if (!isset(self::$instancia)) {
                //echo "No Esta seteado self::instancia<br>";
                self::$instancia = new Facebook(
                    array(
                      'appId'  => FB_APP_ID,
                      'secret' => FB_APP_SECRET,
                      'cookie' => (defined('BROWSER') &&  BROWSER == 'Safari' ? false : true)
                    )
                );


            }
            return self::$instancia;

        }

        public function conectar($verificarPermisos=false){

            try{

                $this->printHttpHeader();

                $salida['estado'] = 1;

                $this->session = $this->facebook->getSession();

                
                if (isset($this->session))
                {

                    $uid = $this->facebook->getUser();

                    if ($uid)
                    {
                        $llamada = $this->llamada(array(
                            "metodo" => FBUtils::GET_INFO,
                            "parametros" => array(
                                "uid" => $uid,
                                "campos" => "uid,name,first_name,last_name,pic,email"
                            )
                        ));

                        $this->setUsuario(Data::arrayToObject($llamada["datos"][0]));

                        if ($verificarPermisos && !$this->tienePermisosNecesarios())
                            $this->forzarLoginFB();

                        return $salida;


                    }
                    else
                        $this->forzarLoginFB();

                }
                else
                    $this->forzarLoginFB();

            }
            catch (FacebookApiException $e){
                //echo "Entro en FacebookApiException<br>";

                $err = $e->getResult();
                
                
                if (isset($err['error_code']) && $err['error_code'] == '190')
                    $this->forzarLoginFB();

            }
            catch (Exception $e)
            {
                //echo "Entro en Exception<br>";

                return $this->armarSalidaExcepcion($e);

              
            }
            

        }

        private function setUsuario($valor)
        {
            $this->usuario = $valor;
        }

        public function getUsuario()
        {
            return $this->usuario;
        }


        public function api(/* polymorphic */) {
            $args = func_get_args();
            return call_user_func_array(array($this->facebook, 'api'), $args);
        }



	public function tienePermisosNecesarios()
	{
		$required_perms = explode(',',FB_APP_PERMISOS);

		if(count($required_perms) < 1) {
			return(true);
		}

		//	hay que generar una query para cada permiso, pero se ejecutan todas juntas....
		$queries = array();
		foreach($required_perms as $perm) {
			$queries[$perm] = 'select ' . $perm . ' from permissions where uid=me()';
		}

		try {
			
			$result = $this->api(array(
				'method' 	=> 'fql.multiquery',
				'queries'	=>	$queries,
			));
		} catch (FacebookApiException $e)
		{
			return(false);
		}

		$okperms=array();

		foreach($result as $rs) {
			if((isset($rs['fql_result_set']))&&(isset($rs['fql_result_set'][0])))
			{
				foreach($rs['fql_result_set'][0] as $perm_name => $perm_value) {
					if($perm_value == '1') {
						$okperms[] = $perm_name;
					}
				}
			}
		}

		foreach($required_perms as $p)
		{
			if(!in_array($p,$okperms))
			{
				return(false);
			}
		}

		return(true);

	}



        public function llamada($params)
        {
            $fql = array(
                'method'    => 'fql.query',
                'query'     => '',
                'callback'  => ''
            );

            $rest = array(
                'method'    => '',
                'callback'  => ''
            );


            $salida = array(
                "estado" => 0
            );

            if ($params != null){

                //try
                //{
                    switch ($params['metodo'])
                    {

                        case FBUtils::GET_INFO:
                            $fql['query'] = 'SELECT ' . (isset($params['parametros']['campos']) ? $params['parametros']['campos'] : "")
                                            . ' FROM user WHERE uid = ' . ($params['parametros']['uid'] ? $params['parametros']['uid'] : 'me()');

                            if (Data::hasKey('limit', $params['parametros']))
                                    $fql['query'] .= ' LIMIT ' . $params['parametros']['limit'][0] . ',' . $params['parametros']['limit'][1];

                            

                            $fbResponse = $this->api($fql);

                            return $this->armarSalida($params['metodo'],$fbResponse);


                        break;
                        // <editor-fold defaultstate="collapsed" desc="FBUtils::GET_ALBUMS">
                        case FBUtils::GET_ALBUMS:

                            $fql['query'] = 'SELECT ' . $params['parametros']['campos']
                                            . ' FROM album WHERE owner = ' . $params['parametros']['uid'];

                            if (Data::hasKey('limit', $params['parametros']))
                                    $fql['query'] .= ' LIMIT ' . $params['parametros']['limit'][0] . ',' . $params['parametros']['limit'][1];

                            $fbResponse = $this->api($fql);

                            return $this->armarSalida($params['metodo'],$fbResponse);
                            break;
                        // </editor-fold>
                        // <editor-fold defaultstate="collapsed" desc="FBUtils::GET_AMIGOS">
                        case FBUtils::GET_AMIGOS:
                            $fql['query'] = 'SELECT ' . $params['parametros']['campos']
                                            . ' FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = ' . $params['parametros']['uid'] . ')';

                            if (Data::hasKey('limit', $params['parametros']))
                                    $fql['query'] .= ' LIMIT ' . $params['parametros']['limit'][0] . ',' . $params['parametros']['limit'][1];

                            $fbResponse = $this->api($fql);

                            return $this->armarSalida($params['metodo'],$fbResponse);



                            break;
                        // </editor-fold>
                        // <editor-fold defaultstate="collapsed" desc="FBUtils::GET_FOTOS">
                        case FBUtils::GET_FOTOS:
                            $fql['query'] = 'SELECT ' . $params['parametros']['campos']
                                            . ' FROM photo WHERE 1=1 ';

                            if (Data::hasKey('aid', $params['parametros']))
                                    $fql['query'] .= ' AND aid = \'' . $params['parametros']['aid'] . '\'';
                            if (Data::hasKey('pid', $params['parametros']))
                                    $fql['query'] .= ' AND pid = \'' . $params['parametros']['pid'] . '\'';
                            if (Data::hasKey('limit', $params['parametros']))
                                    $fql['query'] .= ' LIMIT ' . $params['parametros']['limit'][0] . ',' . $params['parametros']['limit'][1];

                            $fbResponse = $this->api($fql);

                            return $this->armarSalida($params['metodo'],$fbResponse);

                            break;
                        // </editor-fold>
                        // <editor-fold defaultstate="collapsed" desc="FBUtils::ES_FAN">
                        case FBUtils::ES_FAN:
                            $rest['method'] = 'pages.isFan';
                            $rest['page_id'] = $params['parametros']['pid'];
                            $fbResponse = $this->api($rest);
                            return $this->armarSalida($params['metodo'],$fbResponse);


                            break;
                        // </editor-fold>
                        // <editor-fold defaultstate="collapsed" desc="FBUtils::CREAR_ALBUM">
                        case FBUtils::CREAR_ALBUM:
                            $detalle        = array(
                                "name"      => $params['parametros']['nombre'],
                            );

                            if (Data::hasKey('mensaje', $params['parametros']))
                                    $detalle['message'] = $params['parametros']['mensaje'];

                            $fbResponse = $this->api('/me/albums', 'post', $detalle);
                            return $this->armarSalida($params['metodo'],$fbResponse);


                            break;
                        // </editor-fold>
                        // <editor-fold defaultstate="collapsed" desc="FBUtils::SUBIR_FOTO">
                        case FBUtils::SUBIR_FOTO:


                            $session = $this->getSession();
                            //$session = $this->session;

                            $albums = $this->api('/'.$params['parametros']['uid'].'/albums?access_token='.$session->access_token);
                            
                            $currentName = "";
                            $i=0;
                            $album_id = 0;
                            $length = count($albums['data']);
                            
                            while($i != $length)
                            {
                                $currentName = $albums['data'][$i]['name'];

                                if($currentName == $params['parametros']['album'])
                                {
                                    $album_link = $albums['data'][$i]['link'];
                                    $pieces = explode("?aid=", $album_link);
                                    $pieces2 = explode("&id=", $pieces[1]);
                                    $album_id = $pieces2[0];
                                }

                                $i++;
                            }

                            if($album_id == 0)
                            {
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/'.$params['parametros']['uid'].'/albums');
                                curl_setopt($ch, CURLOPT_POSTFIELDS,'name='.$params['parametros']['album'].'&message='.$params['parametros']['mensaje_album'].'&access_token='.$session->access_token);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_HEADER, 0);
                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; fr-CA; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");

                                $test = curl_exec($ch);
                                curl_close($ch);

                                $albums = $this->api('/'.$params['parametros']['uid'].'/albums?access_token='.$session->access_token);
                                $currentName = "";
                                $i=0;
                                $album_id = 0;
                                $length = count($albums['data']);
                                while($i != $length)
                                {
                                    $currentName = $albums['data'][$i]['name'];

                                    if($currentName == $params['parametros']['album'])
                                    {
                                        $album_link = $albums['data'][$i]['link'];
                                        $pieces = explode("?aid=", $album_link);
                                        $pieces2 = explode("&id=", $pieces[1]);
                                        $album_id = $pieces2[0];
                                    }

                                    $i++;
                                }
                            }

                            $data = array(basename($params['parametros']['imagen']) => "@".realpath($params['parametros']['imagen']),
                            //filename, where $row['file_location'] is a file hosted on my server
                                "caption" => $params['parametros']['mensaje_imagen'],
                                "aid" => $album_id, //valid aid, as demonstrated above
                                "access_token" => $session->access_token
                            );
                            $ch = curl_init();
                            $url = "https://api.facebook.com/method/photos.upload";
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_HEADER, false);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                            $op = curl_exec($ch);
                            return $op;


                            break;
                        // </editor-fold>
                        // <editor-fold defaultstate="collapsed" desc="FBUtils::POST_FEED">
                        case FBUtils::POST_FEED:


                            $detalle        = array(
                                "message"       => "",
                                "picture"       => "",
                                "link"          => "",
                                "name"        => "",
                                "caption"       => "",
                                "description"   => "",
                                "actions"       => ""
                            );

                            if (Data::hasKey('mensaje', $params['parametros']))
                                    $detalle['message'] = $params['parametros']['mensaje'];
                            if (Data::hasKey('imagen', $params['parametros']))
                                    $detalle['picture'] = $params['parametros']['imagen'];
                            if (Data::hasKey('link', $params['parametros']))
                                    $detalle['link'] = $params['parametros']['link'];
                            if (Data::hasKey('nombre', $params['parametros']))
                                    $detalle['name'] = $params['parametros']['nombre'];
                            if (Data::hasKey('caption', $params['parametros']))
                                    $detalle['caption'] = $params['parametros']['caption'];
                            if (Data::hasKey('descripcion', $params['parametros']))
                                    $detalle['description'] = $params['parametros']['descripcion'];
                            if (Data::hasKey('acciones', $params['parametros']))
                                    $detalle['actions'] = $params['parametros']['acciones'];
                            if (Data::hasKey('target', $params['parametros']))
                                    $target = $params['parametros']['target'];
                            else
                                    $target = "me";

                            $fbResponse = $this->api("/{$target}/feed", 'post', $detalle);
                            return $this->armarSalida($params['metodo'],$fbResponse);

                            break;
                            // </editor-fold>
                    }

                /*}catch(Exception $ex){
                    $salida['error'] = array(
                        "code"      => $ex->getCode(),
                        "message"   => $ex->getMessage()
                    );

                    return $salida;
                }*/


            }


        }
        private function armarSalida($metodo,$respuesta)
        {
            $salida = array(
                "estado" => 0
            );

            switch ($metodo)
            {
                case FBUtils::GET_ALBUMS:
                case FBUtils::GET_AMIGOS:
                case FBUtils::GET_FOTOS:
                case FBUtils::POST_FEED:
                case FBUtils::CREAR_ALBUM:
                case FBUtils::GET_INFO:

                    if (isset($respuesta) && is_array($respuesta))
                    {
                        if (Data::hasKey('error', $respuesta))
                        {
                            $salida['error'] = $respuesta['error'];
                        }
                        else
                        {
                            $salida['estado']    = 1;
                            $salida['datos']     = $respuesta;
                        }

                    }
                    else
                    {
                        $salida['datos'] = null;
                    }


                    break;
                case FBUtils::ES_FAN:
                    if (isset($respuesta) && $respuesta == '1')
                        $salida['estado']    = 1;


                    break;

            }
            return $salida;
        }

        private function armarSalidaExcepcion($ex)
        {

                $salida = array(
                    'estado'=> 0,
                    'error' => array(
                        "code"      => $ex->getCode(),
                        "message"   => $ex->getMessage()
                    )
                );

                return $salida;
        }
        public function printHttpHeader(){
		//header ( 'P3P:CP=\'IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT\'' );
		header ( 'Expires: Mon, 1 Jul 2006 21:30:00 GMT' );
		header ( 'Last-Modified: ' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
		header ( 'Cache-Control: no-store, no-cache, must-revalidate' );
		header ( 'Cache-Control: post-check=0, pre-check=0' );
		header ( 'Cache-Control: private' );
		header ( 'Pragma: no-cache' );
        }
        public function forzarLoginFB()
        {
            if (isset($this->facebook)){
                $loginUrl = $this->facebook->getLoginUrl(
                            array(
                                    'canvas'    => 1,
                                    'fbconnect' => 0,
                                    'next'      => FB_APP_URL . (isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING']:''),
                                    'req_perms' => FB_APP_PERMISOS
                            )
                 );
                echo "<script>window.top.location.href=\"".$loginUrl."\";</script>";
            }
        }

}



?>
