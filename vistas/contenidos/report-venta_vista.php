<div class="full-box page-header">
  
    <p class="text-justify">
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>report-venta/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; REPORTE POR FECHA</a>
        </li>
    </ul>
</div>
<div class="container-fluid">
    <form class="form-neon" action="<?php echo SERVERURL; ?>/report-venta-venta" method="POST" autocomplete="off">
    <input type="hidden" name="modulo" value="venta">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="fecha_inicio" >Fecha inicial (día/mes/año)</label>
                        <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" maxlength="30" require>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="fecha_final" >Fecha final (día/mes/año)</label>
                        <input type="date" class="form-control" name="fecha_final" id="fecha_final" maxlength="30" require>
                    </div>
                </div>
                <div class="col-12">
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="submit" class="btn btn-raised btn-info"><i class="fas fa-search"></i> &nbsp; GENERAR REPORTE</button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
