<?php
    if ($_SESSION['rol_auth'] < 1 || $_SESSION['rol_auth'] > 2) {
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
?>
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR SERVICIO
    </h3>
    <p class="text-justify">
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo SERVERURL; ?>service-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR SERVICIO</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>service-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE SERVICIOS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>service-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR SERVICIO</a>
        </li>
    </ul>
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/servicioControlador.php";
        $ins_servicio = new servicioControlador();
        $datos_servicio = $ins_servicio->datos_servicio_controlador("Unico", $pagina[1]);

        if ($datos_servicio->rowCount() == 1) {
            $campos = $datos_servicio->fetch();
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/servicioAjax.php" method="POST" data-form="update" autocomplete="off">
        <input type="hidden" name="servicio_id_up" value="<?php echo $pagina[1]; ?>">
        <fieldset>
            <legend><i class="far fa-plus-square"></i> &nbsp; Información del servicio</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="servicio_codigo" class="bmd-label-floating">Código<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-Z0-9-]{1,10}" class="form-control" name="servicio_codigo_up" value="<?php echo $campos['Codigo']; ?>" id="servicio_codigo" maxlength="10" required>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="servicio_nombre" class="bmd-label-floating">Nombre<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,50}" class="form-control" name="servicio_nombre_up" value="<?php echo $campos['Nombre']; ?>" id="servicio_nombre" maxlength="50" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="servicio_preciov" class="bmd-label-floating">Precio<font COLOR="red">*</font></label>
                            <input type="number" min="0" max="1000" step="0.01" class="form-control" name="servicio_precio_up" value="<?php echo $campos['Precio']; ?>" id="servicio_preciov" maxlength="4" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="servicio_estado_up" class="bmd-label-floating">Estado<font COLOR="red">*</font></label>
                            <select class="form-control" name="servicio_estado_up">
                                <option value="1" <?php if ($campos['Estado'] == 1) { echo 'selected=""'; } ?>>Habilitado <?php if ($campos['Estado'] == 1) { echo '(Actual)'; } ?></option>
                                <option value="0" <?php if ($campos['Estado'] == 0) { echo 'selected=""'; } ?>>Deshabilitado <?php if ($campos['Estado'] == 0) { echo '(Actual)'; } ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="servicio_detalle" class="bmd-label-floating">Detalle</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control" name="servicio_detalle_up" value="<?php echo $campos['Detalle']; ?>" id="servicio_detalle" maxlength="190">
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <br><br><br>
        <p class="text-center" style="margin-top: 40px;">
            <button type="submit" class="btn btn-raised btn-success btn-sm"><i class="fas fa-sync-alt"></i> &nbsp; ACTUALIZAR</button>
        </p>
    </form>
    <?php
        } else {
    ?>
    <div class="alert alert-danger text-center" role="alert">
        <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
        <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
        <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
    </div>
    <?php 
        }
    ?>
</div>