
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR SERVICIO
    </h3>
    <p class="text-justify">
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>service-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR SERVICIO</a>
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
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/servicioAjax.php" method="POST" data-form="save" autocomplete="off">
        <fieldset>
            <legend><i class="far fa-plus-square"></i> &nbsp; Información del servicio</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="servicio_codigo" class="bmd-label-floating">Código<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-Z0-9-]{1,10}" class="form-control" name="servicio_codigo_reg" id="servicio_codigo" maxlength="10"required>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="servicio_nombre" class="bmd-label-floating">Nombre<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,50}" class="form-control" name="servicio_nombre_reg" id="servicio_nombre" maxlength="50" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="servicio_precio" class="bmd-label-floating">Precio<font COLOR="red">*</font></label>
                            <input type="number" min="0" max="1000" step="0.1" class="form-control" name="servicio_precio_reg" id="servicio_precio" maxlength="4" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="servicio_detalle" class="bmd-label-floating">Detalle</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control" name="servicio_detalle_reg" id="servicio_detalle" maxlength="190">
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <br><br><br>
        <p class="text-center" style="margin-top: 40px;">
            <button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR</button>
            &nbsp; &nbsp;
            <button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; GUARDAR</button>
        </p>
    </form>
</div>