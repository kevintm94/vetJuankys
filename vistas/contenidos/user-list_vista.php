<?php
    if ($_SESSION['rol_auth'] != 1) {
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
?>
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE EMPLEADOS
    </h3>
    <p class="text-justify">
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo SERVERURL; ?>user-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO EMPLEADO</a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>user-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE EMPLEADOS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>user-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR EMPLEADO</a>
        </li>
    </ul>	
</div>

<div class="container-fluid">
    <?php
        require_once "./controladores/usuarioControlador.php";
        $ins_usuario = new usuarioControlador();

        echo $ins_usuario->paginador_usuario_controlador($pagina[1], PAGINA, $_SESSION['rol_auth'], $_SESSION['id_auth'], $pagina[0],"");
    ?>
</div>