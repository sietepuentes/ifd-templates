<?php

Jota::requerir('Zend/Loader.php');

set_include_path(WEB_ROOT);

Zend_Loader::loadClass('Zend_Gdata_YouTube', array(WEB_ROOT));
Zend_Loader::loadClass('Zend_Gdata_ClientLogin', array(WEB_ROOT));

Jota::incluir(   array(  'clases' => array('CredencialYoutube', 'Video')
                         )
                 );

class YoutubeWrapper
{
    /**
     * Esta funcion devuelve una url donde posdteamos el video
     * @autor Nano 4/10/2010
     * @param <class> $credencial
     * @param <class> $video
     * @return <$video>
     */
    public static function getUrlPost(CredencialYoutube $credencial, Video $video)
    {
        //TODO nano, a ver si le cambias el nombre a este metodo que dice urlpost y devuelve un video, no se entiende un carajo querido
        $authenticationURL= 'https://www.google.com/youtube/accounts/ClientLogin';
        $httpClient =
          Zend_Gdata_ClientLogin::getHttpClient(
                      $username = $credencial->getUser(),
                      $password = $credencial->getPassword(),
                      $service = $credencial->getService(),
                      $client = null,
                      $source = $credencial->getSource(), // a short string identifying your application
                      $loginToken = null,
                      $loginCaptcha = null,
                      $authenticationURL);

        $developerKey = $credencial->getDeveloperKey();
        $applicationId = null;
        $clientId = null;

        // Note that this example creates an unversioned service object.
        // You do not need to specify a version number to upload content
        // since the upload behavior is the same for all API versions.
        $yt = new Zend_Gdata_YouTube($httpClient, $applicationId, $clientId, $developerKey);

        // create a new VideoEntry object
        $myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();

        $myVideoEntry->setVideoPrivate();

        $myVideoEntry->setVideoTitle($video->getNombre());
        $myVideoEntry->setVideoDescription($video->getDescripcion());
        // The category must be a valid YouTube category!
        $myVideoEntry->setVideoCategory("Music");

        // Set keywords. Please note that this must be a comma-separated string
        // and that individual keywords cannot contain whitespace
        $myVideoEntry->SetVideoTags($video->getTag());

        $tokenHandlerUrl = 'http://gdata.youtube.com/action/GetUploadToken';
        $tokenArray = $yt->getFormUploadToken($myVideoEntry, $tokenHandlerUrl);

        $video->setUrlPost($tokenArray['url']);
        $video->setToken($tokenArray['token']);
        $video->setIDYoutube($myVideoEntry->getId());

        return $video;
    }

   public static function actualizarVideo(CredencialYoutube $credencial, Video $video)
    {
        $authenticationURL= 'https://www.google.com/youtube/accounts/ClientLogin';
        $httpClient =
          Zend_Gdata_ClientLogin::getHttpClient(
                      $username = $credencial->getUser(),
                      $password = $credencial->getPassword(),
                      $service = $credencial->getService(),
                      $client = null,
                      $source = $credencial->getSource(), // a short string identifying your application
                      $loginToken = null,
                      $loginCaptcha = null,
                      $authenticationURL);

        $developerKey = $credencial->getDeveloperKey();
        $applicationId = null;
        $clientId = null;

        $yt = new Zend_Gdata_YouTube($httpClient, $applicationId, $clientId, $developerKey);
        
            try
            {
                    $videoEntry = $yt->getFullVideoEntry($video->getIDYoutube());
                    $videoEntry->setVideoTitle($video->getNombre());
                    $videoEntry->setVideoDescription($video->getDescripcion());
                    if($video->getHabilitado())
                        $videoEntry->setVideoPublic();
                    else
                        $videoEntry->setVideoPrivate();

                    $arrayMiniaturas = $videoEntry->getVideoThumbnails();
                    $arrayLinks = $videoEntry->getLink();

//                    Jota::eswas($arrayLinks[0]['link']);
                    
                    $video->setUrlMiniatura($arrayMiniaturas[0]['url']);

                    $putUrl = $videoEntry->getEditLink()->getHref();
                    $yt->updateEntry($videoEntry, $putUrl);
            }
            catch (Exception $e){ echo "<script>alert('error al intentar actualizar el video');</script>";}

        return $video;
    }
}

?>
