<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE PROVEEDORES
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
            <a class="active" href="<?php echo SERVERURL; ?>provider-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE PROVEEDORES</a>
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

        echo $ins_proveedor->paginador_proveedor_controlador($pagina[1], PAGINA, $_SESSION['rol_auth'], $pagina[0],"");
    ?>
</div>