<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<?php
    if($_SESSION['modal'] == true){
?>
<script>
    $(document).ready(function() {
        $('#ModalProductos').modal('toggle');
    });
    
</script>
<?php $_SESSION['modal'] = false; }?>
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fab fa-dashcube fa-fw"></i> &nbsp; <?php echo COMPANY; ?>
    </h3>
    <p class="text-justify">
    </p>
</div>

<div class="full-box tile-container">
    <?php
        require_once "./controladores/clienteControlador.php";
        $ins_cliente = new clienteControlador();
        $total_clientes = $ins_cliente->datos_cliente_controlador("Conteo", 0);
    ?>
    <a href="<?php echo SERVERURL; ?>client-list/" class="tile">
        <div class="tile-tittle">Clientes</div>
        <div class="tile-icon">
            <i class="fas fa-users fa-fw"></i>
            <p><?php echo $total_clientes->rowCount(); ?> Registrados</p>
        </div>
    </a>

    <?php
        require_once "./controladores/proveedorControlador.php";
        $ins_proveedor = new proveedorControlador();
        $total_proveedores = $ins_proveedor->datos_proveedor_controlador("Conteo", 0);
    ?>
    <a href="<?php echo SERVERURL; ?>provider-list/" class="tile">
        <div class="tile-tittle">Proveedores</div>
        <div class="tile-icon">
            <i class="fas fa-truck"></i>
            <p><?php echo $total_proveedores->rowCount(); ?> Registrados</p>
        </div>
    </a>
    
    <?php
        require_once "./controladores/itemControlador.php";
        $ins_item = new itemControlador();
        $total_items = $ins_item->datos_item_controlador("Conteo", 0);
    ?>
    <a href="<?php echo SERVERURL; ?>item-list/" class="tile">
        <div class="tile-tittle">Items</div>
        <div class="tile-icon">
            <i class="fas fa-pallet fa-fw"></i>
            <p><?php echo $total_items->rowCount(); ?> Registrados</p>
        </div>
    </a>

    <?php
        require_once "./controladores/medicinaControlador.php";
        $ins_medicina = new medicinaControlador();
        $total_medicinas = $ins_medicina->datos_medicina_controlador("Conteo", 0);
    ?>
    <a href="<?php echo SERVERURL; ?>medicine-list/" class="tile">
        <div class="tile-tittle">Medicinas</div>
        <div class="tile-icon">
        <i class="fas fa-prescription-bottle-alt"></i>
            <p><?php echo $total_medicinas->rowCount(); ?> Registrados</p>
        </div>
    </a>

    <?php
        require_once "./controladores/servicioControlador.php";
        $ins_servicio = new servicioControlador();
        $total_servicios = $ins_servicio->datos_servicio_controlador("Conteo", 0);
    ?>
    <a href="<?php echo SERVERURL; ?>service-list/" class="tile">
        <div class="tile-tittle">Servicios</div>
        <div class="tile-icon">
            <i class="fas fa-briefcase-medical"></i>
            <p><?php echo $total_servicios->rowCount(); ?> Registrados</p>
        </div>
    </a>

    <?php
        require_once "./controladores/cursoControlador.php";
        $ins_curso = new cursoControlador();
        $total_cursos = $ins_curso->datos_curso_controlador("Conteo", 0);
    ?>
    <a href="<?php echo SERVERURL; ?>course-list/" class="tile">
        <div class="tile-tittle">Cursos</div>
        <div class="tile-icon">
            <i class="fas fa-graduation-cap"></i>
            <p><?php echo $total_cursos->rowCount(); ?> Registrados</p>
        </div>
    </a>

    <?php
        require_once "./controladores/ventaControlador.php";
        $ins_venta = new ventaControlador();
        $total_ventas = $ins_venta->datos_venta_controlador("Conteo", 0);
    ?>
    <a href="<?php echo SERVERURL; ?>reservation-pending/" class="tile">
        <div class="tile-tittle">Ventas</div>
        <div class="tile-icon">
            <i class="fas fa-hand-holding-usd"></i>
            <p><?php echo $total_ventas->rowCount(); ?> Registrados</p>
        </div>
    </a>

    <!--a href="" class="tile">
        <div class="tile-tittle">Inscripciones</div>
        <div class="tile-icon">
            <i class="fas fa-chalkboard-teacher"></i>
            <p>0 Registrados</p>
        </div>
    </a-->

    <a href="<?php echo SERVERURL; ?>report/" class="tile">
        <div class="tile-tittle">Reportes</div>
        <div class="tile-icon">
            <i class="fas fa-file-alt"></i>
            <p>_____________</p>
        </div>
    </a>

    <!--
    <a href="<//?php echo SERVERURL; ?>reservation-reservation/" class="tile">
        <div class="tile-tittle">Reservaciones</div>
        <div class="tile-icon">
            <i class="far fa-calendar-alt fa-fw"></i>
            <p>30 Registradas</p>
        </div>
    </a>

    <a href="<//?php echo SERVERURL; ?>reservation-pending/" class="tile">
        <div class="tile-tittle">Prestamos</div>
        <div class="tile-icon">
            <i class="fas fa-hand-holding-usd fa-fw"></i>
            <p>200 Registrados</p>
        </div>
    </a>

    <a href="<//?php echo SERVERURL; ?>reservation-list/" class="tile">
        <div class="tile-tittle">Finalizados</div>
        <div class="tile-icon">
            <i class="fas fa-clipboard-list fa-fw"></i>
            <p>700 Registrados</p>
        </div>
    </a>
    -->

    <?php 
        if ($_SESSION['rol_auth'] == 1) { 
            require_once "./controladores/usuarioControlador.php";
            $ins_usuario = new usuarioControlador();
            $total_usuarios = $ins_usuario->datos_usuario_controlador("Conteo", 0);
    ?>
    <a href="<?php echo SERVERURL; ?>user-list/" class="tile">
        <div class="tile-tittle">Empleados</div>
        <div class="tile-icon">
            <i class="fas fa-address-book fa-fw"></i>
            <p><?php echo $total_usuarios->rowCount(); ?> Registrados</p>
        </div>
    </a>
    <?php } ?>
    
    <!--
    <a href="<//?php echo SERVERURL; ?>company/" class="tile">
        <div class="tile-tittle">Empresa</div>
        <div class="tile-icon">
            <i class="fas fa-store-alt fa-fw"></i>
            <p>1 Registrada</p>
        </div>
    </a>
    -->
</div>


<!-- MODAL CLIENTE -->
<div class="modal fade" id="ModalProductos" tabindex="-1" aria-labelledby="ModalCliente" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCliente">Lista de items con stock menor o igual a 5 unidades</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                    require_once "./modelos/presentacionModelo.php";
                    $ins_presentacion = new presentacionModelo();
                    $medicinas = $ins_presentacion->datos_presentacion_medicina_modelo();
                    $medicinas = $medicinas->fetchAll();
                    echo "<div class='tile-tittle text-center'>MEDICINAS</div>";
                    foreach ($medicinas as $row) {
                        $bandera = "no";
                        $presentaciones = $ins_presentacion->datos_presentacion_modelo("Completo", $row['IdMedicinas']);
                        $presentaciones = $presentaciones->fetchAll();
                        foreach ($presentaciones as $presentacion) {
                            $stock = $ins_presentacion->stock_presentacion_modelo($presentacion['IdPresentaciones']);
                            $stock = $stock->fetch();
                            if ($stock['Suma'] != "" && $stock['Suma'] <= 5) {
                                $bandera = "si";
                            }
                        }
                        if ($bandera == "si") {
                            foreach ($presentaciones as $presentacion) {
                                $stock = $ins_presentacion->stock_presentacion_modelo($presentacion['IdPresentaciones']);
                                $stock = $stock->fetch();
                                if ($stock['Suma'] != "" && $stock['Suma'] <= 5) {
                                    echo "<h6 class='text-center'>".$row['Nombre']." - ".$presentacion['Nombre'].".........................................".$stock['Suma']." Unidades</h6>";
                                }
                            }
                        }
                    }
                    require_once "./controladores/itemControlador.php";
                    $ins_item = new itemControlador();
                    $items = $ins_item->datos_item_controlador("Todos", 0);
                    $items = $items->fetchAll();
                    echo "<div class='tile-tittle text-center'>PRODUCTOS</div>";
                    foreach ($items as $item) {
                        if ($item['Stock'] <= 5) {
                            echo "<h6 class='text-center'>".$item['Nombre'].".........................................".$item['Stock']." Unidades</h6>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>