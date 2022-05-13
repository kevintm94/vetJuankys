
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-beta1/jquery.js"></script>
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR MEDICINA
    </h3>
    <p class="text-justify">
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>medicine-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR MEDICINA</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>medicine-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE MEDICINAS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>medicine-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR MEDICINA</a>
        </li>
    </ul>
</div>

<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/medicinaAjax.php" method="POST" data-form="save" autocomplete="off">
        <fieldset>
            <legend><i class="far fa-plus-square"></i> &nbsp; Información de la medicina</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="medicina_codigo" class="bmd-label-floating">Código<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-Z0-9-]{1,10}" class="form-control" name="medicina_codigo_reg" id="medicina_codigo" maxlength="10"required>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="medicina_nombre" class="bmd-label-floating">Nombre<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ,.]{1,50}" class="form-control" name="medicina_nombre_reg" id="medicina_nombre" maxlength="50" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <?php
                            require_once "./controladores/medicinaControlador.php";
                            $ins_medicina = new medicinaControlador();

                            echo $ins_medicina->proveedores_medicina_controlador("Nuevo", 0);
                        ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="medicina_detalle" class="bmd-label-floating">Detalle</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control" name="medicina_detalle_reg" id="medicina_detalle" maxlength="190">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="medicina_laboratorio" class="bmd-label-floating">Laboratorio<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,50}" class="form-control" name="medicina_laboratorio_reg" id="medicina_laboratorio" maxlength="50" required>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <br><br><br>
        <fieldset>
            <legend><i class="fa fa-prescription-bottle-alt"></i> &nbsp; Presentaciones de la medicina <button id="adicional" name="adicional" type="button" class="btn btn-raised btn-success btn-sm"><i class="fas fa-plus"></i></button></legend>
                <div class="container-fluid">
                    <table class="table"  id="tabla">
                        <tr class="fila-fija">
                            <td>
                                <input type="text" pattern="[a-zA-Z0-9-]{1,10}" class="form-control" placeholder="Embase*" name="presentacion_embase_reg[]" maxlength="10"required>
                            </td>
                            <td>
                                <input type="number" min="1" max="1000" class="form-control" placeholder="Contenido*" name="presentacion_contenido_reg[]" maxlength="4" required>
                            </td>
                            <td>
                                <select class="form-control" name="presentacion_medida_reg[]">
                                    <option value="" selected="" disabled="">Seleccione una medida*</option>
                                    <option value="mg.">Miligramos</option>
                                    <option value="g.">Gramos</option>
                                    <option value="Kg.">Kilogramos</option>
                                    <option value="ml.">Mililitros</option>
                                    <option value="L.">Litros</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" min="0" max="1000" step="0.01" class="form-control" placeholder="Precio Venta Und.*" name="presentacion_preciov_reg[]" maxlength="4" required>
                            </td>
                            <td class="eliminar" width="10%" style="display:none;">
                                <button type="button" class="btn btn-raised btn-danger btn-lgs btn-block"><i class="fas fa-minus"></i></button>
                            </td>
                        </tr>
                    </table>
                </div>
        </fieldset>
        <br><br>
        <p class="text-center" style="margin-top: 40px;">
            <button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR</button>
            &nbsp; &nbsp;
            <button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; GUARDAR</button>
            
        </p>
    </form>
</div>