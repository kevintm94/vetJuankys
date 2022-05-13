<?php
    if ($_SESSION['rol_auth'] < 1 || $_SESSION['rol_auth'] > 2) {
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
?>
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR MEDICINA
    </h3>
    <p class="text-justify">
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo SERVERURL; ?>medicine-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR MEDICINA</a>
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
    <?php
        require_once "./controladores/medicinaControlador.php";
        $ins_medicina = new medicinaControlador();
        $datos_medicina = $ins_medicina->datos_medicina_controlador("Unico", $pagina[1]);

        if ($datos_medicina->rowCount() == 1) {
            $campos = $datos_medicina->fetch();
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/medicinaAjax.php" method="POST" data-form="update" autocomplete="off">
        <input type="hidden" name="medicina_id_up" value="<?php echo $pagina[1]; ?>">
        <fieldset>
            <legend><i class="far fa-plus-square"></i> &nbsp; Información de la medicina</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="medicina_codigo" class="bmd-label-floating">Código<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-Z0-9-]{1,10}" class="form-control" name="medicina_codigo_up" value="<?php echo $campos['Codigo']; ?>" id="medicina_codigo" maxlength="10" required>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="medicina_nombre" class="bmd-label-floating">Nombre<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 .,]{1,50}" class="form-control" name="medicina_nombre_up" value="<?php echo $campos['Nombre']; ?>" id="medicina_nombre" maxlength="50" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <?php
                            require_once "./controladores/medicinaControlador.php";
                            $ins_medicina = new medicinaControlador();
    
                            echo $ins_medicina->proveedores_medicina_controlador("Actualizar", $campos['IdProveedores']);
                        ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="medicina_detalle" class="bmd-label-floating">Detalle</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control" name="medicina_detalle_up" value="<?php echo $campos['Detalle']; ?>" id="medicina_detalle" maxlength="190">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="medicina_laboratorio" class="bmd-label-floating">Laboratorio<font COLOR="red">*</font></label>
                            <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,50}" class="form-control" name="medicina_laboratorio_up" value="<?php echo $campos['Laboratorio']; ?>" id="medicina_laboratorio" maxlength="50">
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <br><br><br>
        <fieldset>
            <legend><i class="fa fa-prescription-bottle-alt"></i> &nbsp; Presentaciones de la medicina <button id="adicional_up" name="adicional_up" type="button" class="btn btn-raised btn-success btn-sm"><i class="fas fa-plus"></i></button></legend>
                <div class="container-fluid">
                    <table class="table"  id="tabla">
                        <thead>
                            <tr>
                                <th style="color:#000000">Embase</th>
                                <th style="color:#000000">Contenido</th>
                                <th style="color:#000000">Medida</th>
                                <th style="color:#000000">Precio Venta Und.</th>
                            </tr>
                        </thead>
                    <?php
                        require_once "./controladores/medicinaControlador.php";
                        $ins_presentaciones = new medicinaControlador();
                        $datos_presentaciones = $ins_presentaciones->datos_presentacion_medicina_controlador("Unico", $pagina[1]);

                        if ($datos_presentaciones->rowCount() > 0) {
                            $presentaciones = $datos_presentaciones->fetchAll();
                            $porciones = explode(" ", $presentaciones[0]["Nombre"]);
                            //echo('<pre>');
                            //var_dump($datos_presentaciones);
                            //echo('</pre>');
                            $nro = sizeof($presentaciones);
                            for ($i=0; $i < $nro; $i++) { 
                                $porciones = explode(" ", $presentaciones[$i]["Nombre"]);
                    ?>
                        <tr style="background-color:#94b9d4">
                            <td style="display:none">
                                <input type="hidden" name="presentacion_id_up[]" value="<?php echo $ins_presentaciones->encryption($presentaciones[$i]["IdPresentaciones"]); ?>">          
                            </td>
                            <td>
                                <input type="text" pattern="[a-zA-Z0-9-]{1,10}" class="form-control" placeholder="Embase*" name="presentacion_embase_up[]" value="<?php echo $porciones[0]; ?>" maxlength="10"required>
                            </td>
                            <td>
                                <input type="number" min="1" max="1000" class="form-control" placeholder="Contenido*" name="presentacion_contenido_up[]" value="<?php echo $porciones[1]; ?>" maxlength="4" required>
                            </td>
                            <td>
                                <select class="form-control" name="presentacion_medida_up[]">
                                    <option value="mg." <?php if ($porciones[2] == "mg.") { echo 'selected=""'; } ?>>Miligramos<?php if ($porciones[2] == "mg.") { echo '(Actual)'; } ?></option>
                                    <option value="g." <?php if ($porciones[2] == "g.") { echo 'selected=""'; } ?>>Gramos<?php if ($porciones[2] == "g.") { echo '(Actual)'; } ?></option>
                                    <option value="Kg." <?php if ($porciones[2] == "Kg.") { echo 'selected=""'; } ?>>Kilogramos<?php if ($porciones[2] == "Kg.") { echo '(Actual)'; } ?></option>
                                    <option value="ml." <?php if ($porciones[2] == "ml.") { echo 'selected=""'; } ?>>Mililitros<?php if ($porciones[2] == "ml.") { echo '(Actual)'; } ?></option>
                                    <option value="l." <?php if ($porciones[2] == "l.") { echo 'selected=""'; } ?>>Litros<?php if ($porciones[2] == "l.") { echo '(Actual)'; } ?></option>
                                </select>
                            </td>
                            <td>
                                <input type="number" min="0" max="1000" step="0.01" class="form-control" placeholder="Precio Venta Und.*" name="presentacion_preciov_up[]" value="<?php echo $presentaciones[$i]["PrecioVenta"];?>" maxlength="4" required>
                            </td>
                        </tr>
                    <?php
                            }
                        } else {
                    ?>
                        <tr class="fila-fija">
                            <td>
                                <input type="text" pattern="[a-zA-Z0-9-]{1,10}" class="form-control" placeholder="Embase*" name="presentacion_embase_up[]" maxlength="10"required>
                            </td>
                            <td>
                                <input type="number" min="1" max="1000" class="form-control" placeholder="Contenido*" name="presentacion_contenido_up[]" maxlength="4" required>
                            </td>
                            <td>
                                <select class="form-control" name="presentacion_medida_up[]">
                                    <option value="" selected="" disabled="">Seleccione una medida*</option>
                                    <option value="mg.">Miligramos</option>
                                    <option value="g.">Gramos</option>
                                    <option value="Kg.">Kilogramos</option>
                                    <option value="ml.">Mililitros</option>
                                    <option value="L.">Litros</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" min="0" max="1000" step="0.01" class="form-control" placeholder="Precio Venta Und.*" name="presentacion_preciov_up[]" maxlength="4" required>
                            </td>
                            <td class="eliminar" width="10%" style="display:none;">
                                <button type="button" class="btn btn-raised btn-danger btn-lgs btn-block"><i class="fas fa-minus"></i></button>
                            </td>
                        </tr>
                    <?php
                        }
                    ?>
                    </table>
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