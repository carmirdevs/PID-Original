<?php

class archivosServidor {

    public $errores;

    public function __construct(){
        $this->errores = new errores();
    }

    public function subir_archivo(array $extenciones_permitida, $archivo , string $nombre_archivo){
        $extencion_archivo = explode('.',$archivo['name']);
        $extencion_archivo = end($extencion_archivo);
        $extencion_archivo = strtolower($extencion_archivo);

        if (!in_array($extencion_archivo,$extenciones_permitida)){
            return $this->errores->datos(1,'Error, la extencion '.$extencion_archivo.' no esta permitida',
                __CLASS__,__LINE__,__FILE__,$extencion_archivo,__FUNCTION__);
        }

        $rutaArchivos = __DIR__.'/../archivos/';
        move_uploaded_file($archivo['tmp_name'], "$rutaArchivos/$nombre_archivo");
    }

    /**
     * Muestra archivos. Para .xlsx/.xls redirige a Office Web Viewer (inline).
     * Para otros tipos, mantiene el comportamiento anterior: redirección directa al archivo.
     * Si se incluye el parámetro ?raw=1, siempre sirve la URL directa (saltando el visor).
     */
    public function ver_archivo($nombre_archivo){
        // Seguridad básica sobre el nombre
        $nombre_archivo = basename($nombre_archivo);

        // Construye la URL pública al archivo en /archivos/
        // Nota: RUTA_PROYECTO debe ser absoluta y sin sufijo /archivos (por ejemplo: https://escolarizado.itsjrc.net/ )
        $base = rtrim(RUTA_PROYECTO, '/');
        $rutaPublica = $base . '/archivos/' . $nombre_archivo;

        // Detecta extensión
        $ext = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
        $esExcel = in_array($ext, ['xlsx', 'xls'], true);

        if ($esExcel && !isset($_GET['raw'])) {
            // Redirige al visor de Office con la URL pública del archivo
            $officeViewer = 'https://view.officeapps.live.com/op/view.aspx?src=' . rawurlencode($rutaPublica);

            if (function_exists('header_remove')) {
                @header_remove('Content-Type');
                @header_remove('Content-Disposition');
                @header_remove('Content-Length');
            }
            header('Cache-Control: no-store, no-cache, must-revalidate, private');
            header('Pragma: no-cache');
            header('Location: ' . $officeViewer, true, 302);
            exit;
        }

        // Comportamiento por defecto (incluye Excel si ?raw=1): mandar al archivo directo
        header("Location: $rutaPublica");
        exit;
    }
}
