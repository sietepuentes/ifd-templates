<?php
    require_once "prog/frm/core/ini.php";
    Jota::incluir(   array  (  'clases' => array('BidUsuarioBo'),
                             'helpers' => array('PageBo','Page','Html')
                            )
    );
    PageBo::desloguear();
?>
<!DOCTYPE html>
<html lang="en">

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
   
    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="bodyLogin">
    <div class="container">
        <form class="form-signin" id="formulario">
            <h2 class="form-signin-heading"><img src="img/logo.png"></h2>
            <label for="inputEmail" class="sr-only">Usuario</label>
            <input type="text" id="txtUsuario" name="txtUsuario" class="form-control" placeholder="Usuario" required autofocus>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="txtPass" name="txtPass" class="form-control" placeholder="Password" required>
            <button id="button"  class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        </form>

    </div> <!-- /container -->

    
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    
    
    <script src="js/bootstrap-dialog.min.js"></script>
    
    <script type="text/javascript" src="prog/frm/js/LoaderJs.php"></script>
    <script type="text/javascript" src="prog/app/mcc/controladores/bo.login.cont.js?<?php echo rand();?>"></script>
</body>
</html>