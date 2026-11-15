<?php
    $archivo = 'Instrumentacion';
    $tituloFormulario = "Modificar {$archivo}";
    $action = get_url('instrumentaciones','modificar_instrumentacion_bd',SESSION_ID);
    $textoBotonSubmit = 'Modificar';
    require_once __DIR__.'/_formularioSubirArchivo.php';
?>