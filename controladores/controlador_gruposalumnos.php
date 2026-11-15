<?php


class controlador_gruposalumnos extends controlador {

    public function __construct(database $link){

        parent::__construct($link,'gruposalumnos');

        $this->configuracion();
    }

    private function configuracion(){

        $this->valida_campos_unicos = array(
            'Grupo Alumnos' => 'nombre'
        );

    }

    public function alta(){
        $this->inputs[] = $this->HTML->input('Grupo Alumnos','nombre',4,'Grupo Alumnos');

        $this->inputs[] = $this->HTML->submit('Registrar','gruposalumnos_alta_bd',4);
    }

    public function modifica(){
        parent::modifica();
        $this->inputs[] = $this->HTML->input('Grupo Alumnos','nombre',4,'Grupo Alumnos',$this->registro['nombre']);


        $this->inputs[] = $this->HTML->submit('Modificar','gruposalumnos_modifica_bd',4);

    }

    public function lista(){

        $this->lista_usar_filtro = true;
        $this->inputs_filtro_lista_cols = 4;
        $this->filtro_lista_campos = array(
            'Grupo Alumnos' => 'gruposalumnos.nombre'
        );

        $this->nombre_columnas_lista = array('ID','Grupo Alumnos','Estatus');
        $this->columnas_lista = array('gruposalumnos.id','gruposalumnos.nombre','gruposalumnos.status');
        $this->joins_lista = '';

        parent::lista();

    }
}