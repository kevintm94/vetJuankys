<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR SERVICIO
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
            <a class="active" href="<?php echo SERVERURL; ?>service-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR SERVICIO</a>
        </li>
    </ul>
</div>

<?php
    if (!isset($_SESSION['busqueda_servicio']) && empty($_SESSION['busqueda_servicio'])) {
?>
<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" data-form="default" autocomplete="off">
        <input type="hidden" name="modulo" value="servicio">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <h5>¿Qué servicio estas buscando?</h5>
                    <div class="form-group">
                        <label for="inputSearch" class="bmd-label-floating">Código o nombre</label>
                        <input type="text" class="form-control" name="busqueda_inicial" id="inputSearch" maxlength="50">
                    </div>
                </div>
                <div class="col-12">
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="submit" class="btn btn-raised btn-info"><i class="fas fa-search"></i> &nbsp; BUSCAR</button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<?php } else { ?>
<div class="container-fluid">
    <form class="FormularioAjax" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" data-form="search" autocomplete="off">
        <input type="hidden" name="modulo" value="servicio">
        <input type="hidden" name="eliminar_busqueda" value="eliminar">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <p class="text-center" style="font-size: 20px;">
                        Resultados de la busqueda <strong>“<?php echo $_SESSION['busqueda_servicio'] ?>”</strong>
                    </p>
                </div>
                <div class="col-12">
                    <p class="text-center" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-raised btn-danger"><i class="far fa-trash-alt"></i> &nbsp; ELIMINAR BÚSQUEDA</button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/servicioControlador.php";
        $ins_servicio = new servicioControlador();

        echo $ins_servicio->paginador_servicio_controlador($pagina[1], PAGINA, $_SESSION['rol_auth'], $pagina[0], $_SESSION['busqueda_servicio']);
    ?>
</div>
<?php } ?>