<?php


class controlador_carreras extends controlador {

    public function __construct(database $link){

        parent::__construct($link,'carreras');

        $this->configuracion();
    }

    private function configuracion(){

        $this->valida_campos_unicos = array(
        );

    }// end configuracion

    public function alta(){
        $this->inputs[] = $this->HTML->input('Carrera','nombre',4,
            'Carrera');

        $this->inputs[] = $this->HTML->submit('Registrar','carreras_alta_bd',4);
    }

    public function modifica(){
        parent::modifica();
        $this->inputs[] = $this->HTML->input('Carrera','nombre',4,
            'Carrera',$this->registro['nombre']);

        $this->inputs[] = $this->HTML->submit('Modificar','carreras_modifica_bd',4);

    }// end modifica

    public function lista(){

        // configuracion de el filtro para la lista
        $this->lista_usar_filtro = true;
        $this->inputs_filtro_lista_cols = 3;

        $this->filtro_lista_campos = array(
            'Carrera' => 'carreras.nombre'
        );
        // termina configuracion de el filtro para la lista

        $this->nombre_columnas_lista = array('ID','Carrera','Estatus');
        $this->columnas_lista = array('id','carreras.nombre','carreras.status');
        $this->joins_lista = '';

        parent::lista();

    }// end lista

}