<?php
    if ($_SESSION['rol_auth'] < 1 || $_SESSION['rol_auth'] > 2) {
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
?>
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR CLIENTE
    </h3>
    <p class="text-justify">
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo SERVERURL; ?>client-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CLIENTE</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>client-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CLIENTES</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>client-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CLIENTE</a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/clienteControlador.php";
        $ins_cliente = new clienteControlador();
        $datos_cliente = $ins_cliente->datos_cliente_controlador("Unico", $pagina[1]);

        if ($datos_cliente->rowCount() == 1) {
            $campos = $datos_cliente->fetch();
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/clienteAjax.php" method="POST" data-form="update" autocomplete="off">
        <input type="hidden" name="cliente_id_up" value="<?php echo $pagina[1]; ?>">
        <fieldset>
            <legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="cliente_ci" class="bmd-label-floating">CI O NIT<font COLOR="red">*</font></label>
                            <input type="text" pattern="[0-9-]{7,10}" class="form-control" name="cliente_ci_up" value="<?php echo $campos['CI']; ?>" id="cliente_ci" maxlength="10" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="form-group">
                            <label for="cliente_nombre" class="bmd-label-floating">Nombres<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" class="form-control" name="cliente_nombre_up" value="<?php echo $campos['Nombres']; ?>" id="cliente_nombre" maxlength="35" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="form-group">
                            <label for="cliente_apellido" class="bmd-label-floating">Apellidos<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" class="form-control" name="cliente_apellido_up" value="<?php echo $campos['Apellidos']; ?>" id="cliente_apellido" maxlength="35" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cliente_telefono" class="bmd-label-floating">Teléfono</label>
                            <input type="text" pattern="[0-9()+]{7,8}" class="form-control" name="cliente_telefono_up" value="<?php echo $campos['Telefono']; ?>" id="cliente_telefono" maxlength="8">
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="cliente_direccion" class="bmd-label-floating">Dirección</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="cliente_direccion_up" value="<?php echo $campos['Direccion']; ?>" id="cliente_direccion" maxlength="150">
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