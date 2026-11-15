<?php
    class controlador_session extends controlador{
        public $sessiones_modelo;

        public function __construct(database $link){
            parent::__construct($link);
            $this->sessiones_modelo = new sessiones($this->link);

            $this->breadcrumb = [false];
        }

        public function  login_bd(){
            $usuario_modelo = new usuarios($this->link);
            $filtro = array(
                'usuarios.user' => $_POST['user'],
                'usuarios.password' =>$_POST['password'],
                'usuarios.status' => 'activo'
            );
            $r_usuario =$usuario_modelo->filtro_and($filtro);

            if (isset($r_usuario['error'])){
                $error = $this->errores->datos(1,'Error, al obtener datos',
                    __CLASS__,__LINE__,__FILE__,$r_usuario,__FUNCTION__);
                print_r($error);
                exit;
            }

            if ((int)$r_usuario['n_registros'] != 1){
                header_url('','','','Error, usuario o contrase単a incorrecto');
            }

            $usuario = $r_usuario['registros'][0];

            $string = $_POST['user'].$_POST['password'].time();

            $session_id = md5(md5($string));

            $insert['session_id'] = $session_id;
            $insert['usuario_id'] = $usuario['id'];
            $insert['fecha'] = date('Y-m-d');
            $insert['grupo_id'] = $usuario['grupo_id'];

            $resultado = $this->sessiones_modelo->alta_bd($insert);

            if (isset($resultado['error'])){
                $error = $this->errores->datos(1,'Error, generar el session_id',
                    __CLASS__,__LINE__,__FILE__,$resultado,__FUNCTION__);
                print_r($error);
                exit;
            }

            header_url('session','inicio',$session_id,'<strong><H4 align=center>Estimado Docente del ITS Juan Rodriguez Clara, Bienvenido al PID</H4></strong>');

        }

        public function inicio(){

        }

        public function login_off(){
            $filtro = array('sessiones.session_id' => SESSION_ID);
            $resultado = $this->sessiones_modelo->elimina_con_filtro_and($filtro);

            if (isset($resultado['error'])){
                header_url('session','inicio',SESSION_ID,'Error al cerrar session: ');
            }
            header('Location: '.RUTA_PROYECTO);

        }
    }