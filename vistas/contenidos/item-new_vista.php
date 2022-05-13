
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ITEM
    </h3>
    <p class="text-justify">
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>item-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ITEM</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ITEMS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ITEM</a>
        </li>
    </ul>
</div>

<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/itemAjax.php" method="POST" data-form="save" autocomplete="off">
        <fieldset>
            <legend><i class="far fa-plus-square"></i> &nbsp; Información del item</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="item_codigo" class="bmd-label-floating">Código<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-Z0-9-]{1,10}" class="form-control" name="item_codigo_reg" id="item_codigo" maxlength="10"required>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="item_nombre" class="bmd-label-floating">Nombre<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,50}" class="form-control" name="item_nombre_reg" id="item_nombre" maxlength="50" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="item_stock" class="bmd-label-floating">Stock<font COLOR="red">*</font></label>
                            <input type="number" min="0" max="1000" class="form-control" name="item_stock_reg" id="item_stock" maxlength="4" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="item_precioc" class="bmd-label-floating">Precio Compra Unidad</label>
                            <input type="number" min="0" max="1000" step="0.01" class="form-control" name="item_precioc_reg" id="item_precioc" maxlength="4">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="item_preciov" class="bmd-label-floating">Precio Venta Unidad<font COLOR="red">*</font></label>
                            <input type="number" min="0" max="1000" step="0.01" class="form-control" name="item_preciov_reg" id="item_preciov" maxlength="4" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <?php
                            require_once "./controladores/itemControlador.php";
                            $ins_item = new itemControlador();

                            echo $ins_item->proveedores_item_controlador("Nuevo", 0);
                        ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="item_detalle" class="bmd-label-floating">Detalle</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control" name="item_detalle_reg" id="item_detalle" maxlength="190">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="item_fabricante" class="bmd-label-floating">Fabricante o Marca<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,50}" class="form-control" name="item_fabricante_reg" id="item_fabricante" maxlength="50" required>
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