<?php
    require_once "prog/frm/core/ini.php";
    Jota::incluir(   array  (  'clases' => array('BidUsuarioBo','BidNewsletter'),
                               'helpers' => array('PageBo','Page','Html')
                            )
    );
    PageBo::verificarSesion();
    $paginaResultados = 'prog/app/mcc/modelos/bo.abmNew.mod.php';
    $paginaAltaEditar="altaNews.php";
    $paginaActual="news.php";
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
                    <div class="col-lg-12 col-xs-12">
                        <div ng-controller="customersCrtl">
                            <!--<div class="container">-->
                                <div class="row">
                                    <div class="col-lg-2 col-md-2">Tamaño de pagina:
                                        <select ng-model="entryLimit" class="form-control">
                                            <option>5</option>
                                            <option>10</option>
                                            <option>20</option>
                                            <option>50</option>
                                            <option>100</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3">Filtro
                                        <input type="text" ng-model="search" ng-change="filter()" placeholder="Filtro" class="form-control" />
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <h5>Filtrado {{ filtered.length}} de {{ totalItems}} en total</h5>
                                    </div>
                                    <div class="col-lg-3 col-md-4">
                                        <button id="button" onclick="window.location.href='<?php echo $paginaAltaEditar;?>?oper=add'" class="btn btn-lg btn-primary btn-block btn-nuevo" type="button">Nuevo</button>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-md-12" ng-show="filteredItems > 0">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                            <th width="85%">Titulo&nbsp;<a ng-click="sort_by('Titulo');"><i class="glyphicon glyphicon-sort"></i></a></th>
                                            <th style="text-align: center">Acciones</th>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="data in filtered = (list| filter:search | orderBy : predicate :reverse) | startFrom:(currentPage - 1) * entryLimit | limitTo:entryLimit">
                                                    <td>{{data.Titulo}}</td>
                                                    <td align="center">
                                                        <a href="generar.php?id={{data.IDNew}}&tipo=ifdnew&oper=HTML" target="_blank" class="btn btn-xs btn-info"> <i class="fa fa-eye"></i> </a>
                                                        <a href="generar.php?id={{data.IDNew}}&tipo=ifdnew&oper=DOWN" target="_blank" class="btn btn-xs btn-warning"> <i class="fa fa-download"></i> </a>
                                                        <a href="<?php echo $paginaAltaEditar;?>?id={{data.IDNew}}&oper=edit" class="btn btn-xs btn-success"> <i class="fa fa-pencil"></i> </a>
                                                        <button class="btn btn-xs btn-danger" type="button" data-url-eliminar="<?php echo $paginaActual;?>" data-id-eliminar="{{data.IDNew}}" data-toggle="modal" data-target="#confirmDelete" data-title="Eliminar" data-message="Esta seguro que quiere eliminar el registro?"> <i class="fa fa-trash-o"></i> </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12" ng-show="filteredItems == 0">
                                        <div class="col-md-12">
                                            <h4>Sin resultado</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-12" ng-show="filteredItems > 0">    
                                        <div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="&laquo;" next-text="&raquo;"></div>


                                    </div>
                                </div>
                            <!--</div>-->
                        </div>
                    </div>
                </div>
                

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <?php include("eliminar.php");?>
    
    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

        <script src="js/angular.min.js"></script>
        <script src="js/ui-bootstrap-tpls-0.10.0.min.js"></script>
        <script>var paginaResultados = '<?php echo $paginaResultados;?>';</script>
        <script src="js/listado.js"></script>
</body>

</html>
