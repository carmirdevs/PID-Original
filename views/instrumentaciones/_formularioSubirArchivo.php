<?php $instrumentacion = $controlador->datosInstrumentacion; ?>
<br><br>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $tituloFormulario ?></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form autocomplete="off" enctype="multipart/form-data" role="form" method="POST"
                  action="<?php echo $action ?>">
                <div class="box-body">
                    <input type="hidden" name="instrumentacion_id" value="<?php echo $instrumentacion['id'] ?>" >

                    <div class="form-group">
                        <label>Carrera</label>
                        <input type="text" value="<?php echo $instrumentacion['nombre_carrera'] ?>" disabled="" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Materia</label>
                        <input type="text" value="<?php echo $instrumentacion['nombre_materia'] ?>" disabled="" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Periodo</label>
                        <input type="text" value="<?php echo $instrumentacion['nombre_periodo'] ?>" disabled="" class="form-control">
                    </div>

                    <div class="form-group">
                        <label><?php echo $archivo; ?> en Formato de Excel .XLSX</label>
                        <input required name="instrumentacion" type="file" placeholder="instrumentacion" class="form-control">
                    </div>


                </div>
                <!-- /.box-body -->
                <div class="box-footer" align="right">
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <button type="submit" name="" class="btn btn-default btn-block btn-flat"><?php echo $textoBotonSubmit ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <div class="col-md-4"></div>

</div>
<!-- /.box -->