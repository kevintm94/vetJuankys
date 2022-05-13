<?php
    if ($peticionAjax) {
        require_once "../modelos/itemModelo.php";
    } else {
        require_once "./modelos/itemModelo.php";
    }
    
    class itemControlador extends itemModelo{
        
        /*-----------Controlador para agregar un artículo----------*/
        public function agregar_item_controlador(){
            $codigo = mainModel::limpiar_cadena($_POST['item_codigo_reg']);
            $nombre = mainModel::limpiar_cadena($_POST['item_nombre_reg']);
            $detalle = mainModel::limpiar_cadena($_POST['item_detalle_reg']);
            $fabricante = mainModel::limpiar_cadena($_POST['item_fabricante_reg']);
            $stock = mainModel::limpiar_cadena($_POST['item_stock_reg']);
            $precioc = mainModel::limpiar_cadena($_POST['item_precioc_reg']);
            $preciov = mainModel::limpiar_cadena($_POST['item_preciov_reg']);
            $proveedor = mainModel::limpiar_cadena($_POST['item_proveedor_reg']);
            $estado = 1;
            
            /*-----------Comprobar campos vacios-------------*/
            if ($codigo == "" || $nombre == "" || $stock == "" || $preciov == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "Faltan datos obligatorios."
                ];
                echo json_encode($alerta);
                exit();
            }

            /*-----------Comprobar formato de campos-------------*/
            if(mainModel::verificar_datos("[a-zA-Z0-9-]{1,10}", $codigo)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El código no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if(mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,50}", $nombre)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El Nombre no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if ($detalle != "") {
                if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $detalle)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El detalle no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
            if ($fabricante != "") {
                if(mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,50}", $fabricante)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El fabricante no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
            if(mainModel::verificar_datos("[0-9()+]{1,4}", $stock)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El stock no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if ($precioc != "") {
                if(mainModel::verificar_datos("[0-9().]{1,4}", $precioc)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El precio de compra no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }else{
                $precioc = "0";
            }
            if(mainModel::verificar_datos("[0-9().]{1,4}", $preciov)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El precio de venta no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }

            /*-----------Comprobar que el codigo sea único-------------*/
            $check_codigo = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM articulos WHERE Codigo = '$codigo'");
            if ($check_codigo->rowCount()>0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El código ingresado ya se encuentra registrado en el sistema."
                ];
                echo json_encode($alerta);
                exit();
            }

            /*-----------Comprobando el proveedor sea correcto-------------*/
            if ($proveedor != 0) {
                $check_proveedor = mainModel::ejecutar_consulta_simple("SELECT Nombre FROM proveedores WHERE IdProveedores = '$proveedor' AND Estado = '1'");
                if ($check_proveedor->rowCount() <= 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El proveedor seleccionado no se encuentra registrado en el sistema."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            } else {
                $proveedor = null;
            }
            
            
            /*-----------Preparando los datos para el registro-------------*/
            $codigo = mb_strtolower($codigo, 'UTF-8');
            $nombre = mb_strtolower($nombre, 'UTF-8');
            $nombre = mainModel::mb_ucfirst($nombre, 'UTF-8');
            $detalle = mb_strtolower($detalle, 'UTF-8');
            $detalle = mainModel::mb_ucfirst($detalle, 'UTF-8');
            $fabricante = mb_strtolower($fabricante, 'UTF-8');
            $fabricante = mainModel::mb_ucfirst($fabricante, 'UTF-8');
            $datos_item_reg = [
                "Codigo" => $codigo,
                "Nombre" => $nombre,
                "Detalle" => $detalle,
                "Stock" => $stock,
                "Fabricante" => $fabricante,
                "PrecioCompra" => $precioc,
                "PrecioVenta" => $preciov,
                "Estado" => $estado,
                "IdProveedores" => $proveedor
            ];
            $agregar_item = itemModelo::agregar_item_modelo($datos_item_reg);
            if ($agregar_item->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "limpiar",
                    "Tipo" => "success",
                    "Titulo" => "Item registrado",
                    "Texto" => "El item fue registrado con exito."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El item no fue registrado correctamente."
                ];
            }
            echo json_encode($alerta);
        } /*Fin controlador*/

        /*-----------Modelo para listar proveedores----------*/
        public function proveedores_item_controlador($tipo, $id){
            $consulta = "SELECT IdProveedores, Nombre FROM proveedores WHERE Estado = '1'";
            $conexion = mainModel::conectar();
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();

            if ($tipo == "Nuevo") {
                $lista = '<div class="form-group">
                        <label for="item_proveedor_reg" class="bmd-label-floating">Proveedor</label>
                        <select class="form-control" name="item_proveedor_reg">';

                $lista .= '<option value="0" selected="">Seleccione una opción</option>';
                foreach ($datos as $rows) {
                    $lista .= '<option value="'.$rows['IdProveedores'].'">'.$rows['Nombre'].'</option>';
                }
            } else {
                $lista = '<div class="form-group">
                        <label for="item_proveedor_up" class="bmd-label-floating">Proveedor</label>
                        <select class="form-control" name="item_proveedor_up">';
                if ($id == null) {
                    $lista .= '<option value="0" selected="">Seleccione una opción</option>';
                    foreach ($datos as $rows) {
                        $lista .= '<option value="'.$rows['IdProveedores'].'">'.$rows['Nombre'].'</option>';
                    }
                } else {
                    $lista .= '<option value="0">Seleccione una opción</option>';
                    foreach ($datos as $rows) {
                        $actual = ($id == $rows['IdProveedores']) ? "(Actual)" : "" ;
                        $selected = ($id == $rows['IdProveedores']) ? 'selected=""' : '' ;
                        $lista .= '<option value="'.$rows['IdProveedores'].'" '.$selected.'>'.$rows['Nombre'].' '.$actual.'</option>';
                    }  
                }          
            }
            
            
            $lista .='</select></div>';
            return $lista;
        }

        /*-----------Controlador para paginar artículos----------*/
        public function paginador_item_controlador($pagina, $registros, $privilegio, $url, $busqueda){
            $pagina = mainModel::limpiar_cadena($pagina);
            $registros = mainModel::limpiar_cadena($registros);
            $privilegio = mainModel::limpiar_cadena($privilegio);
            
            $url = mainModel::limpiar_cadena($url);
            $url = SERVERURL . $url . "/";
            
            $busqueda = mainModel::limpiar_cadena($busqueda);
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1 ;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0 ;

            if (isset($busqueda) && $busqueda != "") {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM articulos WHERE (Estado = 1) AND (Codigo LIKE '%$busqueda%' OR Nombre LIKE '%$busqueda%' OR Fabricante LIKE '%$busqueda%') ORDER BY Codigo ASC LIMIT $inicio, $registros";
            } else {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM articulos WHERE Estado = 1 ORDER BY Codigo ASC LIMIT $inicio, $registros";
            }

            $conexion = mainModel::conectar();
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();

            $total = $conexion->query("SELECT FOUND_ROWS()");
            $total = (int) $total->fetchColumn();

            $Npaginas = ceil($total / $registros);
            
            $tabla .= '<div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                            <th>#</th>
                            <th>CÓDIGO</th>
                            <th>NOMBRE</th>
                            <th>STOCK</th>
                            <th>PRECIO COMPRA UD.</th>
                            <th>PRECIO VENTA UD.</th>
                            <th>FABRICANTE</th>
                            <th>DETALLE</th>
                            <th>PROVEEDOR</th>
                            <th>ESTADO</th>';
                            if ($privilegio == 1 || $privilegio == 2) {
                                $tabla .= '<th>ACTUALIZAR</th>';
                            }
                            if ($privilegio == 1) {
                                $tabla .= '<th>ELIMINAR</th>';
                            }  
                        $tabla .= '</tr>
                    </thead>
                    <tbody>';

            if ($total >= 1 && $pagina <= $Npaginas) {
                $contador = $inicio + 1;
                $reg_inicio = $inicio + 1;
                foreach ($datos as $rows) {
                    $tabla .= '
                    <tr class="text-center" >
                        <td>'.$contador.'</td>
                        <td>'.$rows['Codigo'].'</td>
                        <td>'.$rows['Nombre'].'</td>
                        <td>'.$rows['Stock'].'</td>
                        <td>'. MONEDA. " " .$rows['PrecioCompra'].'</td>
                        <td>'. MONEDA. " " .$rows['PrecioVenta'].'</td>
                        <td>'.$rows['Fabricante'].'</td>
                        <td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="'.$rows['Nombre'].'" data-content="'.$rows['Detalle'].'">
                            <i class="fas fa-info-circle"></i>
                        </button></td>';
                        if ($rows['IdProveedores'] != "") {
                            $proveedor = itemModelo::proveedor_item_modelo($rows['IdProveedores']);
                            if ($proveedor->rowCount() == 1) {
                                $campos = $proveedor->fetch();
                                $tabla .= '<td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="'.$campos['Nombre'].'" data-content="'.$campos['Telefono'].'&#10;'.$campos['Email'].'">
                                    <i class="fas fa-info-circle"></i>
                                </button></td>';
                            } else {
                                $tabla .= '<td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="Sin Nombre" data-content="Artículo sin proveedor registrado">
                                    <i class="fas fa-info-circle"></i>
                                </button></td>';
                            }
                            
                        } else {
                            $tabla .= '<td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="Sin Nombre" data-content="Artículo sin proveedor registrado">
                                    <i class="fas fa-info-circle"></i>
                                </button></td>';
                            
                        }
                        
                        
                        if ($rows['Stock'] > 0) {
                            $tabla .= '<td><span class="badge badge-success">Habilitado</span></td>';
                        } else {
                            $tabla .= '<td><span class="badge badge-danger">Deshabilitado</span></td>';
                        }
                        
                        if ($privilegio == 1 || $privilegio == 2) {
                            $tabla .= '<td>
                                        <a href="'.SERVERURL.'item-update/'.mainModel::encryption($rows['IdArticulos']).'/" class="btn btn-success">
                                            <i class="fas fa-sync-alt"></i>	
                                        </a>
                                    </td>';
                        }
                        if ($privilegio == 1) {
                            $tabla .= '<td>
                                        <form class="FormularioAjax" action="'.SERVERURL.'ajax/itemAjax.php" method="POST" data-form="delete" autocomplete="off">
                                            <input type="hidden" name="item_id_del" value="'.mainModel::encryption($rows['IdArticulos']).'">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>';
                        }
                    $tabla .='</tr>';
                    $contador++;
                }
                $reg_final = $contador - 1;
            } else {
                if ($total >= 1) {
                    $tabla .= '<tr class="text-center" ><td colspan="9">
                    <a href="'.$url.'" class="btn btn-raised btn-primary btn-sm">Haga click aqui para recargar el listado</a>
                    </td></tr>';
                } else {
                    $tabla .= '<tr class="text-center" ><td colspan="9">No hay registros en el sistema.</td></tr>';
                }
            }
            $tabla .= '</tbody></table></div>';
            
            if ($total >= 1 && $pagina <= $Npaginas) {
                $tabla .= '<p class="text-right">Mostrando artículos '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
            }
            return $tabla;
        }/*Fin controlador*/

        /*-----------Controlador para eliminar un artículo----------*/
        public function eliminar_item_controlador(){
            /* Recuperando el id del artículos */
            $id = mainModel::decryption($_POST['item_id_del']);
            $id = mainModel::limpiar_cadena($id);

            /* Comprobando el artículo en la BD */
            $check_item = mainModel::ejecutar_consulta_simple("SELECT IdArticulos FROM articulos WHERE IdArticulos = '$id' AND Estado = 1");
            if ($check_item->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El artículo que desea eliminar no existe en el sistema."
                ];
                echo json_encode($alerta);
                exit();
            }

            /* Comprobando privilegios */
            session_start(['name' => 'Auth']);
            if ($_SESSION['rol_auth'] != 1) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No tienes los permisos necesarios para realizar esta operación."
                ];
                echo json_encode($alerta);
                exit();
            }

            $eliminar_item = itemModelo::eliminar_item_modelo($id);
            if ($eliminar_item->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Artículo eliminado",
                    "Texto" => "El artículo fue eliminado del sistema correctamente."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El artículo no fue eliminado, por favor intente de nuevo."
                ];
            }
            echo json_encode($alerta);

        }/*Fin controlador*/

        /*-----------Controlador para datos de un artículo----------*/
        public function datos_item_controlador($tipo, $id){
            $tipo = mainModel::limpiar_cadena($tipo);
            $id = mainModel::decryption($id);
            $id = mainModel::limpiar_cadena($id);

            return itemModelo::datos_item_modelo($tipo, $id);
        }/*Fin controlador*/

        /*-----------Controlador para actualizar un artículo----------*/
        public function actualizar_item_controlador(){
            /* Recibiendo el id */
            $id = mainModel::decryption($_POST['item_id_up']);
            $id = mainModel::limpiar_cadena($id);

            /* Comprobar item en la BD */
            $check_item = mainModel::ejecutar_consulta_simple("SELECT * FROM articulos WHERE IdArticulos='$id' AND Estado = 1");
            if ($check_item->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El artículo no pudo ser encontrado en el sistema."
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $campos = $check_item->fetch();
            }

            $codigo = mainModel::limpiar_cadena($_POST['item_codigo_up']);
            $nombre = mainModel::limpiar_cadena($_POST['item_nombre_up']);
            $detalle = mainModel::limpiar_cadena($_POST['item_detalle_up']);
            $fabricante = mainModel::limpiar_cadena($_POST['item_fabricante_up']);
            $stock = mainModel::limpiar_cadena($_POST['item_stock_up']);
            $precioc = mainModel::limpiar_cadena($_POST['item_precioc_up']);
            $preciov = mainModel::limpiar_cadena($_POST['item_preciov_up']);
            $proveedor = mainModel::limpiar_cadena($_POST['item_proveedor_up']);
                       
            /*-----------Comprobar campos vacios-------------*/
            if ($codigo == "" || $nombre == "" || $stock == "" || $preciov == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "Faltan datos obligatorios."
                ];
                echo json_encode($alerta);
                exit();
            }

            /*-----------Comprobar formato de campos-------------*/
            if(mainModel::verificar_datos("[a-zA-Z0-9-]{1,10}", $codigo)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El código no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if(mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,50}", $nombre)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El Nombre no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if ($detalle != "") {
                if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $detalle)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El detalle no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
            if ($fabricante != "") {
                if(mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,50}", $fabricante)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El fabricante no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
            if(mainModel::verificar_datos("[0-9()+]{1,4}", $stock)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El stock no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if ($precioc != "") {
                if(mainModel::verificar_datos("[0-9().]{1,4}", $precioc)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El precio de compra no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }else{
                $precioc = "0";
            }
            if ($preciov != "") {
                if(mainModel::verificar_datos("[0-9().]{1,4}", $preciov)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El precio de venta no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            /*-----------Comprobar que el código sea único-------------*/
            if ($codigo != $campos['Codigo']) {
                $check_codigo = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM articulos WHERE Codigo = '$codigo'");
                if ($check_codigo->rowCount()>0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El Código ingresado ya se encuentra registrado en el sistema."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
            
            /*-----------Comprobar credenciales para actualizar datos-------------*/
            session_start(['name' => 'Auth']);
            if ($_SESSION['rol_auth'] < 1 || $_SESSION['rol_auth'] > 2) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No tiene los permisos necesarios para realizar estos cambios."
                ]; 
                echo json_encode($alerta);
                exit();
            }

            /*-----------Comprobando el proveedor sea correcto-------------*/
            if ($proveedor != 0) {
                if ($proveedor != $campos['IdProveedores']) {
                    $check_proveedor = mainModel::ejecutar_consulta_simple("SELECT Nombre FROM proveedores WHERE IdProveedores = '$proveedor' AND Estado = '1'");
                    if ($check_proveedor->rowCount() <= 0) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Tipo" => "error",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "El proveedor seleccionado no se encuentra registrado en el sistema."
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                }
            } else {
                $proveedor = null;
            }
            
            /*-----------Preparando datos para enviarlos al modelo-------------*/
            $codigo = mb_strtolower($codigo, 'UTF-8');
            $nombre = mb_strtolower($nombre, 'UTF-8');
            $nombre = mainModel::mb_ucfirst($nombre, 'UTF-8');
            $detalle = mb_strtolower($detalle, 'UTF-8');
            $detalle = mainModel::mb_ucfirst($detalle, 'UTF-8');
            $fabricante = mb_strtolower($fabricante, 'UTF-8');
            $fabricante = mainModel::mb_ucfirst($fabricante, 'UTF-8');
            $datos_item_up =[
                "Codigo" => $codigo,
                "Nombre" => $nombre,
                "Detalle" => $detalle,
                "Fabricante" => $fabricante,
                "Stock" => $stock,
                "PrecioCompra" => $precioc,
                "PrecioVenta" => $preciov,
                "IdProveedores" => $proveedor,
                "ID" => $id
            ];
            if (itemModelo::actualizar_item_modelo($datos_item_up)) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Datos actualizados",
                    "Texto" => "Los datos del artículo han sido actualizados con exito."
                ]; 
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido actualizar los datos del artículo, intente de nuevo."
                ]; 
            }
            echo json_encode($alerta);
        }/*Fin controlador*/
    }
?>