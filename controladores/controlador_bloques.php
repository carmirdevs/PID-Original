<?php


class controlador_bloques extends controlador {

    public function __construct(database $link){

        parent::__construct($link,'bloques');

        $this->configuracion();
    }

    private function configuracion(){

        $this->valida_campos_unicos = array(
            'Bloque' => 'nombre'
        );

    }

    public function alta(){
        $this->inputs[] = $this->HTML->input('Bloque','nombre',4,'Bloque');

        $this->inputs[] = $this->HTML->submit('Registrar','bloques_alta_bd',4);
    }

    public function modifica(){
        parent::modifica();
        $this->inputs[] = $this->HTML->input('Bloque','nombre',4,'Bloque',$this->registro['nombre']);


        $this->inputs[] = $this->HTML->submit('Modificar','bloques_modifica_bd',4);

    }

    public function lista(){

        $this->lista_usar_filtro = true;
        $this->inputs_filtro_lista_cols = 4;
        $this->filtro_lista_campos = array(
            'Bloque' => 'bloques.nombre'
        );

        $this->nombre_columnas_lista = array('ID','Bloque','Estatus');
        $this->columnas_lista = array('bloques.id','bloques.nombre','bloques.status');
        $this->joins_lista = '';

        parent::lista();

    }
}