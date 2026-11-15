<?php
    $datosInstrumentacion = $controlador->datosInstrumentacion;
    $archivosInstrumentacion = $controlador->archivosInstrumentacion;
    $nombre_completo_docente = "{$datosInstrumentacion['docente_nombre']} {$datosInstrumentacion['docente_apellido_p']} {$datosInstrumentacion['docente_apellido_m']}";
?>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Periodo</label>
            <input type="text" value="<?php echo $datosInstrumentacion['nombre_periodo'] ?>" disabled="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Carrera</label>
            <input type="text" value="<?php echo $datosInstrumentacion['nombre_carrera'] ?>" disabled="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Materia</label>
            <input type="text" value="<?php echo $datosInstrumentacion['nombre_materia'] ?>" disabled="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Docente</label>
            <input type="text" value="<?php echo $nombre_completo_docente; ?>" disabled="" class="form-control">
        </div>
    </div>
</div>

<div style="background-color: white; border-radius: 10px " class="table-responsive">
    <table class="table">

        <tbody>
        <?php if (count($archivosInstrumentacion) > 0) { ?>
            <tr>
                <th>Id</th>
                <th>Nombre Archivo</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th><p class="text-center">Acciones</p></th>
            </tr>
            <?php foreach ($archivosInstrumentacion as $archivo): ?>
                <tr>
                    <td><?= $archivo['id']; ?></td>
                    <td><?= $archivo['nombre_archivo']; ?></td>
                    <td><?= $archivo['fecha']; ?></td>
                    <td><?= $archivo['tipo_archivo']; ?></td>
                    <td align="center">
                        <a target="_blank" title="ver archvivo" href="<?php echo get_url('instrumentaciones',
                                'ver_archivo',SESSION_ID,'',
                                $archivo['id']) ?>"><i class="fa fa-file-pdf-o"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>

        <?php }else{ ?>
            <td align="center">
                <span class="glyphicon glyphicon-remove"></span> no existen archivos
            </td>
        <?php } // end if count ?>
        </tbody>

    </table>
</div>