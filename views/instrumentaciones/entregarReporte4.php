<?php
    $archivo = 'Reporte';
    $tituloFormulario = "Subir {$archivo} ".REPORTE_4;
    $action = get_url('instrumentaciones','entregarReporte4Bd',SESSION_ID);
    $textoBotonSubmit = 'Subir';
    require_once __DIR__.'/_formularioSubirArchivo.php';
?>