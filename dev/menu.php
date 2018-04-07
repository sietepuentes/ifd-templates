        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="bienvenido.php"><?php echo Config::TITULO_BO; ?></a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>&nbsp;&nbsp;<?php if(isset($_SESSION["Nombre".PageBo::KEY_USUARIO_BO])) echo $_SESSION["Nombre".PageBo::KEY_USUARIO_BO];?>&nbsp;&nbsp;<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="login.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li <?php if(isset($paginaAct) && $paginaAct == "administradores") { echo 'class="active"'; } ?>>
                        <a href="administradores.php"><i class="fa fa-user"></i> Administradores</a>
                    </li>
                    <li <?php if(isset($paginaAct) && $paginaAct== "publiacacion") { echo 'class="active"'; } ?>>
                        <a href="publicaciones.php"><i class="fa fa-fw fa-file"></i> Publicaciones</a>
                    </li>
                    <li <?php if(isset($paginaAct) && $paginaAct== "newsletters") { echo 'class="active"'; } ?>>
                        <a href="newsletters.php"><i class="fa fa-fw fa-file"></i> Eventos</a>
                    </li>
                    <li <?php if(isset($paginaAct) && $paginaAct== "news") { echo 'class="active"'; } ?>>
                        <a href="news.php"><i class="fa fa-fw fa-file"></i> IFD News</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>