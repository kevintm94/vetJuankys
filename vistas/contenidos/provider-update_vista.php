<?php
    if ($_SESSION['rol_auth'] < 1 || $_SESSION['rol_auth'] > 2) {
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
?>
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR PROVEEDOR
    </h3>
    <p class="text-justify">
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo SERVERURL; ?>provider-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR PROVEEDOR</a>
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
    <?php
        require_once "./controladores/proveedorControlador.php";
        $ins_proveedor = new proveedorControlador();
        $datos_proveedor = $ins_proveedor->datos_proveedor_controlador("Unico", $pagina[1]);

        if ($datos_proveedor->rowCount() == 1) {
            $campos = $datos_proveedor->fetch();
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/proveedorAjax.php" method="POST" data-form="update" autocomplete="off">
        <input type="hidden" name="proveedor_id_up" value="<?php echo $pagina[1]; ?>">
        <fieldset>
            <legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="proveedor_nombre" class="bmd-label-floating">Nombre Completo<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,50}" class="form-control" name="proveedor_nombre_up" value="<?php echo $campos['Nombre']; ?>" id="proveedor_nombre" maxlength="50">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_telefono" class="bmd-label-floating">Teléfono<font COLOR="red">*</font></label>
                            <input type="text" pattern="[0-9()+]{7,8}" class="form-control" name="proveedor_telefono_up" value="<?php echo $campos['Telefono']; ?>" id="proveedor_telefono" maxlength="8">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_email" class="bmd-label-floating">Correo Electrónico</label>
                            <input type="email" class="form-control" name="proveedor_email_up" value="<?php echo $campos['Email']; ?>" id="proveedor_email" maxlength="50">
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="proveedor_direccion" class="bmd-label-floating">Dirección</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="proveedor_direccion_up" value="<?php echo $campos['Direccion']; ?>" id="proveedor_direccion" maxlength="150">
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