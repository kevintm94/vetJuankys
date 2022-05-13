<?php
    if ($peticionAjax) {
        require_once "../modelos/ventaModelo.php";
        require_once "../modelos/clienteModelo.php";
        require_once "../modelos/itemModelo.php";
        require_once "../modelos/medicinaModelo.php";
        require_once "../modelos/servicioModelo.php";
    } else {
        require_once "./modelos/ventaModelo.php";
        require_once "./modelos/clienteModelo.php";
        require_once "./modelos/itemModelo.php";
        require_once "./modelos/medicinaModelo.php";
        require_once "./modelos/servicioModelo.php";
    }
    
    class ventaControlador extends ventaModelo{
        
        /*-----------Controlador para agregar una venta----------*/
        public function agregar_venta_controlador(){
            $cliente = mainModel::limpiar_cadena($_POST['id_cliente_seleccionado']);
            $cliente = mainModel::decryption($cliente);
            $cantidad = mainModel::limpiar_cadena($_POST['canttotal_venta_reg']);
            $total = mainModel::limpiar_cadena($_POST['prectotal_venta_reg']);
            
            /*-----------Comprobar campos vacios-------------*/
            if ($cliente == "" || $cantidad == "0") {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "Faltan datos obligatorios."
                ];
                echo json_encode($alerta);
                exit();
            }
            $estado = 1;
            
            /*-----------Comprobar formato de campos-------------*/
            if(isset($_POST['item_reg'])){
                $articulos = $_POST['item_reg'];
                $cantidadArticulos = $_POST['cantidad_reg'];
            }
            if(isset($_POST['medicina_reg'])){
                $medicinas = $_POST['medicina_reg'];
                $cantidadMedicinas = $_POST['cantidad_medicina_reg'];
                $idLotes = $_POST['medicina_lote_reg'];
            }
            if(isset($_POST['servicio_reg'])){
                $servicios = $_POST['servicio_reg'];
            }

            session_start(['name' => 'Auth']);
            $idempleado = $_SESSION['id_auth'];
            /*-----------Preparando los datos para el registro-------------*/
            $datos_venta_reg = [
                "TotalPagar" => floatval($total),
                "IdClientes" => $cliente,
                "IdEmpleados" => $idempleado,
                "Fecha" => date("Y-m-d"),
                "Estado" => $estado
            ];
            $agregar_venta = ventaModelo::agregar_venta_modelo($datos_venta_reg);
            if ($agregar_venta->rowCount() == 1) {
                $conexion = mainModel::conectar();
                $rs = $conexion->query("SELECT MAX(IdVentas) AS id FROM ventas");
                $rs = $rs->fetchColumn();
                if(isset($_POST['item_reg'])){
                    $contador = 0;
                    foreach ($articulos as $articulo) {
                        $datos_detalle_venta_art_reg = [
                           "IdArticulos" => mainModel::decryption($articulo),
                           "IdVentas" => $rs,
                           "Cantidad" => $cantidadArticulos[$contador]
                        ];
                        $agregar_detalle_venta = ventaModelo::agregar_detalle_venta_articulo_modelo($datos_detalle_venta_art_reg);
                        if ($agregar_detalle_venta->rowCount() != 1) {
                            $alerta = [
                                "Alerta" => "simple",
                                "Tipo" => "error",
                                "Titulo" => "Ocurrió un error inesperado",
                                "Texto" => "Los detalles de la venta, artículo no se pudieron registrar correctamente."
                            ];
                            echo json_encode($alerta);
                            exit();
                        } else {
                            $stockArt = $conexion->query("SELECT Stock FROM articulos WHERE IdArticulos=" . mainModel::decryption($articulo));
                            $stockArt = $stockArt->fetchColumn();
                            $stockArt = $stockArt - $cantidadArticulos[$contador];
                            $query ="UPDATE articulos SET Stock=" . $stockArt . " WHERE IdArticulos=" . mainModel::decryption($articulo);
                            $update = $conexion->query($query);
                        }
                        $contador++;
                    }
                }
                if(isset($_POST['medicina_reg'])){
                    $contador = 0;
                    foreach ($medicinas as $medicina) {
                        $datos_detalle_venta_med_reg = [
                            "IdMedicinas" => mainModel::decryption($medicina),
                            "IdLotes" => $idLotes[$contador],
                            "IdVentas" => $rs,
                            "Cantidad" => $cantidadMedicinas[$contador]
                        ];
                        $agregar_detalle_venta = ventaModelo::agregar_detalle_venta_medicina_modelo($datos_detalle_venta_med_reg);
                        if ($agregar_detalle_venta->rowCount() != 1) {
                            $alerta = [
                                "Alerta" => "simple",
                                "Tipo" => "error",
                                "Titulo" => "Ocurrió un error inesperado",
                                "Texto" => "Los detalles de la venta, medicina no se pudieron registrar correctamente."
                            ];
                            echo json_encode($alerta);
                            exit();
                        } else {
                            $stockMed = $conexion->query("SELECT Stock FROM lotes WHERE IdLotes=" . $idLotes[$contador]);
                            $stockMed = $stockMed->fetchColumn();
                            $stockMed = $stockMed - $cantidadMedicinas[$contador];
                            $query ="UPDATE lotes SET Stock=" . $stockMed . " WHERE IdLotes=" . $idLotes[$contador];
                            $update = $conexion->query($query);
                        }
                        $contador++;
                    }
                }
                if(isset($_POST['servicio_reg'])){
                    $servicios = $_POST['servicio_reg'];
                    $contador = 0;
                    foreach ($servicios as $servicio) {
                        $datos_detalle_venta_serv_reg = [
                            "IdServicios" => mainModel::decryption($servicio),
                            "IdVentas" => $rs,
                            "Cantidad" => 1
                        ];
                        $agregar_detalle_venta = ventaModelo::agregar_detalle_venta_servicio_modelo($datos_detalle_venta_serv_reg);
                        if ($agregar_detalle_venta->rowCount() != 1) {
                            $alerta = [
                                "Alerta" => "simple",
                                "Tipo" => "error",
                                "Titulo" => "Ocurrió un error inesperado",
                                "Texto" => "Los detalles de la venta, servicio no se pudieron registrar correctamente."
                            ];
                            echo json_encode($alerta);
                            exit();
                        }
                        $contador++;
                    }
                }
                
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Venta registrada",
                    "Texto" => "La fue registrado con éxito."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La venta no fue registrada correctamente."
                ];
            }
            echo json_encode($alerta);
        } /*Fin controlador*/

        /*-----------Controlador para paginar ventas----------*/
        public function paginador_venta_controlador($pagina, $registros, $privilegio, $url, $fecha_ini, $fecha_fin){
            $pagina = mainModel::limpiar_cadena($pagina);
            $registros = mainModel::limpiar_cadena($registros);
            $privilegio = mainModel::limpiar_cadena($privilegio);
            
            $url = mainModel::limpiar_cadena($url);
            $url = SERVERURL . $url . "/";
            
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1 ;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0 ;

            if (isset($fecha_ini) && $fecha_ini != "") {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM ventas WHERE Estado = 1 AND Fecha >= '$fecha_ini' AND Fecha <= '$fecha_fin' ORDER BY Fecha DESC LIMIT $inicio, $registros";
            } else {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS ventas.*, CONCAT_WS(' ', clientes.Nombres, clientes.Apellidos) AS Cliente FROM ventas JOIN clientes ON clientes.IdClientes = ventas.IdClientes WHERE ventas.Estado = 1 ORDER BY Fecha DESC, IdVentas DESC LIMIT $inicio, $registros";
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
                            <th>CLIENTE</th>
                            <th>FECHA</th>
                            <th>TOTAL</th>
                            <th>DETALLE</th>';
                            if ($privilegio == 1 || $privilegio == 2) {
                                $tabla .= '<th>RECIBO</th>';
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
                        <td>'.$rows['Cliente'].'</td>
                        <td>'.$rows['Fecha'].'</td>
                        <td>'. MONEDA. " " .$rows['TotalPagar'].'</td>
                        <td><button type="button" class="btn btn-info" onclick="mostrar_detalle(\''.mainModel::encryption($rows['IdVentas']).'\')">
                            <i class="fas fa-info-circle"></i>
                        </button></td>';
                        
                        if ($privilegio == 1 || $privilegio == 2) {
                            $tabla .= '<td>
                                        <a href="'.SERVERURL.'report-recibo/'.mainModel::encryption($rows['IdVentas']).'" class="btn btn-success">
                                            <i class="fas fa-file-pdf"></i>	
                                        </a>
                                    </td>';
                        }
                        if ($privilegio == 1) {
                            $tabla .= '<td>
                                        <form class="FormularioAjax" action="'.SERVERURL.'ajax/ventaAjax.php" method="POST" data-form="delete" autocomplete="off">
                                            <input type="hidden" name="venta_id_del" value="'.mainModel::encryption($rows['IdVentas']).'">
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
                $tabla .= '<p class="text-right">Mostrando cursos '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
            }
            return $tabla;
        }/*Fin controlador*/

        /*-----------Controlador para eliminar un venta----------*/
        public function eliminar_venta_controlador(){
            /* Recuperando el id del artículos */
            $id = mainModel::decryption($_POST['venta_id_del']);
            $id = mainModel::limpiar_cadena($id);

            /* Comprobando el artículo en la BD */
            $check_curso = mainModel::ejecutar_consulta_simple("SELECT IdVentas FROM ventas WHERE IdVentas = '$id' AND Estado = 1");
            if ($check_curso->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La venta que desea eliminar no existe en el sistema."
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

            $eliminar_venta = ventaModelo::eliminar_venta_modelo($id);
            if ($eliminar_venta->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Venta eliminada",
                    "Texto" => "La venta fue eliminada del sistema correctamente."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La venta no fue eliminado, por favor intente de nuevo."
                ];
            }
            echo json_encode($alerta);

        }/*Fin controlador*/

        /*-----------Controlador para datos de un curso----------*/
        public function datos_venta_controlador($tipo, $id){
            $tipo = mainModel::limpiar_cadena($tipo);
            $id = mainModel::decryption($id);
            $id = mainModel::limpiar_cadena($id);

            return ventaModelo::datos_venta_modelo($tipo, $id);
        }/*Fin controlador*/

        /*-----------Controlador para actualizar un curso----------*/
        public function actualizar_curso_controlador(){
            /* Recibiendo el id */
            $id = mainModel::decryption($_POST['curso_id_up']);
            $id = mainModel::limpiar_cadena($id);

            /* Comprobar curso en la BD */
            $check_curso = mainModel::ejecutar_consulta_simple("SELECT * FROM cursos WHERE IdCursos='$id' AND EstadoBD = 1");
            if ($check_curso->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El curso no pudo ser encontrado en el sistema."
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $campos = $check_curso->fetch();
            }

            $codigo = mainModel::limpiar_cadena($_POST['curso_codigo_up']);
            $nombre = mainModel::limpiar_cadena($_POST['curso_nombre_up']);
            $detalle = mainModel::limpiar_cadena($_POST['curso_detalle_up']);
            $sesiones = mainModel::limpiar_cadena($_POST['curso_sesiones_up']);
            $precio = mainModel::limpiar_cadena($_POST['curso_precio_up']);
            $estado = mainModel::limpiar_cadena($_POST['curso_estado_up']);
                       
            /*-----------Comprobar campos vacios-------------*/
            if ($codigo == "" || $nombre == "" || $estado == "" || $precio == "" || $sesiones == "") {
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
            if(mainModel::verificar_datos("[1-9]{1,3}", $sesiones)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El número de sesiones no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if(mainModel::verificar_datos("[0-9().]{1,4}", $precio)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El precio no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if ($estado < 0 || $estado > 1) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El estado tiene un valor inválido."
                ];
                echo json_encode($alerta);
                exit();
            }

            /*-----------Comprobar que el código sea único-------------*/
            if ($codigo != $campos['Codigo']) {
                $check_codigo = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM cursos WHERE Codigo = '$codigo'");
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
            }

            /*-----------Comprobar que el nombre sea único-------------*/
            if ($nombre != $campos['Nombre']) {
                $check_nombre = mainModel::ejecutar_consulta_simple("SELECT Nombre FROM cursos WHERE Nombre = '$nombre'");
                if ($check_nombre->rowCount()>0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El nombre del curso ingresado ya se encuentra registrado en el sistema."
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
            
            /*-----------Preparando datos para enviarlos al modelo-------------*/
            $codigo = mb_strtolower($codigo, 'UTF-8');
            $nombre = mb_strtolower($nombre, 'UTF-8');
            $nombre = mainModel::mb_ucfirst($nombre, 'UTF-8');
            $detalle = mb_strtolower($detalle, 'UTF-8');
            $detalle = mainModel::mb_ucfirst($detalle, 'UTF-8');
            $datos_curso_up =[
                "Codigo" => $codigo,
                "Nombre" => $nombre,
                "Detalle" => $detalle,
                "Sesiones" => $sesiones,
                "Precio" => $precio,
                "Estado" => $estado,
                "ID" => $id
            ];
            if (cursoModelo::actualizar_curso_modelo($datos_curso_up)) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Datos actualizados",
                    "Texto" => "Los datos del curso han sido actualizados con éxito."
                ]; 
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido actualizar los datos del curso, intente de nuevo."
                ]; 
            }
            echo json_encode($alerta);
        }/*Fin controlador*/
    }
?>