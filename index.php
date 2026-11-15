<?php
    error_reporting(E_ALL);
    ini_set('upload_max_filesize', '2048M');
    ini_set('post_max_size', '2048M');
	ini_set('display_errors', 1);
	date_default_timezone_set('America/Mexico_City');
	require_once('requires.php');

	$parametros_get_requeridos = array('controlador','metodo');

    foreach ($parametros_get_requeridos as $parametro){
        valida_parametro_get($parametro);
    }

    if ($_GET['controlador'] === 'session' && $_GET['metodo'] === 'login_bd'){

        $link = new database();
        $controlador = new controlador_session($link);
        $res = $controlador->login_bd();

    }

    valida_parametro_get('session_id');

    $link = new database();

    $seguridad = new seguridad($link);

    $menu_navegacion = $seguridad->genera_menu();

    $HTML = new html();

    if ($seguridad->valida_permiso()){
        define('CONTROLADOR',$_GET['controlador']);
        define('METODO',$_GET['metodo']);

        $nombre_controlador = 'controlador_'.CONTROLADOR;
        if (file_exists('controladores/'.$nombre_controlador.'.php')){
            $controlador = crear_controlador($nombre_controlador,$link);

            if (method_exists($controlador,METODO)){
                $acciones = $seguridad->genera_acciones_base();
                $metdo = METODO;
                $resultado = $controlador->$metdo();
            }
            else{
                $_GET['mensaje'] = 'El metodo '.METODO.' no existe';
            }
        }
        else{
            $_GET['mensaje'] = 'El controldaor '.CONTROLADOR.' no existe';
        }
    }
    else{
        define('CONTROLADOR','');
        define('METODO','');
        $_GET['mensaje'] = 'Permiso Denegado';
    }


    ?>
    <?php require_once ('template/header.php'); ?>
    <?php
        $css = 'views/CSS/'.CONTROLADOR.'.'.METODO.'.css';
        if (file_exists($css)){?><link rel="stylesheet" href="<?php echo RUTA_PROYECTO.$css ?>">
    <?php } ?>
    <?php require_once ('template/header2.php'); ?>
    <?php require_once ('template/nav.php'); ?>
    <?php require_once ('template/menu.php'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content">
            <?php if ($controlador->breadcrumb[0]){ ?>
                <ol class="breadcrumb">
                    <li><a ><?php echo strtoupper(CONTROLADOR); ?></a></li>
                    <li><a ><?php echo strtoupper(METODO); ?></a></li>
                </ol>
            <?php }// end if ($controlador->breadcrumb)  ?>

            <?php if (isset($_GET['mensaje'])){ ?>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="alert alert-info alert-dismissible"
                             style="background-color: white !important; color: black !important; border-color: white !important;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <p align="center"><?php echo $_GET['mensaje']; ?></p>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>

            <?php } // end if (isset($_GET['mensaje'])) ?>

            <?php
            $view = 'views/'.CONTROLADOR.'/'.METODO.'.php';
            if(file_exists($view)) {
                require_once ('views/'.CONTROLADOR.'/'.METODO.'.php');
            }else{

                if ( METODO == 'alta' ){
                    require_once ('views/1base/alta.php');
                }

                if (METODO == 'modifica'){
                    require_once ('views/1base/modifica.php');
                }

                if (METODO == 'lista'){
                    require_once ('views/1base/lista.php');
                }
            }// end if(file_exists($view))


            ?>
        </section><!-- /.content -->
    </div>


    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.0.5 Tepache
        </div>
        <strong>Copyright &copy; 2026 mcz&cabg </strong>ITS JUAN RODRIGUEZ CLARA Todos los Derechos Reservados.
    </footer>

</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS  -->
<script src="<?php echo RUTA_BOOTSTRAP ?>js/jquery.min.js"></script>
<script src="<?php echo RUTA_BOOTSTRAP ?>js/bootstrap.min.js"></script>
<script src="<?php echo RUTA_ADMINLTE ?>js/adminlte.min.js"></script>
<script src="<?php echo RUTA_BOOTSTRAP ?>js/select2.full.min.js"></script>
<script src="<?php echo RUTA_PROYECTO ?>views/JS/1base.js"></script>
<?php
$js = 'views/JS/'.CONTROLADOR.'.'.METODO.'.js';
if (file_exists($js)){?><script src="<?php echo RUTA_PROYECTO.$js ?>"></script>">
<?php } ?>

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2();
    })
</script>
</body>
</html>


