<?php


class archivos extends modelo {
    public $prueba = "jola";

    public function __construct(database $link){
        $tabla = __CLASS__;
        parent::__construct($link, $tabla);
    }

    public function buscarPorInstrumentacionId(int $instrumentacionId)
    {
        $filtro = array('archivos.instrumentacion_id' => $instrumentacionId);

        if (GRUPO_ID == 4) {
            $filtro['instrumentaciones.usuario_id'] = USUARIO_ID;
        }

        $columnas = ['archivos.id','nombre_archivo','tipo_archivo','fecha'];

        $joins  = ' INNER JOIN  instrumentaciones ON instrumentaciones.id = archivos.instrumentacion_id ';

        $resultado = $this->filtro_and($filtro,$columnas,'',$joins);

        if (isset($resultado['error'])){
            return $this->errores->datos(1,'Error, al obtener datos de la instrumentacion',__CLASS__,
                __LINE__,__FILE__,$resultado,__FUNCTION__);
        }
        
        return $resultado['registros'];
    }

    public function existenArchivosDeInstrumentacionId(int $instrumentacionId)
    {
        $filtro = array('instrumentacion_id' => $instrumentacionId);
        $resultado = $this->filtro_and($filtro);

        if (isset($resultado['error'])){
            return $this->errores->datos(1,'Error, al obtener datos de la tabla archivos',__CLASS__,__LINE__,__FILE__,$resultado,__FUNCTION__);
        }

        if ($resultado['n_registros'] > 0) {
            return true;
        }

        return false;
    }

    public function registrarArchivoInstrumentacion(int $instrumentacionId, string $nombreArchivo, string $tipo_archivo)
    {

        $filtro = array('instrumentacion_id' => $instrumentacionId,'nombre_archivo' => $nombreArchivo,'tipo_archivo' => $tipo_archivo);
        $resultado = $this->filtro_and($filtro);

        if ($resultado['n_registros'] == 0) {

            $registro['instrumentacion_id'] = $instrumentacionId;
            $registro['nombre_archivo'] = $nombreArchivo;
            $registro['tipo_archivo'] = $tipo_archivo;
            $registro['fecha'] = date('Y-m-d H:i:s');
            $registro['usuario_alta_id'] = USUARIO_ID;
            $registro['usuario_update_id'] = USUARIO_ID;
    
            $resultado = $this->alta_bd($registro);
    
            if (isset($resultado['error'])){
                return $this->errores->datos(1,'Error, al registrar el archivo de la instrumentacion',__CLASS__,
                    __LINE__,__FILE__,$resultado,__FUNCTION__);
            }

        }

    }

}