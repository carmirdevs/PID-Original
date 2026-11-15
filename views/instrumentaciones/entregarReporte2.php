<?php
    $archivo = 'Reporte';
    $tituloFormulario = "Subir {$archivo} ".REPORTE_2;
    $action = get_url('instrumentaciones','entregarReporte2Bd',SESSION_ID);
    $textoBotonSubmit = 'Subir';
    require_once __DIR__.'/_formularioSubirArchivo.php';
?>