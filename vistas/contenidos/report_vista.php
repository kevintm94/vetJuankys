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
    <i class="fas fa-file-alt"></i> &nbsp; REPORTES
    </h3>
    <p class="text-justify">
    </p>
</div>

<div class="full-box tile-container">
    <a href="<?php echo SERVERURL; ?>report-client/" class="tile">
        <div class="tile-tittle">Clientes</div>
        <div class="tile-icon">
            <i class="fas fa-users fa-fw"></i>
        </div>
    </a>

    <a href="<?php echo SERVERURL; ?>report-provider/" class="tile">
        <div class="tile-tittle">Proveedores</div>
        <div class="tile-icon">
            <i class="fas fa-truck"></i>
        </div>
    </a>
    
    <a href="<?php echo SERVERURL; ?>report-item/" class="tile">
        <div class="tile-tittle">Items</div>
        <div class="tile-icon">
            <i class="fas fa-pallet fa-fw"></i>
        </div>
    </a>

    <a href="<?php echo SERVERURL; ?>report-medicine/" class="tile">
        <div class="tile-tittle">Medicinas</div>
        <div class="tile-icon">
            <i class="fas fa-prescription-bottle-alt"></i>
        </div>
    </a>

    <a href="<?php echo SERVERURL; ?>report-service/" class="tile">
        <div class="tile-tittle">Servicios</div>
        <div class="tile-icon">
            <i class="fas fa-briefcase-medical"></i>
        </div>
    </a>

    <a href="<?php echo SERVERURL; ?>report-course/" class="tile">
        <div class="tile-tittle">Cursos</div>
        <div class="tile-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
    </a>

    <a href="<?php echo SERVERURL; ?>report-venta/" class="tile">
        <div class="tile-tittle">Ventas</div>
        <div class="tile-icon">
            <i class="fas fa-hand-holding-usd"></i>
        </div>
    </a>

    <!--a href="" class="tile">
        <div class="tile-tittle">Inscripciones</div>
        <div class="tile-icon">
            <i class="fas fa-chalkboard-teacher"></i>
            <p>0 Registrados</p>
        </div>
    </a>

    <a href="" class="tile">
        <div class="tile-tittle">Reportes</div>
        <div class="tile-icon">
            <i class="fas fa-file-alt"></i>
            <p>_____________</p>
        </div>
    </a-->

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

    <a href="<?php echo SERVERURL; ?>report-user/" class="tile">
        <div class="tile-tittle">Empleados</div>
        <div class="tile-icon">
            <i class="fas fa-address-book fa-fw"></i>
        </div>
    </a>
    
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