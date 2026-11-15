<?php


class seguridad{
    public $link;

    public function __construct(database $link){
        $this->link = $link;

        $session_id = $_GET['session_id'];

        $sessiones_modelo = new sessiones($this->link);
        $joins = ' LEFT JOIN usuarios ON usuarios.id = sessiones.usuario_id ';
        $joins .= ' LEFT JOIN grupos ON grupos.id = sessiones.grupo_id';
        $r_sessiones = $sessiones_modelo->filtro_and(array('session_id'=>$session_id),array(),
            '',$joins);
        if (isset($r_sessiones['error'])){
            $msj = 'Error, al validar el session_id';
            header_url('','','',$msj);
        }

        if ($r_sessiones['n_registros'] !== 1){
            $msj = 'Error, session_id no valido';
            header_url('','','',$msj);
        }

        $session = $r_sessiones['registros'][0];
        define('USUARIO_ID',$session['usuario_id']);
        define('GRUPO_ID',$session['grupo_id']);
        define('SESSION_ID',$session['session_id']);
        define('NOMBRE_USUARIO_SINESPACIO',strtoupper("{$session['nombre']}_{$session['apellido_p']}_{$session['apellido_m']}"));
        define('NOMBRE_USUARIO',strtoupper("{$session['nombre']} {$session['apellido_p']} {$session['apellido_m']}"));
        define('GRUPO',strtoupper($session['descripcion_grupo']));
    }

    public function genera_acciones_base () {
        $metodo_grupo_modelo = new metodo_grupo($this->link);
        $filtro = array(
            'metodo_grupo.grupo_id' => GRUPO_ID,
            'metodos.status_accion' => 'activo',
            'menus.descripcion_menu' => CONTROLADOR
        );
        $columnas_base = array('metodos.descripcion_metodo AS metodo','menus.descripcion_menu AS controlador',
            'label_accion AS label','icon_accion AS icon');
        $joins = '';
        $joins .= ' LEFT JOIN metodos ON metodo_grupo.metodo_id = metodos.id ';
        $joins .= ' LEFT JOIN menus ON metodos.menu_id = menus.id  ';

        $r_acciones = $metodo_grupo_modelo->filtro_and($filtro,$columnas_base,' ORDER by metodo ASC',$joins);

        if (isset($r_acciones['error'])){
            $error = $this->errores->datos(1,'Error, al generar el menu',
                __CLASS__,__LINE__,__FILE__,$r_acciones,__FUNCTION__);
            print_r($error);
            exit;
        }

        if ($r_acciones['n_registros'] === 0){
            return array();
        }

        return $r_acciones['registros'];

    }// end genera_acciones

    public function genera_menu () {
        $metodo_grupo_modelo = new metodo_grupo($this->link);
        $filtro = array(
            'metodo_grupo.grupo_id' => GRUPO_ID,
            'metodos.status_menu' => 'activo',
            'menus.status' => 'activo'
        );
        $columnas_base = array('metodos.descripcion_metodo','metodos.label_metodo','menus.label_menu',
            'menus.descripcion_menu','menus.icon_menu');
        $joins = '';
        $joins .= ' LEFT JOIN metodos ON metodo_grupo.metodo_id = metodos.id ';
        $joins .= ' LEFT JOIN menus ON metodos.menu_id = menus.id  ';

        $r_menu = $metodo_grupo_modelo->filtro_and($filtro,$columnas_base,' ORDER BY metodo_grupo.id ASC',$joins);

        if (isset($r_menu['error'])){
            $error = $this->errores->datos(1,'Error, al generar el menu',
                __CLASS__,__LINE__,__FILE__,$r_menu,__FUNCTION__);
            print_r($error);
            exit;
        }

        if ($r_menu['n_registros'] === 0){
            return array();
        }

        $menus = $r_menu['registros'];
        $menu_array = array();

        foreach ( $menus as $menu){
            if (!isset($menu_array[ $menu['label_menu'] ])){
                $menu_array[ $menu['label_menu'] ] = array($menu['descripcion_menu'],
                    $menu['icon_menu'],$menu['label_menu']);
            }
            array_push($menu_array[ $menu['label_menu'] ] ,array(
                'label' =>  $menu['label_metodo'],
                'metodo' => $menu['descripcion_metodo']
            ));

        }
        return $menu_array;

    }

    public function valida_permiso(){
        $metodo_grupo_modelo = new metodo_grupo($this->link);
        $filtro = array(
            'metodo_grupo.grupo_id' => GRUPO_ID,
            'metodos.descripcion_metodo' => $_GET['metodo'],
            'menus.descripcion_menu' => $_GET['controlador']
        );
        $columnas_base = array('metodo_grupo.id');
        $joins = '';
        $joins .= ' LEFT JOIN metodos ON metodo_grupo.metodo_id = metodos.id ';
        $joins .= ' LEFT JOIN menus ON metodos.menu_id = menus.id  ';

        $r_menu = $metodo_grupo_modelo->filtro_and($filtro,$columnas_base,'',$joins);

        if (isset($r_menu['error'])){
            $error = $this->errores->datos(1,'Error, al validar permisos',
                __CLASS__,__LINE__,__FILE__,$r_menu,__FUNCTION__);
            print_r($error);
            exit;
        }

        if ($r_menu['n_registros'] === 1){
            return true;
        }

        return false;
    }

}