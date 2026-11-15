<?php


class controlador_instrumentaciones extends controlador{

    public array $carreras;
    public array $materias;
    public array $usuarios;
    public array $periodos;
    public array $bloques;
    public array $gruposAlumnos;
    public array $datosInstrumentacion = [];
    public array $archivosInstrumentacion = [];
    public string $carreras_ids = '';
    public modelo $instrumentaciones;
    public modelo $archivos;
    public $archivosServidor;

    public function __construct(database $link){

        parent::__construct($link, 'instrumentaciones');

        $this->instrumentaciones = new instrumentaciones($this->link);
        $this->archivos = new archivos($this->link);
        $this->archivosServidor = new archivosServidor();

        $this->configuracion();
    }

    public function alta(){
        $this->inputs[] = $this->HTML->select_buscador('Carrera','carrera_id',
            6,$this->carreras,'nombre','','required','',false,5);

        $this->inputs[] = $this->HTML->select_buscador('Materia','materia_id',
            6,$this->materias,'nombre_materia,nombre_carrera','','required',' - ',
            false,6);

        $this->inputs[] = $this->HTML->select_buscador('Docente','usuario_id',
            6,$this->usuarios,'nombre_usuario,nombre_carrera','','required',' - ',
            false,7);

        $this->inputs[] = $this->HTML->select_buscador('Periodo','periodo_id',
            6,$this->periodos,'nombre','','required',' - ',
            false,8);

        $this->inputs[] = $this->HTML->select_buscador('Bloque','bloque_id',
            6,$this->bloques,'nombre','','required',' - ',
            false,9);

        $this->inputs[] = $this->HTML->select_buscador('Grupo','grupoalumnos_id',
            6,$this->gruposAlumnos,'nombre','','required',' - ',
            false,10);

        $this->inputs[] = $this->HTML->submit('Registrar','instrumentaciones_alta_bd',6);
    }

    private function cargarArchivo(int $instrumentacionId, string $numero, string $tipo_archivo, string $campo_fecha, int $numeroModificaciones = 0)
    {

        $nombreArchivo = $this->generaNombreArchivo($instrumentacionId,$numero);
        
        if (isset($nombreArchivo['error'])){
            print_r($nombreArchivo);
            exit;
        }

        $guardarNombreArchivoEnBd = $this->archivos->registrarArchivoInstrumentacion($instrumentacionId,$nombreArchivo,$tipo_archivo);

        if (isset($guardarNombreArchivoEnBd['error'])){
            print_r($guardarNombreArchivoEnBd);
            exit;
        }
    
        $subirArchivo = $this->archivosServidor->subir_archivo(array('xlsx'),$_FILES['instrumentacion'],$nombreArchivo);
        
        if (isset($subirArchivo['error'])){
            print_r($subirArchivo);
            exit;
        }

        if ($numeroModificaciones == 0) {
            $numeroModificaciones = $this->obtenerDatosInstrumentacion($instrumentacionId)['numero'];
        }

        $modifcarDatos[$campo_fecha] = date('Y-m-d H:i:s');
        $modifcarDatos['numero_modificaciones'] = $numeroModificaciones;
        $modificarInstrumentacion = $this->instrumentaciones->modifica_por_id($instrumentacionId,$modifcarDatos);

        if (isset($modificarInstrumentacion['error'])){
            print_r($modificarInstrumentacion);
            exit;
        }
    }

    private function configuracion(){

        // inicia configuracion de las carreras
        $usuario_carrera_modelo = new usuario_carrera($this->link);

        $filtro_carreras = array('usuario_id' => USUARIO_ID);

        $columnas_carreras = array('carrera_id AS id','nombre');

        $join_carreras = ' INNER JOIN carreras ON carreras.id = usuario_carrera.carrera_id ';

        $this->carreras = $usuario_carrera_modelo->filtro_and($filtro_carreras,$columnas_carreras,'',
            $join_carreras)['registros'];

        foreach ($this->carreras as $carrera){
            $this->carreras_ids .= $carrera['id'].',';
        }
        $this->carreras_ids = trim($this->carreras_ids,',');

        // inicia la configuracion de las materias
        $materia_carrera_modelo = new materia_carrera($this->link);

        $columnas_materias = array('materia_id AS id','materias.nombre AS nombre_materia',
            'carreras.nombre AS nombre_carrera');

        $join_materias = ' INNER JOIN carreras ON carreras.id = materia_carrera.carrera_id ';

        $join_materias .= ' INNER JOIN materias ON materias.id = materia_carrera.materia_id ';

        $this->materias = $materia_carrera_modelo->filtro_and(array(),$columnas_materias,
            " AND carrera_id IN ($this->carreras_ids) ",$join_materias)['registros'];

        //inicia la configuracion de los usuarios
        $filtro_usuarios = array('usuarios.grupo_id' => 4);

        $columnas_usuarios = array('usuario_id AS id',"concat_ws(' ', usuarios.nombre, usuarios.apellido_p, 
            usuarios.apellido_m) AS nombre_usuario",'carreras.nombre AS nombre_carrera');

        $join_usuarios = ' INNER JOIN usuarios ON usuarios.id = usuario_carrera.usuario_id';

        $join_usuarios .= ' INNER JOIN carreras ON carreras.id = usuario_carrera.carrera_id ';

        $this->usuarios = $usuario_carrera_modelo->filtro_and($filtro_usuarios,$columnas_usuarios,
            " AND carrera_id IN ($this->carreras_ids) ",$join_usuarios)['registros'];

        //inicia configuracion de grupos

        $periodo_modelo = new periodos($this->link);

        $this->periodos = $periodo_modelo->filtro_and(['status'=>'activo'],['id','nombre'])['registros'];


        //inicia configuracion de bloques

        $bloques_modelo = new bloques($this->link);

        $this->bloques = $bloques_modelo->filtro_and(['status'=>'activo'],['id','nombre'])['registros'];

        //inicia configuracion de gruposalumnos

        $gruposalumnos_modelo = new gruposalumnos($this->link);

        $this->gruposAlumnos = $gruposalumnos_modelo->filtro_and(['status'=>'activo'],['id','nombre'])['registros'];
    }

    public function entregarReporte1()
    {
      $this->generaDatosFormulariosDeArchivos();  
    }

    public function entregarReporte1Bd()
    {
        $instrumentacionId = $this->obtenerInstrumentacionIdPost(); 
        $resultado = $this->cargarArchivo($instrumentacionId,'RPT1','Reporte '.REPORTE_1,'fecha_reporte1');
        header_url('instrumentaciones','lista',SESSION_ID,'Reporte '.REPORTE_1.' subido corectamente');
        exit;
    }

    public function entregarReporte2()
    {
      $this->generaDatosFormulariosDeArchivos();  
    }

    public function entregarReporte2Bd()
    {
        $instrumentacionId = $this->obtenerInstrumentacionIdPost(); 
        $resultado = $this->cargarArchivo($instrumentacionId,'RPT2','Reporte '.REPORTE_2,'fecha_reporte2');
        header_url('instrumentaciones','lista',SESSION_ID,'Reporte '.REPORTE_2.' subido corectamente');
        exit;
    }

    public function entregarReporte3()
    {
      $this->generaDatosFormulariosDeArchivos();  
    }

    public function entregarReporte3Bd()
    {
        $instrumentacionId = $this->obtenerInstrumentacionIdPost(); 
        $resultado = $this->cargarArchivo($instrumentacionId,'RPT3','Reporte '.REPORTE_3,'fecha_reporte3');
        header_url('instrumentaciones','lista',SESSION_ID,'Reporte '.REPORTE_3.' subido corectamente');
        exit;
    }

    public function entregarReporte4()
    {
      $this->generaDatosFormulariosDeArchivos();  
    }

    public function entregarReporte4Bd()
    {
        $instrumentacionId = $this->obtenerInstrumentacionIdPost(); 
        $resultado = $this->cargarArchivo($instrumentacionId,'RPT4','Reporte '.REPORTE_4,'fecha_reporte4');
        header_url('instrumentaciones','lista',SESSION_ID,'Reporte '.REPORTE_4.' subido corectamente');
        exit;
    }

    public function generaDatosFormulariosDeArchivos()
    {
        $instrumentacionId = $this->obtenerInstrumentacionIdGet();
        $this->breadcrumb = array(false);
        $this->datosInstrumentacion = $this->obtenerDatosInstrumentacion($instrumentacionId);
        if (isset($this->instrumentacion_datos['error'])){
            $error = $this->instrumentacion_datos;
            print_r($error);
            exit;
        }
    }

    private function generaNombreArchivo(int $instrumentacionId, string $numero) // funcion optimizada 25/08/2020
    {
        
        $datosInstrumentacion = $this->obtenerDatosInstrumentacion($instrumentacionId);
        $periodo_id = $datosInstrumentacion['id_periodo'];
        $usuario_id = $datosInstrumentacion['id_usuario'];
        $carrera_id = $datosInstrumentacion['id_carrera'];

        $nombre_materia = strtoupper($datosInstrumentacion['nombre_materia']);
        $nombre_materia = str_replace(' ','_',$nombre_materia);

        return "INSTRUMENTACION{$instrumentacionId}.{$usuario_id }.{$carrera_id}.{$periodo_id}_{$nombre_materia}_{$numero}.xlsx";
    }

    public function lista()
    {

        $this->lista_usar_filtro = true;
        $this->inputs_filtro_lista_cols = 3;
        $this->filtro_lista_campos = array(
            'Carrera' => 'carreras.nombre',
            'Materia' => 'materias.nombre',
            'Periodo' => 'periodos.nombre',
            'Bloque' => 'bloques.nombre',
            'Grupo' => 'gruposalumnos.nombre',
            'Docente Nombre' => 'usuarios.nombre',
            'Docente Apellido P' => 'usuarios.apellido_p',
            'Docente Apellido M' => 'usuarios.apellido_m'
        );
        

        $this->nombre_columnas_lista = array('ID','Carrera','Materia','Docente','Bloque','Grupo','Periodo','Instrumentacion',REPORTE_1,
        REPORTE_2,REPORTE_3,REPORTE_4);
        $this->columnas_lista = array('instrumentaciones.id','carreras.nombre AS nombre_carrera',
            'materias.nombre AS nombre_materia',
            "concat_ws(' ', usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m) AS nombre_usuario","bloques.nombre AS nombre_bloque",
            'gruposalumnos.nombre AS nombre_grupo','periodos.nombre AS nombre_periodo','instrumentaciones.fecha_instrumentacion','instrumentaciones.fecha_reporte1',
            'instrumentaciones.fecha_reporte2','instrumentaciones.fecha_reporte3','instrumentaciones.fecha_reporte4','instrumentaciones.status');

        $this->filtro_custom_lista = " AND carrera_id IN ($this->carreras_ids) ";

        if (GRUPO_ID == 4){
            unset($this->filtro_lista_campos['Docente Nombre']);
            unset($this->filtro_lista_campos['Docente Apellido P']);
            unset($this->filtro_lista_campos['Docente Apellido M']);
            unset($this->nombre_columnas_lista[3]);
            unset($this->columnas_lista[3]);
            $this->filtro_lista = array('usuario_id' => USUARIO_ID);
        }

        $this->joins_lista  = ' INNER JOIN usuarios       ON usuarios.id       = instrumentaciones.usuario_id ';
        $this->joins_lista .= ' INNER JOIN carreras       ON carreras.id       = instrumentaciones.carrera_id ';
        $this->joins_lista .= ' INNER JOIN periodos       ON periodos.id       = instrumentaciones.periodo_id ';
        $this->joins_lista .= ' INNER JOIN materias       ON materias.id       = instrumentaciones.materia_id ';
        $this->joins_lista .= ' INNER JOIN bloques        ON bloques.id        = instrumentaciones.bloque_id ';
        $this->joins_lista .= ' INNER JOIN gruposalumnos  ON gruposalumnos.id  = instrumentaciones.grupoalumnos_id ';

        parent::lista();
    }

    

    public function modifica()
    {
        parent::modifica();
        $this->inputs[] = $this->HTML->select_buscador('Carrera','carrera_id',6,$this->carreras,'nombre',$this->registro['carrera_id'],'required','',false,5);
        $this->inputs[] = $this->HTML->select_buscador('Materia','materia_id',6,$this->materias,'nombre_materia,nombre_carrera',$this->registro['materia_id'],'required',' - ',false,6);
        $this->inputs[] = $this->HTML->select_buscador('Docente','usuario_id',6,$this->usuarios,'nombre_usuario,nombre_carrera',$this->registro['usuario_id'],'required',' - ',false,7);
        $this->inputs[] = $this->HTML->select_buscador('Periodo','periodo_id',6,$this->periodos,'nombre',$this->registro['periodo_id'],'required',' - ',false,8);
        $this->inputs[] = $this->HTML->select_buscador('Bloque','bloque_id',6,$this->bloques,'nombre',$this->registro['bloque_id'],'required',' - ',false,9);
        $this->inputs[] = $this->HTML->select_buscador('Grupo','grupoalumnos_id',6,$this->gruposAlumnos,'nombre',$this->registro['grupoalumnos_id'],'required',' - ',false,10);
        $this->inputs[] = $this->HTML->submit('Modificar','instrumentaciones_modifica_bd',6);

    }

    public function modificar_instrumentacion()
    {
        $this->generaDatosFormulariosDeArchivos();
    }

    public function modificar_instrumentacion_bd()
    {
        $instrumentacionId = $this->obtenerInstrumentacionIdPost();
        $numeroModificaciones = $this->obtenerDatosInstrumentacion($instrumentacionId)['numero'];
        $numeroModificaciones++;
        $resultado = $this->cargarArchivo($instrumentacionId,'MOD','Instrumentacion Modificada','fecha_modificacion',$numeroModificaciones);
        header_url('instrumentaciones','lista',SESSION_ID,'Instrumentacion modificada corectamente');
        exit;
    }
    
    public function obtenerInstrumentacionIdGet()
    {
        if (!isset($_GET['registro_id'])) {
            header_url('instrumentaciones','lista',SESSION_ID);
            exit;
        }
        $instrumentacionId = $_GET['registro_id'];
        $this->validaInstrumentacionId($instrumentacionId);
        return $instrumentacionId;
    } 

    public function obtenerInstrumentacionIdPost()
    {
        if (!isset($_POST['instrumentacion_id'])) {
            header_url('instrumentaciones','lista',SESSION_ID);
            exit;
        }
        $instrumentacionId = $_POST['instrumentacion_id'];
        $this->validaInstrumentacionId($instrumentacionId);
        return $instrumentacionId;
    } 

    private function obtenerDatosInstrumentacion(int $instrumentacionId)
    {
        
        $columnas = [
            'periodos.id AS id_periodo','instrumentaciones.id','grupos.descripcion_grupo AS nombre_grupo','usuarios.id AS id_usuario','instrumentaciones.numero_modificaciones AS numero',
            'carreras.id AS id_carrera','materias.nombre AS nombre_materia','carreras.nombre AS nombre_carrera','periodos.nombre AS nombre_periodo',
            'usuarios.nombre AS docente_nombre','usuarios.apellido_p AS docente_apellido_p','usuarios.apellido_m AS docente_apellido_m'
        ];

        $datosInstrumentacion = $this->instrumentaciones->obten_datos_instrumentacion($instrumentacionId,$columnas);

        if (isset($datosInstrumentacion['error'])){
            return $this->errores->datos(1,'Error, al obtener los datos de la instrumentacion',__CLASS__,__LINE__,__FILE__,$resultado,__FUNCTION__);
        }

        return $datosInstrumentacion;
    }

    public function reporte_carrera(){

        $periodo_modelo = new periodos($this->link);
        $periodos = $periodo_modelo->filtro_and(array(),
            array('id','nombre'))['registros'];

        $tiposReportes = [
            ['id'=>'fecha_instrumentacion', 'nombre' => 'INSTRUMENTACIONES'],
            ['id'=>'fecha_reporte1', 'nombre' => strtoupper(REPORTE_1)],
            ['id'=>'fecha_reporte2', 'nombre' => strtoupper(REPORTE_2)],
            ['id'=>'fecha_reporte3', 'nombre' => strtoupper(REPORTE_3)],
            ['id'=>'fecha_reporte4', 'nombre' => strtoupper(REPORTE_4)]
        ];
        $arrayGruposAlumnos = [['id' => -1 , 'nombre' => 'TODOS']];
        
        foreach ($this->gruposAlumnos AS $gruposAlumno) {
            $arrayGruposAlumnos [] = $gruposAlumno;

        }

        $this->breadcrumb = array(false);
        $this->inputs[] = $this->HTML->select_buscador('Carrera','carrera_id',4,$this->carreras,'nombre','','required','',false,1);
        $this->inputs[] = $this->HTML->select_buscador('Periodo','periodo_id',4,$periodos,'nombre','','required',' - ',false,2);
        $this->inputs[] = $this->HTML->select_buscador('Bloque','bloque_id',4,$this->bloques,'nombre','','required',' - ',false,9);
        $this->inputs[] = $this->HTML->select_buscador('Grupo','grupoalumnos_id',4,$arrayGruposAlumnos,'nombre','','required',' - ',false,10);
        $this->inputs[] = $this->HTML->select_buscador('Tipo Reporte','tipo_reporte',4,$tiposReportes,'nombre','','required',' - ',false,3);
        $this->inputs[] = $this->HTML->submit('Generar Reporte','',4);
    }

    public function reporte_carrera_bd(){

        $carreraId = $_POST['carrera_id'];
        $periodoId = $_POST['periodo_id'];
        $bloqueId = $_POST['bloque_id'];
        $grupoId = $_POST['grupoalumnos_id'];
        $tipoReporte = $_POST['tipo_reporte'];
        print_r($tipoReporte);
        $datos = $this->instrumentaciones->obtenerDatosReporte($carreraId,$periodoId,$bloqueId,$grupoId,$tipoReporte);

        if (isset($datos['error'])) {
            $error = $datos['error'];
            print_r($error);
            exit;
        }

        if (count($datos) == 0) {
            header_url('instrumentaciones','reporte_carrera',SESSION_ID,'No existen registro de este periodo');
            exit;
        }

        if ($tipoReporte == 'fecha_instrumentacion') {
            $tituloReporte = 'DE INSTRUMENTACIONES';
            require ('views/instrumentaciones/reporteInstrumentaciones.php');
            exit;
        }
// ToDo : se piede mejorar esto para que evitar todos los if siguiente
        if ($tipoReporte == 'fecha_reporte1') {
            $tituloReporte = 'DE LA '.strtoupper(REPORTE_1);
            $textoFinal = strtoupper(REPORTE_1);
            require ('views/instrumentaciones/reporteSemanas.php');
            exit;
        }

        if ($tipoReporte == 'fecha_reporte2') {
            $tituloReporte = 'DE LA '.strtoupper(REPORTE_2);
            $textoFinal = strtoupper(REPORTE_2);
            require ('views/instrumentaciones/reporteSemanas.php');
            exit;
        }

        if ($tipoReporte == 'fecha_reporte3') {
            $tituloReporte = 'DE LA '.strtoupper(REPORTE_3);
            $textoFinal = strtoupper(REPORTE_3);
            require ('views/instrumentaciones/reporteSemanas.php');
            exit;
        }

        if ($tipoReporte == 'fecha_reporte4') {
            $tituloReporte = ' '.strtoupper(REPORTE_4);
            $textoFinal = strtoupper(REPORTE_4);
            require ('views/instrumentaciones/reporteSemanas.php');
            exit;
        }
    }

    public function subir_instrumentacion(){
        $this->generaDatosFormulariosDeArchivos();
    }

    public function subir_instrumentacion_bd()
    {
        $instrumentacionId = $this->obtenerInstrumentacionIdPost();
        $resultado = $this->cargarArchivo($instrumentacionId,'ORI','Instrumentacion Original','fecha_instrumentacion');
        header_url('instrumentaciones','lista',SESSION_ID,'Instrumentacion subida corectamente');
        exit;
    }

    private function validaInstrumentacionId($instrumentacionId)
    {
        if ($instrumentacionId < 1 ) {
            header_url('instrumentaciones','lista',SESSION_ID);
            exit;
        }
    }

    public function ver_archivo(){
        $this->breadcrumb = array(false);
        
        $archivoId = $_GET['registro_id'];

        $datosArchivo = $this->archivos->obten_data($archivoId);

        if (isset($datosArchivo['error'])){
            $error = $datosArchivo;
            print_r($error);
            exit;
        }

        $resultado = $this->archivosServidor->ver_archivo($datosArchivo['nombre_archivo']);
        print_r($resultado);exit;
    }

    public function ver_archivos_instrumentacion() // funcion por optimizar 25/08/2020
    {
        $instrumentacionId = $this->obtenerInstrumentacionIdGet();
        $this->breadcrumb = [false];
        $this->archivosInstrumentacion = $this->archivos->buscarPorInstrumentacionId($instrumentacionId);
        $this->datosInstrumentacion = $this->obtenerDatosInstrumentacion($instrumentacionId);

    }

}