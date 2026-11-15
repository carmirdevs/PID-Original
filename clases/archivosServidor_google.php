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
     * Visualización con Google Docs Viewer (Google Sheets) para Excel.
     * - Para .xlsx/.xls redirige a Google Viewer con la URL pública del archivo.
     * - ?raw=1 fuerza URL directa (comportamiento previo).
     * - Otros tipos: URL directa a /archivos/<nombre>
     *
     * Requisitos:
     *   - RUTA_PROYECTO debe ser la URL base pública del sitio (sin /archivos al final).
     *   - Los archivos de /archivos/ deben ser accesibles por URL pública o por lo menos sin
     *     requerir cookies/headers de sesión, ya que el visor de Google NO enviará tus cookies.
     */
    public function ver_archivo($nombre_archivo){
        // Sanitiza nombre para evitar path traversal
        $nombre_archivo = basename($nombre_archivo);

        // Construye la URL pública al archivo en /archivos/
        $base = rtrim(RUTA_PROYECTO, '/');
        $rutaPublica = $base . '/archivos/' . $nombre_archivo;

        // Detecta extensión
        $ext = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
        $esExcel = in_array($ext, ['xlsx', 'xls'], true);

        if ($esExcel && !isset($_GET['raw'])) {
            // Google Docs Viewer (Sheets)
            // Opción A (recomendada): gview endpoint
            $gviewer = 'https://docs.google.com/gview?embedded=1&url=' . rawurlencode($rutaPublica);

            // Limpia headers conflictivos y redirige
            if (function_exists('header_remove')) {
                @header_remove('Content-Type');
                @header_remove('Content-Disposition');
                @header_remove('Content-Length');
            }
            header('Cache-Control: no-store, no-cache, must-revalidate, private');
            header('Pragma: no-cache');
            header('Location: ' . $gviewer, true, 302);
            exit;
        }

        // Por defecto (y Excel con ?raw=1): redirección directa al archivo
        header("Location: $rutaPublica");
        exit;
    }
}
