<?php
    if ($_SESSION['rol_auth'] < 1 || $_SESSION['rol_auth'] > 2) {
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
?>
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR CURSO
    </h3>
    <p class="text-justify">
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo SERVERURL; ?>course-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CURSO</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>course-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CURSOS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>course-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CURSO</a>
        </li>
    </ul>
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/cursoControlador.php";
        $ins_curso = new cursoControlador();
        $datos_curso = $ins_curso->datos_curso_controlador("Unico", $pagina[1]);

        if ($datos_curso->rowCount() == 1) {
            $campos = $datos_curso->fetch();
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/cursoAjax.php" method="POST" data-form="update" autocomplete="off">
        <input type="hidden" name="curso_id_up" value="<?php echo $pagina[1]; ?>">
        <fieldset>
            <legend><i class="far fa-plus-square"></i> &nbsp; Información del curso</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="curso_codigo" class="bmd-label-floating">Código<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-Z0-9-]{1,10}" class="form-control" name="curso_codigo_up" value="<?php echo $campos['Codigo']; ?>" id="curso_codigo" maxlength="10" required>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="curso_nombre" class="bmd-label-floating">Nombre<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,50}" class="form-control" name="curso_nombre_up" value="<?php echo $campos['Nombre']; ?>" id="curso_nombre" maxlength="50" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="curso_sesiones" class="bmd-label-floating">Número de sesiones<font COLOR="red">*</font></label>
                            <input type="number" min="0" max="100" class="form-control" name="curso_sesiones_up" value="<?php echo $campos['Sesiones']; ?>" id="curso_sesiones" maxlength="3" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="curso_precio" class="bmd-label-floating">Precio<font COLOR="red">*</font></label>
                            <input type="number" min="0" max="1000" step="0.01" class="form-control" name="curso_precio_up" value="<?php echo $campos['Precio']; ?>" id="curso_precio" maxlength="4" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="curso_estado_up" class="bmd-label-floating">Estado<font COLOR="red">*</font></label>
                            <select class="form-control" name="curso_estado_up">
                                <option value="1" <?php if ($campos['Estado'] == 1) { echo 'selected=""'; } ?>>Habilitado <?php if ($campos['Estado'] == 1) { echo '(Actual)'; } ?></option>
                                <option value="0" <?php if ($campos['Estado'] == 0) { echo 'selected=""'; } ?>>Deshabilitado <?php if ($campos['Estado'] == 0) { echo '(Actual)'; } ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="curso_detalle" class="bmd-label-floating">Detalle</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control" name="curso_detalle_up" value="<?php echo $campos['Detalle']; ?>" id="curso_detalle" maxlength="190">
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