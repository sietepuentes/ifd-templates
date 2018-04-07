<?php
    require_once "prog/frm/core/ini.php";
    Jota::incluir(   array  (  'clases' => array('BidUsuarioBo', 'BidNew'),
                               'helpers' => array('PageBo','Page','Html')
                            )
    );
    PageBo::verificarSesion();
?>
<!DOCTYPE html>
<html ng-app="myApp" ng-app lang="en">

<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo Config::TITULO_BO; ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" media="all" href="css/bootstrap-colorpalette.css" />
    <link rel="stylesheet" href="css/jquery.Jcrop.css" type="text/css" />
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .posicionParaColor {
            float:left;
        }

        ul.dropdown-menu {
            min-width: 144px;
        }

    </style>
</head>

<body>

    <div id="wrapper">
        
        <?php 
            $paginaAct = "news";
            include("menu.php");
        ?>
        

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            IFD News
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
                
                <div class="row">
                    <div class="col-lg-offset-2 col-lg-8 col-md-offset-3 col-md-6" col-xs-12>
                        <form id="formulario" role="form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="oper" id="oper" />
                            <input type="hidden" name="id" id="id" />
                            <div class="form-group">
                                <label>Titulo</label>
                                <input class="form-control" placeholder="Ingrese el Titulo" id="txtTitulo" name="txtTitulo" required autofocus>
                            </div>
                            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-info">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Modulos de Informacion</h3>
                                                <button id="button" onclick="agregarModulo();" class="btn btn-xs btn-primary btn-autores" type="button">Agregar Modulo +</button>
                                            </div>
                                            <input type="hidden" name="hidCantInf" id="hidCantInf" value="0">
                                            <div class="panel-body" id="listadoInfo">
                                                
                                            </div>
                                        </div>
                                        <div class="panel-heading">
                                            <button id="button" onclick="agregarModulo();" class="btn btn-xs btn-primary btn-autores" type="button">Agregar Modulo +</button>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    <div class="col-lg-12"></div>                                    
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Facebook</label>
                                <input class="form-control" placeholder="Ingrese el link de Facebook" id="txtFacebook" name="txtFacebook"  autofocus>
                            </div>
                            <div class="form-group">
                                <label>Twitter</label>
                                <input class="form-control" placeholder="Ingrese el link de Twitter" id="txtTwitter" name="txtTwitter"  autofocus>
                            </div>
                            <div class="form-group">
                                <label>Youtube</label>
                                <input class="form-control" placeholder="Ingrese el link de Youtube" id="txtYoutube" name="txtYoutube"  autofocus>
                            </div>
                            <div class="form-group">
                                <label>Instagram</label>
                                <input class="form-control" placeholder="Ingrese el link de Instagram" id="txtInstagram" name="txtInstagram"  autofocus>
                            </div>
                            <div class="form-group">
                                <label>Google +</label>
                                <input class="form-control" placeholder="Ingrese el link de Google +" id="txtGoogle" name="txtGoogle" autofocus>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" id="button" class="btn btn-default">Aceptar</button>
                                <button type="reset" class="btn btn-default">Borrar</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <div id="popup" class="popup" style="display: none;">
            <div class="popup__body"><div class="js-img"></div></div>
            <div style="margin: 0 0 5px; text-align: center;">
                    <div class="js-upload btn btn_browse btn_browse_small">Upload</div>
            </div>
    </div>
    
    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <script src="js/FileAPI/FileAPI.min.js"></script>
    <script src="js/FileAPI/FileAPI.exif.js"></script>
    <script src="js/jquery.fileapi.js"></script>
    <script src="js/jquery.modal.js"></script>
    
    <script src="//cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/3.3.0/ekko-lightbox.min.js"></script>    
    
    <script src="js/bootstrap-dialog.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-colorpalette.js" charset="utf-8"></script>
    <script type="text/javascript" src="prog/frm/js/LoaderJs.php"></script>
    <script type="text/javascript" src="prog/app/mcc/controladores/bo.altaNew.cont.js"></script>
    
    <script src="js/jquery.Jcrop.js"></script>
    
    
    
    <script>
       /* $('#userpic').fileapi({
            url: 'uploadImagen.php',
            accept: 'image/*',
            imageSize: { minWidth: 600, minHeight: 162 },
            elements: {
               active: { show: '.js-upload', hide: '.js-browse' },
               preview: {
                  el: '.js-preview',
                  width: 600,
                  height: 162
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
                           $('#userpic').fileapi('upload');
                        });
                        $('.js-img', overlay).cropper({
                           file: file,
                           bgColor: '#fff',
                           maxSize: [$(window).width()-100, $(window).height()-100],
                           minSize: [600, 162],
                           selection: '90%',
                           onSelect: function (coords){
                              $('#userpic').fileapi('crop', file, coords);
                           }
                        });
                     }
                  }).open();
               }
            }
         });*/
    </script>
</body>

</html>
