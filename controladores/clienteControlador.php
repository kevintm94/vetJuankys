<?php
    if ($peticionAjax) {
        require_once "../modelos/clienteModelo.php";
    } else {
        require_once "./modelos/clienteModelo.php";
    }

    class clienteControlador extends clienteModelo{
        
        /*-----------Controlador para agregar un cliente----------*/
        public function agregar_cliente_controlador(){
            $ci = mainModel::limpiar_cadena($_POST['cliente_ci_reg']);
            $nombres = mainModel::limpiar_cadena($_POST['cliente_nombre_reg']);
            $apellidos = mainModel::limpiar_cadena($_POST['cliente_apellido_reg']);
            $telefono = mainModel::limpiar_cadena($_POST['cliente_telefono_reg']);
            $direccion = mainModel::limpiar_cadena($_POST['cliente_direccion_reg']);

            /*-----------Comprobar campos vacios-------------*/
            if ($ci == "" || $nombres == "" || $apellidos == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "Faltan datos obligatorios."
                ];
                echo json_encode($alerta);
                exit();
            }

            /*-----------Comprobar formato de campos-------------*/
            if(mainModel::verificar_datos("[0-9-]{7,10}", $ci)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El CI no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}", $nombres)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El Nombre no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}", $apellidos)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El apellido no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if ($telefono != "") {
                if(mainModel::verificar_datos("[0-9()+]{7,8}", $telefono)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrio un error inesperado",
                        "Texto" => "El telefono no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
            if ($direccion != "") {
                if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}", $direccion)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrio un error inesperado",
                        "Texto" => "La direccion no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            /*-----------Comprobar que el ci sea único-------------*/
            $check_ci = mainModel::ejecutar_consulta_simple("SELECT CI FROM clientes WHERE CI = '$ci'");
            if ($check_ci->rowCount()>0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El CI ingresado ya se encuentra registrado en el sistema."
                ];
                echo json_encode($alerta);
                exit();
            }

            /*-----------Preparando datos para la inserción-------------*/
            $nombres = mb_strtolower($nombres, 'UTF-8');
            $nombres = mb_convert_case($nombres, MB_CASE_TITLE, 'UTF-8');
            $apellidos = mb_strtolower($apellidos, 'UTF-8');
            $apellidos = mb_convert_case($apellidos, MB_CASE_TITLE, 'UTF-8');
            $direccion = mb_strtolower($direccion, 'UTF-8');
            $direccion = mainModel::mb_ucfirst($direccion, 'UTF-8');
            $datos_cliente_reg = [
                "CI" => $ci,
                "Nombres" => $nombres,
                "Apellidos" => $apellidos,
                "Telefono" => $telefono,
                "Direccion" => $direccion,
                "Estado" => "1",
            ];
            $agregar_cliente = clienteModelo::agregar_cliente_modelo($datos_cliente_reg);

            if ($agregar_cliente->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "limpiar",
                    "Tipo" => "success",
                    "Titulo" => "Cliente registrado",
                    "Texto" => "El cliente fue registrado con exito."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El cliente no fue registrado correctamente, intente de nuevo."
                ];
            }
            echo json_encode($alerta);
        }/* Fin controlador */

        /*-----------Controlador para paginar clientes----------*/
        public function paginador_cliente_controlador($pagina, $registros, $privilegio, $url, $busqueda){
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
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM clientes WHERE Estado != '0' AND CI LIKE '%$busqueda%' OR Nombres LIKE '%$busqueda%' OR Apellidos LIKE '%$busqueda%' ORDER BY Nombres ASC LIMIT $inicio, $registros";
            } else {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM clientes WHERE Estado != '0' ORDER BY Nombres ASC LIMIT $inicio, $registros";
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
                            <th>CI O NIT</th>
                            <th>NOMBRE COMPLETO</th>
                            <th>TELÉFONO</th>
                            <th>DIRECCIÓN</th>';
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
                        <td>'.$rows['CI'].'</td>
                        <td>'.$rows['Nombres']." ".$rows['Apellidos'].'</td>
                        <td>'.$rows['Telefono'].'</td>
                        <td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="'.$rows['Nombres'].' '.$rows['Apellidos'].'" data-content="'.$rows['Direccion'].'">
                            <i class="fas fa-info-circle"></i>
                        </button></td>';
                        
                        if ($privilegio == 1 || $privilegio == 2) {
                            $tabla .= '<td>
                                        <a href="'.SERVERURL.'client-update/'.mainModel::encryption($rows['IdClientes']).'/" class="btn btn-success">
                                            <i class="fas fa-sync-alt"></i>	
                                        </a>
                                    </td>';
                        }
                        if ($privilegio == 1) {
                            $tabla .= '<td>
                                        <form class="FormularioAjax" action="'.SERVERURL.'ajax/clienteAjax.php" method="POST" data-form="delete" autocomplete="off">
                                            <input type="hidden" name="cliente_id_del" value="'.mainModel::encryption($rows['IdClientes']).'">
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
                $tabla .= '<p class="text-right">Mostrando cliente '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
            }
            return $tabla;
        }/*Fin controlador*/

        /*-----------Controlador para eliminar un cliente----------*/
        public function eliminar_cliente_controlador(){
            /* Recuperando el id del empleado */
            $id = mainModel::decryption($_POST['cliente_id_del']);
            $id = mainModel::limpiar_cadena($id);

            /* Comprobando el cliente en la BD */
            $check_cliente = mainModel::ejecutar_consulta_simple("SELECT IdClientes FROM clientes WHERE IdClientes = '$id'");
            if ($check_cliente->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El cliente que desea eliminar no existe en el sistema."
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

            $eliminar_cliente = clienteModelo::eliminar_cliente_modelo($id);
            if ($eliminar_cliente->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Cliente eliminado",
                    "Texto" => "El cliente fue eliminado del sistema correctamente."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El cliente no fue eliminado, por favor intente de nuevo."
                ];
            }
            echo json_encode($alerta);

        }/*Fin controlador*/

        /*-----------Controlador para datos de cliente----------*/
        public function datos_cliente_controlador($tipo, $id){
            $tipo = mainModel::limpiar_cadena($tipo);
            $id = mainModel::decryption($id);
            $id = mainModel::limpiar_cadena($id);

            return clienteModelo::datos_cliente_modelo($tipo, $id);
        }/*Fin controlador*/

        /*-----------Controlador para actualizar cliente----------*/
        public function actualizar_cliente_controlador(){
            /* Recibiendo el id */
            $id = mainModel::decryption($_POST['cliente_id_up']);
            $id = mainModel::limpiar_cadena($id);

            /* Comprobar cliente en la BD */
            $check_cliente = mainModel::ejecutar_consulta_simple("SELECT * FROM clientes WHERE IdClientes='$id'");
            if ($check_cliente->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El cliente no pudo ser encontrado en el sistema."
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $campos = $check_cliente->fetch();
            }

            $ci = mainModel::limpiar_cadena($_POST['cliente_ci_up']);
            $nombres = mainModel::limpiar_cadena($_POST['cliente_nombre_up']);
            $apellidos = mainModel::limpiar_cadena($_POST['cliente_apellido_up']);
            $telefono = mainModel::limpiar_cadena($_POST['cliente_telefono_up']);
            $direccion = mainModel::limpiar_cadena($_POST['cliente_direccion_up']);
            
            /*-----------Comprobar campos vacios-------------*/
            if ($ci == "" || $nombres == "" || $apellidos == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "Faltan datos obligatorios."
                ];
                echo json_encode($alerta);
                exit();
            }

            /*-----------Comprobar formato de campos-------------*/
            if(mainModel::verificar_datos("[0-9-]{7,10}", $ci)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El CI no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}", $nombres)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El Nombre no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}", $apellidos)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El apellido no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if ($telefono != "") {
                if(mainModel::verificar_datos("[0-9()+]{7,8}", $telefono)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrio un error inesperado",
                        "Texto" => "El telefono no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
            if ($direccion != "") {
                if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $direccion)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrio un error inesperado",
                        "Texto" => "La direccion no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            /*-----------Comprobar que el ci sea único-------------*/
            if ($ci != $campos['CI']) {
                $check_ci = mainModel::ejecutar_consulta_simple("SELECT CI FROM clientes WHERE CI = '$ci'");
                if ($check_ci->rowCount()>0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrio un error inesperado",
                        "Texto" => "El CI ingresado ya se encuentra registrado en el sistema."
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
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "No tiene los permisos necesarios para realizar estos cambios."
                ]; 
                echo json_encode($alerta);
                exit();
            }
            
            /*-----------Preparando datos para enviarlos al modelo-------------*/
            $nombres = mb_strtolower($nombres, 'UTF-8');
            $nombres = mb_convert_case($nombres, MB_CASE_TITLE, 'UTF-8');
            $apellidos = mb_strtolower($apellidos, 'UTF-8');
            $apellidos = mb_convert_case($apellidos, MB_CASE_TITLE, 'UTF-8');
            $direccion = mb_strtolower($direccion, 'UTF-8');
            $direccion = mainModel::mb_ucfirst($direccion, 'UTF-8');
            $datos_cliente_up =[
                "CI" => $ci,
                "Nombres" => $nombres,
                "Apellidos" => $apellidos,
                "Telefono" => $telefono,
                "Direccion" => $direccion,
                "ID" => $id
            ];
            if (clienteModelo::actualizar_cliente_modelo($datos_cliente_up)) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Datos actualizados",
                    "Texto" => "Los datos del cliente han sido actualizados con exito."
                ]; 
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "No hemos podido actualizar los datos del cliente, intente de nuevo."
                ]; 
            }
            echo json_encode($alerta);
        }/*Fin controlador*/
    }
?>  