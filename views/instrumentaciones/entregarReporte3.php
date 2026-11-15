<?php
    $archivo = 'Reporte';
    $tituloFormulario = "Subir {$archivo} ".REPORTE_3;
    $action = get_url('instrumentaciones','entregarReporte3Bd',SESSION_ID);
    $textoBotonSubmit = 'Subir';
    require_once __DIR__.'/_formularioSubirArchivo.php';
?>