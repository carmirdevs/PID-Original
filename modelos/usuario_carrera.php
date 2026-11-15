<?php


class usuario_carrera extends modelo {

    public function __construct(database $link){
        $tabla = __CLASS__;
        parent::__construct($link, $tabla);
    }

}