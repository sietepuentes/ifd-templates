<?php
    require_once "prog/frm/core/ini.php";
    Jota::incluir(   array  (  'clases' => array('BidUsuarioBo'),
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">
        
        <?php 
        $paginaAct = "administradores";
        include("menu.php");
        ?>
        

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Adminstrador
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
                
                <div class="row">
                    <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6" col-xs-12>
                        <form id="formulario" role="form" method="post">
                            <input type="hidden" name="oper" id="oper" />
                            <input type="hidden" name="id" id="id" />
                            <div class="form-group">
                                <label>Nombre</label>
                                <input class="form-control" placeholder="Ingrese el Nombre" id="txtNombre" name="txtNombre" required autofocus>
                            </div>
                            <div class="form-group">
                                <label>Apellido</label>
                                <input class="form-control" placeholder="Ingrese el Apellido" id="txtApellido" name="txtApellido" required autofocus>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" placeholder="Ingrese el Email" id="txtEmail" name="txtEmail" required autofocus>
                            </div>
                            <div class="form-group">
                                <label>Usuario</label>
                                <input class="form-control" placeholder="Ingrese el Usuario" id="txtUsuario" name="txtUsuario" required autofocus>
                            </div>
                            <div class="form-group">
                                <label>Clave</label>
                                <input class="form-control" placeholder="Ingrese el Clave" id="txtPassword" name="txtPassword" required autofocus>
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

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <script src="js/bootstrap-dialog.min.js"></script>
    <script type="text/javascript" src="prog/frm/js/LoaderJs.php"></script>
    <script type="text/javascript" src="prog/app/mcc/controladores/bo.altaUsuarioBO.cont.js"></script>
</body>

</html>
