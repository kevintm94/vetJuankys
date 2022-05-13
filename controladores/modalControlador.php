<?php
$peticion = true;
    require_once "loteControlador.php";
    require_once "../config/app.php";
    require_once "../modelos/mainModel.php";
    $main = new mainModel();
    if (isset($_GET['op'])) {
        switch ($_GET['op']) {
            case 'agregarLote':
                if(isset($_GET['id']) && !empty($_GET['id'])){
                    $lote = new loteControlador();
                    $datos = $lote->presentaciones_lote_controlador($_GET['id']);
                    $lista = '<form class="form-neon FormularioAjax" action="'. SERVERURL.'ajax/loteAjax.php" method="POST" data-form="save" autocomplete="off">
                        <fieldset>
                            <div class="container-fluid">
                                <div class="form-group">
                                    <label for="lote_presentacion_reg" class="bmd-label-floating">Presentacion<font COLOR="red">*</font></label>
                                    <select class="form-control" name="lote_presentacion_reg" required>';
                    if ($datos == "") {
                        $lista .= '<option value="0" selected="" disabled="">No hay presentaciones registradas</option>';
                    } else {
                        $lista .= '<option value="0" selected="" disabled="">Seleccione una presentación</option>';
                        foreach ($datos as $rows) {
                            $lista .= '<option value="'.$rows['IdPresentaciones'].'">'.$rows['Nombre'].'</option>';
                        }
                        $lista .='</select></div>';
                        $lista .= '<div class="form-group">
                                        <label for="lote_stock" class="bmd-label-floating">Stock<font COLOR="red">*</font></label>
                                        <input type="number" min="0" max="1000" class="form-control" name="lote_stock_reg" id="lote_stock" maxlength="4" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="lote_fechav" class="bmd-label-floating">Fecha de Vencimiento<font COLOR="red">*</font></label>
                                        <input type="date" min="'.date("Y-m-d").'" value="'.date("Y-m-d").'" class="form-control" name="lote_fechav_reg" id="lote_fechav" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="lote_precioc" class="bmd-label-floating">Precio Compra Unidad</label>
                                        <input type="number" min="0" max="1000" step="0.01" class="form-control" name="lote_precioc_reg" id="lote_precioc" maxlength="4">
                                    </div>';
                    }
                    $lista .= '</div></fieldset>';
                    $lista .= '
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="submit" class="btn btn-primary"><i class="far fa-save"></i> &nbsp; Guardar</button>
                        &nbsp; &nbsp;
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </p></form>';
                    echo $lista;
                }else{
                    echo 'Error al cargar la información, recargue la página.';
                }
                break;
            case 'listarLote':
                if(isset($_GET['id']) && !empty($_GET['id'])){
                    $lote = new loteControlador();
                    $datos = $lote->lista_lote_controlador($_GET['id']);
                    $tabla = "";
                    session_start(['name' => 'Auth']);
                    if ($datos == "") {
                        echo "Lista vacia";
                    } else {
                        $contador = 1;
                        $tabla .= '<div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="thead-light">
                                <tr class="text-center roboto-medium">
                                    <th>#</th>
                                    <th>PRESENTACIÓN</th>
                                    <th>FECHA VENCIMIENTO</th>
                                    <th>STOCK</th>
                                    <th>PRECIO COMPRA UD.</th>
                                    <th>PRECIO VENTA UD.</th>
                                    <th>ESTADO</th>
                                    <th>ACTUALIZAR</th>
                                    <th>ELIMINAR</th>'; 
                                $tabla .= '</tr>
                            </thead>
                            <tbody>';
                        foreach ($datos as $rows) {
                            $tabla .= '
                            <tr class="text-center" >
                                <td>'.$contador.'</td>
                                <td>'.$rows['Nombre'].'</td>
                                <td>'.$rows['FechaVencimiento'].'</td>
                                <td>'.$rows['Stock'].'</td>
                                <td>'. MONEDA. " " .$rows['PrecioCompra'].'</td>
                                <td>'. MONEDA. " " .$rows['PrecioVenta'].'</td>';
                                if ($rows['Stock'] > 0) {
                                    $tabla .= '<td><span class="badge badge-success">Habilitado</span></td>';
                                } else {
                                    $tabla .= '<td><span class="badge badge-danger">Deshabilitado</span></td>';
                                }
                                
                                if ($_SESSION['rol_auth'] == 1 || $_SESSION['rol_auth'] == 2) {
                                    $tabla .= '<td>
                                                <button type="button" class="btn btn-success" onclick="editar_lote(\''.$main->encryption($rows['IdLotes']).'\',\''.$_GET['id'].'\')">
                                                    <i class="fas fa-sync-alt"></i>	
                                                </button
                                            </td>';
                                }
                                if ($_SESSION['rol_auth'] == 1) {
                                    $tabla .= '<td>
                                                <form class="FormularioAjax" action="'.SERVERURL.'ajax/loteAjax.php" method="POST" data-form="delete" autocomplete="off">
                                                    <input type="hidden" name="lote_id_del" value="'.$main->encryption($rows['IdLotes']).'">
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="far fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>';
                                }
                            $tabla .='</tr>';
                            $contador++;
                        }
                        
                    }
                    
                    
                    
                    $tabla .= '</tbody></table></div>';
                    echo $tabla;
                }else{
                    echo 'Error al cargar la información, recargue la página.';
                }
                break;
    
            case 'editarLote':
                if(isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['idlote']) && !empty($_GET['idlote'])){
                    $lote = new loteControlador();
                    $datos = $lote->presentaciones_lote_controlador($_GET['id']);
                    $datos_lote = $lote->datos_lote_controlador("Unico", $_GET['idlote']);
                    $campos = $datos_lote->fetch();
                    $lista = '<form class="form-neon FormularioAjax" action="'. SERVERURL.'ajax/loteAjax.php" method="POST" data-form="update" autocomplete="off">
                        <input type="hidden" name="lote_id_up" value="'.$_GET['idlote'].'">
                        <fieldset>
                            <div class="container-fluid">
                                <div class="form-group">
                                    <label for="lote_presentacion_up" class="bmd-label-floating">Presentacion<font COLOR="red">*</font></label>
                                    <select class="form-control" name="lote_presentacion_up" required>';
                    if ($datos == "") {
                        $lista .= '<option value="0" selected="" disabled="">No hay presentaciones registradas</option>';
                    } else {
                        foreach ($datos as $rows) {
                            $lista .= ($campos['IdPresentaciones'] == $rows['IdPresentaciones']) ? '<option value="'.$rows['IdPresentaciones'].'" selected="">'.$rows['Nombre'].'(Actual)</option>' : '<option value="'.$rows['IdPresentaciones'].'">'.$rows['Nombre'].'</option>';
                        }
                        $lista .='</select></div>';
                        $lista .= '<div class="form-group">
                                        <label for="lote_stock" class="bmd-label-floating">Stock<font COLOR="red">*</font></label>
                                        <input type="number" min="0" max="1000" class="form-control" name="lote_stock_up" value="'.$campos['Stock'].'" id="lote_stock" maxlength="4" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="lote_fechav" class="bmd-label-floating">Fecha de Vencimiento<font COLOR="red">*</font></label>
                                        <input type="date" min="'.date("Y-m-d").'" value="'.$campos['FechaVencimiento'].'" class="form-control" name="lote_fechav_up" id="lote_fechav" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="lote_precioc" class="bmd-label-floating">Precio Compra Unidad</label>
                                        <input type="number" min="0" max="1000" step="0.01" class="form-control" name="lote_precioc_up" value="'.$campos['PrecioCompra'].'" id="lote_precioc" maxlength="4">
                                    </div>';
                    }
                    $lista .= '</div></fieldset>';
                    $lista .= '
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="submit" class="btn btn-primary"><i class="far fa-save"></i> &nbsp; Guardar</button>
                        &nbsp; &nbsp;
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </p></form>';
                    echo $lista;
                }else{
                    echo 'Error al cargar la información, recargue la página.';
                }
                break;

        }
    }

    if (isset($_POST['op'])) {
        switch ($_POST['op']) {
            case 'buscarCliente':
                if (isset($_POST['cliente']) && $_POST['cliente'] != "") {
                    $client = $_POST['cliente'];
                    $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM clientes WHERE Estado != '0' AND CI LIKE '%$client%' OR Nombres LIKE '%$client%' OR Apellidos LIKE '%$client%' ORDER BY Nombres ASC LIMIT 5";
                }
    
                $tabla = '';
                $conexion = $main->conectar();
                $datos = $conexion->query($consulta);
                if ($datos->rowCount() > 0) {
                    $datos = $datos->fetchAll();
                    
                    $tabla .= '<div class="container-fluid" id="tabla_clientes">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-sm">
                                <tbody>';
                    foreach ($datos as $value) {
                        $tabla .= '<tr class="text-center">
                            <td>'.$value['CI'].' - '.$value['Nombres'].' '.$value['Apellidos'].'</td>
                            <td>
                                <button type="button" class="btn btn-primary" onclick="añadir_cliente(\''.$main->encryption($value['IdClientes']).'\',\''.$value['Nombres'].' '.$value['Apellidos'].'\')"><i class="fas fa-user-plus"></i></button>
                            </td>
                        </tr>';
                    }        
                                    
                    $tabla .= '</tbody>
                            </table>
                        </div>
                    </div>';
                } else {
                    $tabla .= '<div class="alert alert-warning" role="alert">
                        <p class="text-center mb-0">
                            <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                            No hemos encontrado ningún cliente en el sistema que coincida con <strong>“Busqueda”</strong>
                        </p>
                    </div>';
                }
                
                echo $tabla;
                //echo var_dump($datos);
                break;
            case 'buscarItem':
                if (isset($_POST['item']) && $_POST['item'] != "") {
                    $item = $_POST['item'];
                    $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM articulos WHERE Estado != '0' AND Stock > '0' AND (Codigo LIKE '%$item%' OR Nombre LIKE '%$item%')  ORDER BY Codigo ASC LIMIT 5";
                }
    
                $tabla = '';
                $conexion2 = $main->conectar();
                $datos = $conexion2->query($consulta);
                if ($datos->rowCount() > 0) {
                    $datos = $datos->fetchAll();

                    $tabla .= '<div class="container-fluid" id="tabla_items">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-sm">
                                <tbody>';
                    foreach ($datos as $value) {
                        $tabla .= '<tr class="text-center">
                            <td>'.$value['Codigo'].' - '.$value['Nombre'].' - '.$value['PrecioVenta'].MONEDA.'</td>
                            <td>
                                <button type="button" class="btn btn-primary" onclick="add_item(\''.$main->encryption($value['IdArticulos']).'\',\''.$value['Nombre'].'\',\''.$value['PrecioVenta'].'\',\''.$value['Stock'].'\')"><i class="fas fa-box-open"></i></button>
                            </td>
                        </tr>';
                    }
                                   
                    $tabla .= '</tbody>
                            </table>
                        </div>
                    </div>';
                } else {
                    $tabla .= '<div class="alert alert-warning" role="alert">
                        <p class="text-center mb-0">
                            <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                            No hemos encontrado ningún artículo en el sistema que coincida con <strong>“'.$item.'”</strong>
                        </p>
                    </div>';
                }
                echo $tabla;
                break;
            case 'agregarItem':
                if(isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['nombre']) && !empty($_POST['nombre'])){
                    $lista = '<input type="hidden" name="id_agregar_item" id="id_agregar_item" value="'. $_POST['id'] .'">
                        <input type="hidden" name="nombre_agregar_item" id="nombre_agregar_item" value="'. $_POST['nombre'] .'">
                        <input type="hidden" name="precio_agregar_item" id="precio_agregar_item" value="'.$_POST['precio'].'">
                        <div style="display: flex;justify-content: center;align-items: center;"><h4>Stock: ' . $_POST['stock'] . '</h4></div>
                        <div class="container-fluid" style="display: flex;justify-content: center;align-items: center;">
                            <div class="form-group" style="width: 120px;">
                                <label for="detalle_cantidad" class="bmd-label-floating">Cantidad de items</label>
                                <input type="number" pattern="[0-9]{1,7}" min="1" max="' . $_POST['stock'] . '" value="1" class="form-control" name="detalle_cantidad" id="detalle_cantidad" maxlength="7" required="" >
                            </div>
                        </div>
                    </div>';
                    echo $lista;
                } else {
                    echo 'Error al cargar la información, recargue la página.';
                }
                
                break;

            case 'buscarServicio':
                if (isset($_POST['servicio']) && $_POST['servicio'] != "") {
                    $serv = $_POST['servicio'];
                    $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM servicios WHERE Estado != '0' AND EstadoBD != '0' AND (Nombre LIKE '%$serv%' OR Codigo LIKE '%$serv%') ORDER BY Codigo ASC LIMIT 5";
                }
    
                $tabla = '';
                $conexion = $main->conectar();
                $datos = $conexion->query($consulta);
                if ($datos->rowCount() > 0) {
                    $datos = $datos->fetchAll();
                    
                    $tabla .= '<div class="container-fluid" id="tabla_servicios">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-sm">
                                <tbody>';
                    foreach ($datos as $value) {
                        $tabla .= '<tr class="text-center">
                            <td>'.$value['Codigo'].' - '.$value['Nombre'].'</td>
                            <td class="eliminar_filita">
                                <button type="button" class="btn btn-primary" onclick="add_servicio(\''.$main->encryption($value['IdServicios']).'\',\''.$value['Nombre'].'\',\''.$value['Precio'].'\')"><i class="fas fa-user-plus"></i></button>
                            </td>
                        </tr>';
                    }        
                                    
                    $tabla .= '</tbody>
                            </table>
                        </div>
                    </div>';
                } else {
                    $tabla .= '<div class="alert alert-warning" role="alert">
                        <p class="text-center mb-0">
                            <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                            No hemos encontrado ningún servicio en el sistema que coincida con <strong>“' . $serv . '”</strong>
                        </p>
                    </div>';
                }
                
                echo $tabla;
                //echo var_dump($datos);
                break;
            case 'buscarMedicina':
                if (isset($_POST['medicina']) && $_POST['medicina'] != "") {
                    $medicina = $_POST['medicina'];
                    $consulta = "SELECT SQL_CALC_FOUND_ROWS medicinas.*, presentaciones.IdPresentaciones, presentaciones.Nombre AS Presentacion, PrecioVenta FROM medicinas JOIN presentaciones ON presentaciones.IdMedicinas = medicinas.IdMedicinas WHERE medicinas.Estado != '0' AND (medicinas.Codigo LIKE '%$medicina%' OR medicinas.Nombre LIKE '%$medicina%') ORDER BY Codigo ASC LIMIT 5";
                }
    
                $tabla = '';
                $conexion2 = $main->conectar();
                $datos = $conexion2->query($consulta);
                if ($datos->rowCount() > 0) {
                    $datos = $datos->fetchAll();

                    $tabla .= '<div class="container-fluid" id="tabla_medicinas">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-sm">
                                <tbody>';
                    foreach ($datos as $value) {
                        $tabla .= '<tr class="text-center">
                            <td>'.$value['Codigo'].' - '.$value['Nombre'].' - '.$value['Presentacion'].' - '.$value['PrecioVenta'].MONEDA.'</td>
                            <td>
                                <button type="button" class="btn btn-primary" onclick="add_medicina(\''.$main->encryption($value['IdMedicinas']).'\',\''.$value['Nombre'].' '.$value['Presentacion'].'\',\''.$value['PrecioVenta'].'\',\''.$value['IdPresentaciones'].'\')"><i class="fas fa-box-open"></i></button>
                            </td>
                        </tr>';
                    }
                                   
                    $tabla .= '</tbody>
                            </table>
                        </div>
                    </div>';
                } else {
                    $tabla .= '<div class="alert alert-warning" role="alert">
                        <p class="text-center mb-0">
                            <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                            No hemos encontrado ningún artículo en el sistema que coincida con <strong>“'.$medicina.'”</strong>
                        </p>
                    </div>';
                }
                echo $tabla;
                break;
            case 'agregarMedicina':
                if(isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['nombre']) && !empty($_POST['nombre']) && isset($_POST['idPre']) && !empty($_POST['idPre'])){
                    $medicina = $_POST['id'];
                    $presentacion = $_POST['idPre'];
                    $consulta = "SELECT * FROM lotes WHERE Estado != '0' AND IdPresentaciones='$presentacion' AND FechaVencimiento > CURDATE() AND Stock > '0' ORDER BY FechaVencimiento ASC";
                    $conexion2 = $main->conectar();
                    $datos = $conexion2->query($consulta);
                    if ($datos->rowCount() > 0) {
                        $datos = $datos->fetchAll();
                    
                        $lista = '<input type="hidden" name="id_agregar_item" id="id_agregar_medicina" value="'. $_POST['id'] .'">
                            <input type="hidden" name="nombre_agregar_item" id="nombre_agregar_medicina" value="'. $_POST['nombre'] .'">
                            <input type="hidden" name="precio_agregar_item" id="precio_agregar_medicina" value="'.$_POST['precio'].'">
                            <input type="hidden" name="fecha_agregar_item" id="fecha_agregar_medicina" value="">
                            <div style="display: flex;justify-content: center;align-items: center;" id="Stock"><h4></h4></div>
                            <div class="container-fluid">
                                <div class="form-group">
                                    <select class="form-control" name="lote" id="lote" onchange="actualizar(this)">
                                        <option value="" selected="" disabled="">Seleccione una fecha de vencimiento</option>';
                        foreach ($datos as $value) {
                            $lista .= '<option value="' . $value['IdLotes'] . '">' . $value['FechaVencimiento'] . '</option>';
                        }
                        $lista .= '</select>
                                </div>
                                <div class="form-group" style="width: 120px;">
                                    <label for="detalle_cantidad" class="bmd-label-floating">Cantidad de items</label>
                                    <input type="number" pattern="[0-9]{1,7}" min="1" value="1" class="form-control" name="detalle_cantidad_medicina" id="detalle_cantidad_medicina" maxlength="7" required="" >
                                </div>
                            </div>
                        </div>';
                        echo $lista;
                    }
                } else {
                    echo 'Error al cargar la información, recargue la página.';
                }
                break;
            case 'agregarStock':
                if (isset($_POST['id']) && !empty($_POST['id'])) {
                    $id = $_POST['id'];
                    $consulta = "SELECT Stock, FechaVencimiento FROM lotes WHERE Estado != '0' AND IdLotes='$id'";
                    $conexion2 = $main->conectar();
                    $datos = $conexion2->query($consulta);
                    $datos = $datos->fetch();
                    $res = '<h4> Stock: ' . $datos['Stock'] . '</h4>|' . $datos['Stock'] . '|' . $datos['FechaVencimiento'];
                    echo $res;
                } else {
                    echo 'Error al cargar la información, recargue la página.';
                }
                break;
            case 'listarDetalle':
                if (isset($_POST['id']) && !empty($_POST['id'])) {
                    $idVenta = $main->decryption($_POST['id']);
                    $consulta = "SELECT * FROM detalleventas WHERE IdVentas = ".$idVenta." ORDER BY IdDetalleVentas ASC";
                    $tabla = '';
                    $conexion2 = $main->conectar();
                    $datos = $conexion2->query($consulta);
                    if ($datos->rowCount() > 0) {
                        $datos = $datos->fetchAll();
                        $tabla .= '<div class="container-fluid" id="tabla_detalle">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Detalle</th>
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                        foreach ($datos as $value) {
                            if ($value['IdArticulos'] != NULL) {
                                $consulta = "SELECT Nombre FROM articulos WHERE IdArticulos = " . $value['IdArticulos'];
                                $conexion2 = $main->conectar();
                                $art = $conexion2->query($consulta);
                                $art = $art->fetch();
                                $tabla .= '<tr class="text-center">
                                    <td>'.$art['Nombre'].'</td>
                                    <td>'.$value['Cantidad'].'</td>
                                </tr>';
                            }
                            if ($value['IdLotes'] != NULL) {
                                $consulta = "SELECT CONCAT_WS(' ', medicinas.Nombre, presentaciones.Nombre) AS Detalle FROM lotes JOIN presentaciones ON presentaciones.IdPresentaciones = lotes.IdPresentaciones JOIN medicinas ON medicinas.IdMedicinas = presentaciones.IdMedicinas WHERE IdLotes = " . $value['IdLotes'];
                                $conexion2 = $main->conectar();
                                $med = $conexion2->query($consulta);
                                $med = $med->fetch();
                                $tabla .= '<tr class="text-center">
                                    <td>'.$med['Detalle'].'</td>
                                    <td>'.$value['Cantidad'].'</td>
                                </tr>';
                            }
                            if ($value['IdServicios'] != NULL) {
                                $consulta = "SELECT Nombre FROM servicios WHERE IdServicios = " . $value['IdServicios'];
                                $conexion2 = $main->conectar();
                                $serv = $conexion2->query($consulta);
                                $serv = $serv->fetch();
                                $tabla .= '<tr class="text-center">
                                    <td>'.$serv['Nombre'].'</td>
                                    <td>'.$value['Cantidad'].'</td>
                                </tr>';
                            }
                        }
                                    
                        $tabla .= '</tbody>
                                </table>
                            </div>
                        </div>';
                    } else {
                        $tabla .= '<div class="alert alert-warning" role="alert">
                            <p class="text-center mb-0">
                                <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                                No hemos encontrado ningún artículo en el sistema que coincida con <strong>“'.$medicina.'”</strong>
                            </p>
                        </div>';
                    }
                    echo $tabla;
                    
                }
                break;
        }
    }
?>