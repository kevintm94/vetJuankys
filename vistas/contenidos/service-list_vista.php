<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE SERVICIOS
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
            <a class="active" href="<?php echo SERVERURL; ?>service-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE SERVICIOS</a>
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

        echo $ins_servicio->paginador_servicio_controlador($pagina[1], PAGINA, $_SESSION['rol_auth'], $pagina[0],"");
    ?>
</div>