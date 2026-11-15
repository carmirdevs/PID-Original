<?php


class instrumentaciones extends modelo {

    public function __construct(database $link){
        $tabla = __CLASS__;
        parent::__construct($link, $tabla);
    }

    public function obten_datos_instrumentacion(int $instrumentacion_id, array $columnas){
        $filtro = array('instrumentaciones.id' => $instrumentacion_id);

        $joins  = ' INNER JOIN  carreras ON carreras.id = instrumentaciones.carrera_id ';
        $joins .= ' INNER JOIN  materias ON materias.id = instrumentaciones.materia_id ';
        $joins .= ' INNER JOIN  periodos ON periodos.id = instrumentaciones.periodo_id ';
        $joins .= ' INNER JOIN  usuarios ON usuarios.id = instrumentaciones.usuario_id ';
        $joins .= ' INNER JOIN  grupos   ON grupos.id   = usuarios.grupo_id ';

        $resultado = $this->filtro_and($filtro,$columnas,'',$joins);

        if (isset($resultado['error'])){
            return $this->errores->datos(1,'Error, al obtener datos de la instrumentacion',__CLASS__,
                __LINE__,__FILE__,$resultado,__FUNCTION__);
        }

        if ($resultado['registros'] < 1){
            return array();
        }

        return $resultado['registros'][0];

    }

    public function obtenerDatosReporte(int $carreraId, int $periodoId, int $bloqueId, int $grupoId, string $tipoReporte)
    {
        $filtro = ['carrera_id' => $carreraId , 'periodo_id' => $periodoId,'bloque_id' => $bloqueId,'grupoalumnos_id' => $grupoId];

        if ($grupoId == -1 ) {
            unset($filtro['grupoalumnos_id']);
        }

        $columnas = ['bloques.nombre AS nombre_bloque','gruposalumnos.nombre AS nombre_grupo','materias.nombre AS nombre_materia','carreras.nombre AS nombre_carrera',
            'periodos.nombre AS nombre_periodo',$tipoReporte,
            "concat_ws(' ',usuarios.nombre,usuarios.apellido_p,usuarios.apellido_m) AS nombre_usuario"];

        $joins  = ' INNER JOIN usuarios ON usuarios.id = instrumentaciones.usuario_id ';
        $joins .= ' INNER JOIN carreras ON carreras.id = instrumentaciones.carrera_id ';
        $joins .= ' INNER JOIN periodos ON periodos.id = instrumentaciones.periodo_id ';
        $joins .= ' INNER JOIN materias ON materias.id = instrumentaciones.materia_id ';
        $joins .= ' INNER JOIN bloques        ON bloques.id        = instrumentaciones.bloque_id ';
        $joins .= ' INNER JOIN gruposalumnos  ON gruposalumnos.id  = instrumentaciones.grupoalumnos_id ';

        $resultado = $this->filtro_and($filtro,$columnas,'',$joins,'',' ORDER BY usuario_id ASC ');

        if (isset($resultado['error'])){
            return $this->errores->datos(1,'Error, al obtener datos para el reporte',__CLASS__,
                __LINE__,__FILE__,$resultado,__FUNCTION__);
        }

        if ($resultado['n_registros'] < 1) {
            return [];
        }

        $carrera = $resultado['registros'][0]['nombre_carrera'];
        $periodo = $resultado['registros'][0]['nombre_periodo'];
        $bloque = $resultado['registros'][0]['nombre_bloque'];
        $grupo = $resultado['registros'][0]['nombre_grupo'];

        $docentes = $this->genera_array_docentes($resultado['registros'],$tipoReporte);

        $datos = array(
            'nombre_carrera' => $carrera,
            'periodo' => $periodo,
            'bloque' => $bloque,
            'grupo' => $grupo,
            'total' => $resultado['n_registros'],
            'total_entregadas' => $docentes['total_entregadas'],
            'datos_docentes' => $docentes['docentes']
        );

        return $datos;
    }

    private function genera_array_docentes($instrumentaciones,$tipoReporte){
        $total_entregadas = 0;
        $total = 0;
        $entregadas = 0;
        $nombre = $instrumentaciones[0]['nombre_usuario'];

        $docentes = array();
        foreach ($instrumentaciones as $instrumentacion){

            if ($instrumentacion[$tipoReporte] != 'no entregada'){
                $total_entregadas++;
            }

            if ($instrumentacion['nombre_usuario'] == $nombre){
                $total++;
                if ($instrumentacion[$tipoReporte] != 'no entregada'){
                    $entregadas++;
                }

            }else{

                $docentes [] = array('nombre' => $nombre,'total'=> $total,'entregadas' => $entregadas,
                    'no_entregadas' => (int)($total-$entregadas) );

                $nombre = $instrumentacion['nombre_usuario'];
                $total = 1;
                if ($instrumentacion[$tipoReporte] != 'no entregada'){
                    $entregadas = 1;
                }else{
                    $entregadas = 0;
                }

            }// el else if principal

        }// end forech
        $docentes [] = array('nombre' => $nombre,'total'=> $total,'entregadas' => $entregadas,
            'no_entregadas' => (int)($total-$entregadas) );

        return array('docentes' => $docentes ,'total_entregadas' => $total_entregadas);
    }

}