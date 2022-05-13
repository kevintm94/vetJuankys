<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA VENTA
    </h3>
    <p class="text-justify">
    </p>
</div>
<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>reservation-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA VENTA</a>
        </li>
        <!--li>
            <a href="<//?php echo SERVERURL; ?>reservation-reservation/"><i class="far fa-calendar-alt"></i> &nbsp; RESERVACIONES</a>
        </li-->
        <li>
            <a href="<?php echo SERVERURL; ?>reservation-pending/"><i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp; VENTAS</a>
        </li>
        <!--li>
            <a href="<//?php echo SERVERURL; ?>reservation-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; FINALIZADOS</a>
        </li-->
        <li>
            <a href="<?php echo SERVERURL; ?>reservation-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR POR FECHA</a>
        </li>
    </ul>
</div>

<div class="container-fluid">
    <div class="container-fluid form-neon">
        <div class="container-fluid">
            <p class="text-center roboto-medium">AGREGAR CLIENTE O ITEMS</p>
            <p class="text-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCliente"><i class="fas fa-user-plus"></i> &nbsp; Agregar cliente</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalItem"><i class="fas fa-box-open"></i> &nbsp; Agregar item</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalMedicina"><i class="fas fa-prescription-bottle-alt"></i> &nbsp; Agregar medicina</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalServicio"><i class="fas fa-briefcase-medical"></i> &nbsp; Agregar servicio</button>
            </p>
            <form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/ventaAjax.php" method="POST" data-form="save" autocomplete="off">
                <input type="hidden" name="id_cliente_seleccionado" id="id_cliente_seleccionado" value="">
                <div id="cliente_seleccionado">
                    <span class="roboto-medium">CLIENTE:</span> 
                    <span class="text-danger">&nbsp; <i class="fas fa-exclamation-triangle"></i> Seleccione un cliente</span>
                    <br><br>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark table-sm" id="tabla_venta">
                        <thead>
                            <tr class="text-center roboto-medium">
                                <th>ITEM</th>
                                <th>CANTIDAD</th>
                                <th>FECHA VENCIMIENTO</th>
                                <th>COSTO UNITARIO</th>
                                <th>SUBTOTAL</th>
                                <th>ELIMINAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center bg-light totales">
                                <td style="display:none;"><input type="hidden" class="form-control" name="canttotal_venta_reg" value="0"></td>
                                <td style="display:none;"><input type="hidden" class="form-control" name="prectotal_venta_reg" value="0.0"></td>
                                <td><strong>TOTAL</strong></td>
                                <td><strong>0 items</strong></td>
                                <td colspan="2"></td>
                                <td><strong>0.00 Bs.</strong></td>
                                <td colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-center" style="margin-top: 40px;">
                    <button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR</button>
                    &nbsp; &nbsp;
                    <button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; GUARDAR</button>
                </p>
            </form>
        </div>
    </div>
</div>


<!-- MODAL CLIENTE -->
<div class="modal fade" id="ModalCliente" tabindex="-1" role="dialog" aria-labelledby="ModalCliente" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCliente">Agregar cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_cliente" class="bmd-label-floating">CI, Nombre, Apellido</label>
                        <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,35}" class="form-control" name="input_cliente" id="input_cliente" maxlength="35" required>
                    </div>
                </div>
                <br>
                <div id="contenido_busqueda">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="buscar_cliente()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL ITEM -->
<div class="modal fade" id="ModalItem" tabindex="-1" role="dialog" aria-labelledby="ModalItem" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalItem">Agregar item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_item" class="bmd-label-floating">Código, Nombre</label>
                        <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ-]{1,30}" class="form-control" name="input_item" id="input_item" maxlength="30">
                    </div>
                </div>
                <br>
                <div id="contenido_busqueda_item">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="buscar_item()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL SERVICIO -->
<div class="modal fade" id="ModalServicio" tabindex="-1" role="dialog" aria-labelledby="ModalServicio" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalServicio">Agregar servicio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_servicio" class="bmd-label-floating">Código, Nombre</label>
                        <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ-]{1,30}" class="form-control" name="input_servicio" id="input_servicio" maxlength="30">
                    </div>
                </div>
                <br>
                <div id="contenido_busqueda_servicio">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="buscar_servicio()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL AGREGAR ITEM -->
<div class="modal fade" id="ModalAgregarItem" tabindex="-1" role="dialog" aria-labelledby="ModalAgregarItem" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAgregarItem">Selecciona la cantidad de items</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="agregar_item()" >Agregar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL MEDICINA -->
<div class="modal fade" id="ModalMedicina" tabindex="-1" role="dialog" aria-labelledby="ModalMedicina" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalMedicina">Agregar medicina</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_medicina" class="bmd-label-floating">Código, Nombre</label>
                        <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ-]{1,30}" class="form-control" name="input_medicina" id="input_medicina" maxlength="30">
                    </div>
                </div>
                <br>
                <div id="contenido_busqueda_medicina">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="buscar_medicina()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL AGREGAR MEDICINA -->
<div class="modal fade" id="ModalAgregarMedicina" tabindex="-1" role="dialog" aria-labelledby="ModalAgregarMedicina" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAgregarMedicina">Selecciona la fecha de vencimiento y la cantidad de la medicina</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-medicina">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="agregar_medicina()" >Agregar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cancelar</button>
            </div>
        </form>
    </div>
</div>
