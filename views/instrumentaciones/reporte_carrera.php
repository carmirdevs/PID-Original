<?php
    $inputs = $controlador->inputs;
?>
<?php echo $HTML->hr(); ?>
    <form target="_blank" autocomplete="off" role="form" method="POST"
          action="<?php echo get_url($controlador->tabla,'reporte_carrera_bd',SESSION_ID) ?>">
        <div class="row">
            <?php
            foreach ($inputs as $input) {
                echo $input;
            }
            ?>
        </div>
    </form>
<?php echo $HTML->hr(); ?>