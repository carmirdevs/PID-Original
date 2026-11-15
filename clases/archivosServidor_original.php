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

    public function ver_archivo($nombre_archivo){

        $rutaArchivo = RUTA_PROYECTO.'archivos/'.$nombre_archivo;
        header("Location: $rutaArchivo");
        exit;
    }

}