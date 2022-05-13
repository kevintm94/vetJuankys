<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR PROVEEDOR
    </h3>
    <p class="text-justify">
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>provider-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR PROVEEDOR</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>provider-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE PROVEEDORES</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>provider-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR PROVEEDOR</a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/proveedorAjax.php" method="POST" data-form="save" autocomplete="off">
        <fieldset>
            <legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="proveedor_nombre" class="bmd-label-floating">Nombre Completo<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,50}" class="form-control" name="proveedor_nombre_reg" id="proveedor_nombre" maxlength="50">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_telefono" class="bmd-label-floating">Teléfono<font COLOR="red">*</font></label>
                            <input type="text" pattern="[0-9()+]{7,8}" class="form-control" name="proveedor_telefono_reg" id="proveedor_telefono" maxlength="8">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_email" class="bmd-label-floating">Correo Electrónico</label>
                            <input type="email" class="form-control" name="proveedor_email_reg" id="proveedor_email" maxlength="50">
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="proveedor_direccion" class="bmd-label-floating">Dirección</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="proveedor_direccion_reg" id="proveedor_direccion" maxlength="150">
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