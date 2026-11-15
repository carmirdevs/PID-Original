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
     * Variante con previsualización local (SheetJS) para Excel.
     * - Para .xlsx/.xls redirige a /preview_excel.php?file=<nombre>
     * - ?raw=1 fuerza URL directa (comportamiento previo).
     * - Otros tipos: URL directa a /archivos/<nombre>
     *
     * Requiere:
     *   - Definir RUTA_PROYECTO con la URL base (sin /archivos al final).
     *   - Colocar preview_excel.php y excel_raw.php accesibles por web.
     */
    public function ver_archivo($nombre_archivo){
        $nombre_archivo = basename($nombre_archivo);
        $base = rtrim(RUTA_PROYECTO, '/');
        $rutaPublica = $base . '/archivos/' . $nombre_archivo;

        $ext = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
        $esExcel = in_array($ext, ['xlsx', 'xls'], true);

        // Si Excel y no se solicita raw, usamos el visor local (SheetJS)
        if ($esExcel && !isset($_GET['raw'])) {
            $preview = $base . '/preview_excel.php?file=' . rawurlencode($nombre_archivo);
            if (function_exists('header_remove')) {
                @header_remove('Content-Type');
                @header_remove('Content-Disposition');
                @header_remove('Content-Length');
            }
            header('Cache-Control: no-store, no-cache, must-revalidate, private');
            header('Pragma: no-cache');
            header('Location: ' . $preview, true, 302);
            exit;
        }

        // Por defecto (y Excel con ?raw=1): redirección directa al archivo
        header("Location: $rutaPublica");
        exit;
    }
}
