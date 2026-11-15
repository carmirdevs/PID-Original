<?php
    $archivo = 'Instrumentacion';
    $tituloFormulario = "Subir {$archivo}";
    $action = get_url('instrumentaciones','subir_instrumentacion_bd',SESSION_ID);
    $textoBotonSubmit = 'Subir';
    require_once __DIR__.'/_formularioSubirArchivo.php';
?>

