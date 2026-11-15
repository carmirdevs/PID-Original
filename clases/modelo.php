<?php
    class modelo{

        public $link;

        private $tabla;
        public $errores;

        /* Funciones Publicas */

        public function __construct(database $link,$tabla){
            $this->errores = new errores();
            $this->tabla = $tabla;
            $this->link = $link;

        }

        public function alta_bd(array $array_datos_insert){
            if (count($array_datos_insert) === 0){
                return $this->errores->datos(1,'Error, el array de datos no puede estar vacio',
                    __CLASS__,__LINE__,__FILE__,$array_datos_insert,__FUNCTION__);
            }

            $campos = '';
            $values = '';

            foreach ($array_datos_insert as $campo => $value ){
                $campos .= ' '.$campo.' ,';
                $values .= ' \''.$value.'\' ,';
            }

            $campos = trim($campos,',');
            $values = trim($values,',');


            $query_string = sprintf('INSERT INTO %s (%s) VALUES (%s)',$this->tabla,$campos,$values);

            $resultado = $this->ejecuta_query($query_string);

            if (isset($resultado['error'])){
                return $this->errores->datos(1,'Error, al ejecutar query',__CLASS__,
                    __LINE__,__FILE__,$resultado,__FUNCTION__);
            }

            return $resultado;

        }

        public function elimina_con_filtro_and(array $filtro){
            $filtro = $this->genera_filtro_and($filtro);

            if (isset($filtro['error'])){
                return $this->errores->datos(1,'Error, al generar filtros',__CLASS__,
                    __LINE__,__FILE__,$filtro,__FUNCTION__);
            }

            $query_string = sprintf("DELETE FROM %s %s", $this->tabla, $filtro);

            $resultado = $this->ejecuta_query($query_string);

            if (isset($resultado['error'])){
                return $this->errores->datos(1,'Error, al ejecutar query',__CLASS__,
                    __LINE__,__FILE__,$resultado,__FUNCTION__);
            }

            return $resultado;
        }

        public function elimina_por_id(int $id){
            $filtro = $this->genera_filtro_id($id);

            if (isset($filtro['error'])){
                return $this->errores->datos(1,'Error, al generar filtro id',
                    __CLASS__,__LINE__,__FILE__,$filtro,__FUNCTION__);
            }

            $valida_id = $this->valida_registro_id($id);

            if (isset($valida_id['error'])){
                return $this->errores->datos(1,'Error, no se encontro el registro id',
                    __CLASS__,__LINE__,__FILE__,$valida_id,__FUNCTION__);
            }

            $resultado = $this->elimina_con_filtro_and($filtro);

            if (isset($resultado['error'])){
                return $this->errores->datos(1,'Error, al eliminar datos',
                    __CLASS__,__LINE__,__FILE__,$resultado,__FUNCTION__);
            }

            return $resultado;
        }

        public function filtro_and(array $filtro,array $columnas_base=array(),
                                   string $filtro_custom='',string $joins = '',string $limit = '',string $order = ''){
            $filtro = $this->genera_filtro_and($filtro);

            if (isset($filtro['error'])){
                return $this->errores->datos(1,'Error, al generar filtros',__CLASS__,
                    __LINE__,__FILE__,$filtro,__FUNCTION__);
            }

            $columnas_base = $this->genera_columnas_base($columnas_base);

            $query_string = sprintf("SELECT %s FROM %s %s %s %s %s %s",
                $columnas_base, $this->tabla, $joins , $filtro, $filtro_custom,$order,$limit);

            $resultado = $this->ejecuta_query($query_string);

            if (isset($resultado['error'])){
                return $this->errores->datos(1,'Error, al ejecutar query',__CLASS__,
                    __LINE__,__FILE__,$resultado,__FUNCTION__);
            }

            return $resultado;


        }

        public function obten_numero_registros(array $filtro, string $filtro_custom='',string $joins = ''){
            return $this->filtro_and($filtro,array('count(*) AS n_registros'),$filtro_custom,
                $joins)['registros'][0]['n_registros'];
        }

        public function modifica_con_filtro_and(array $filtro,array $array_datos_modifica){
            $filtro = $this->genera_filtro_and($filtro);

            if (isset($filtro['error'])){
                return $this->errores->datos(1,'Error, al obtener filtros',
                    __CLASS__,__LINE__,__FILE__,$filtro,__FUNCTION__);
            }

            if (count($array_datos_modifica) === 0){
                return $this->errores->datos(1,'Error, el array de datos no puede estar vacio',
                    __CLASS__,__LINE__,__FILE__,$array_datos_modifica,__FUNCTION__);
            }

            $set_string = '';

            foreach ($array_datos_modifica as $campo => $value ){
                $set_string .= ' '.$campo.' = \''.$value.'\' ,';
            }

            $set_string = trim($set_string,',');

            $query_string = sprintf('UPDATE %s SET %s %s',$this->tabla,$set_string,$filtro);

            $resultado = $this->ejecuta_query($query_string);

            if (isset($resultado['error'])){
                return $this->errores->datos(1,'Error, al ejecutar query',__CLASS__,
                    __LINE__,__FILE__,$resultado,__FUNCTION__);
            }

            return $resultado;

        }

        public function modifica_por_id(int $id,array $array_datos_modifica){
            $filtro = $this->genera_filtro_id($id);

            if (isset($filtro['error'])){
                return $this->errores->datos(1,'Error, al generar filtro id',
                    __CLASS__,__LINE__,__FILE__,$filtro,__FUNCTION__);
            }

            $valida_id = $this->valida_registro_id($id);

            if (isset($valida_id['error'])){
                return $this->errores->datos(1,'Error, no se encontro el registro id',
                    __CLASS__,__LINE__,__FILE__,$valida_id,__FUNCTION__);
            }

            $resultado = $this->modifica_con_filtro_and($filtro,$array_datos_modifica);

            if (isset($resultado['error'])){
                return $this->errores->datos(1,'Error, al modificar datos',
                    __CLASS__,__LINE__,__FILE__,$resultado,__FUNCTION__);
            }

            return $resultado;


        }

        public function obten_data(int $id,array $columnas_base=array()){
            $resultado = $this->obten_por_id($id,$columnas_base);
            if (isset($resultado['error'])){
                return $this->errores->datos(1,'Error, al obtener datos',
                    __CLASS__,__LINE__,__FILE__,$resultado,__FUNCTION__);
            }

            if ((int)$resultado['n_registros'] === 0){
                return $this->errores->datos(1,'Error, no se encontro registro con el id ='.$id,
                    __CLASS__,__LINE__,__FILE__,$resultado,__FUNCTION__);
            }

            return $resultado['registros'][0];

        }

        public function obten_por_id(int $id,array $columnas_base=array()){

            $filtro = $this->genera_filtro_id($id);

            if (isset($filtro['error'])){
                return $this->errores->datos(1,'Error, al generar filtro id',
                    __CLASS__,__LINE__,__FILE__,$filtro,__FUNCTION__);
            }

            $resultado = $this->filtro_and($filtro,$columnas_base);

            if (isset($resultado['error'])){
                return $this->errores->datos(1,'Error, al obtener datos',
                    __CLASS__,__LINE__,__FILE__,$resultado,__FUNCTION__);
            }

            return $resultado;
        }

        /* Funciones Privadas */

        private function ejecuta_query(string $query_string){
            $this->link->query($query_string);

            if ($query_string === '' || is_null($query_string)){
                return $this->errores->datos(1,'Error, el string query no puede estar vacio',
                    __CLASS__,__LINE__,__FILE__,$query_string,__FUNCTION__);
            }

            $resultado_explode = explode(' ',$query_string);

            if (count($resultado_explode) === 0){
                return $this->errores->datos(1,'Error, al obtener el tipo de consulta',
                    __CLASS__,__LINE__,__FILE__,$query_string,__FUNCTION__);
            }

            $tipo_consulta = $resultado_explode[0];

            try {

                if ($tipo_consulta === 'SELECT'){
                    $n_registros = $this->link->rowCount();
                    $resultado = $this->link->registros();
                    return array('registros' => $resultado,'n_registros'=>$n_registros);
                }

                if ($tipo_consulta === 'UPDATE'){
                    $this->link->execute();
                    return array('mensaje' => 'registro modificado');
                }

                if ($tipo_consulta === 'INSERT'){
                    $this->link->execute();
                    $registro_id = $this->link->dbh->lastInsertId();
                    return array(
                        'mensaje' => 'registro insertado',
                        'registro_id' =>$registro_id
                    );
                }

                if ($tipo_consulta === 'DELETE'){
                    $this->link->execute();
                    return array('mensaje' => 'registro eliminado');
                }

                return $this->errores->datos(1,'Error, la consulta no es valida',
                    __CLASS__,__LINE__,__FILE__,$query_string,__FUNCTION__);


            } catch (PDOException $e) {
                $error_sql = $e->getMessage();
                return $this->errores->datos(1,'Error, en consulta sql',__CLASS__,
                    __LINE__,__FILE__,'<br><br>'.$error_sql.'<br><br>'.$query_string,__FUNCTION__);
            }

        }

        private function genera_columnas_base(array $columnas){
            $resultado = ' * ';
            if (count($columnas) !== 0){
                $resultado = '';
                foreach ($columnas as $columna){
                    $resultado .= ' '.$columna.' ,';
                }
                $resultado = trim($resultado, ',');
                $resultado = trim($resultado, ' ');
            }
            return $resultado;
        }

        private function genera_filtro_and(array $filtro){
            if (count($filtro) === 0){
                return 'WHERE 1=1';
            }
            $strin_filtro= '';
            foreach ($filtro as $campo => $valor){
                $strin_filtro .= ' '.$campo.' = \''.$valor.'\' AND';
            }
            $strin_filtro = trim($strin_filtro, 'AND');

            return 'WHERE '.$strin_filtro;
        }

        private function genera_filtro_id(int $id):array {
            if ($id <= 0){
                return $this->errores->datos(1,'Error, el id debe ser un entero positivo',
                    __CLASS__,__LINE__,__FILE__,$id,__FUNCTION__);
            }
            $filtro = array($this->tabla.'.id' => $id);
            return $filtro;
        }

        private function valida_registro_id(int $id){

            $resultado = $this->obten_data($id);
            if (isset($resultado['error'])){
                return $this->errores->datos(1,'Error, al validar el id del registro',
                    __CLASS__,__LINE__,__FILE__,$id,__FUNCTION__);
            }

        }

    }
