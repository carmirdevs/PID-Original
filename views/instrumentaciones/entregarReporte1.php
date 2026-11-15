<?php
    $archivo = 'Reporte';
    $tituloFormulario = "Subir {$archivo} ".REPORTE_1;
    $action = get_url('instrumentaciones','entregarReporte1Bd',SESSION_ID);
    $textoBotonSubmit = 'Subir';
    require_once __DIR__.'/_formularioSubirArchivo.php';
?>