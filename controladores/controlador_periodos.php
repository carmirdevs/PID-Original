<?php


class controlador_periodos extends controlador {

    public function __construct(database $link){

        parent::__construct($link,'periodos');

        $this->configuracion();
    }

    private function configuracion(){

        $this->valida_campos_unicos = array(
        );

    }// end configuracion

    public function alta(){
        $this->inputs[] = $this->HTML->input('Periodo','nombre',4,
            'Periodo');

        $this->inputs[] = $this->HTML->submit('Registrar','periodos_alta_bd',4);
    }

    public function modifica(){
        parent::modifica();
        $this->inputs[] = $this->HTML->input('Periodo','nombre',4,
            'Periodo',$this->registro['nombre']);

        $this->inputs[] = $this->HTML->submit('Modificar','periodos_modifica_bd',4);

    }// end modifica

    public function lista(){

        // configuracion de el filtro para la lista
        $this->lista_usar_filtro = true;
        $this->inputs_filtro_lista_cols = 3;

        $this->filtro_lista_campos = array(
            'Periodo' => 'periodos.nombre'
        );
        // termina configuracion de el filtro para la lista

        $this->nombre_columnas_lista = array('ID','Periodo','Estatus');
        $this->columnas_lista = array('id','periodos.nombre','periodos.status');
        $this->joins_lista = '';

        parent::lista();

    }// end lista

}