<?php
    if ($peticionAjax) {
        require_once "../modelos/proveedorModelo.php";
    } else {
        require_once "./modelos/proveedorModelo.php";
    }

    class proveedorControlador extends proveedorModelo{
        
        /*-----------Controlador para agregar un proveedor----------*/
        public function agregar_proveedor_controlador(){
            $nombre = mainModel::limpiar_cadena($_POST['proveedor_nombre_reg']);
            $telefono = mainModel::limpiar_cadena($_POST['proveedor_telefono_reg']);
            $email = mainModel::limpiar_cadena($_POST['proveedor_email_reg']);
            $direccion = mainModel::limpiar_cadena($_POST['proveedor_direccion_reg']);

            /*-----------Comprobar campos vacios-------------*/
            if ($nombre == "" || $telefono == "") {
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
            if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,50}", $nombre)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El Nombre no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if(mainModel::verificar_datos("[0-9()+]{7,8}", $telefono)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El telefono no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if ($direccion != "") {
                if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}", $direccion)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "La direccion no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            /*-----------Comprobar que el telefono sea único-------------*/
            $check_telefono = mainModel::ejecutar_consulta_simple("SELECT Telefono FROM proveedores WHERE Telefono = '$telefono'");
            if ($check_telefono->rowCount()>0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El telefono ingresado ya se encuentra registrado en el sistema."
                ];
                echo json_encode($alerta);
                exit();
            }

            /*-----------Comprobar email-------------*/
            if ($email != "") {
                if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
                    $check_email = mainModel::ejecutar_consulta_simple("SELECT Email FROM proveedores WHERE Email = '$email'");
                    if ($check_email->rowCount()>0) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Tipo" => "error",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "El correo electrónico ingresado ya se encuentra registrado en el sistema."
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                } else {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El correo electrónico ingresado no es válido."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            /*-----------Preparando datos para la inserción-------------*/
            $nombre = mb_strtolower($nombre, 'UTF-8');
            $nombre = mb_convert_case($nombre, MB_CASE_TITLE, 'UTF-8');
            $email = mb_strtolower($email, 'UTF-8');
            $direccion = mb_strtolower($direccion, 'UTF-8');
            $direccion = mainModel::mb_ucfirst($direccion, 'UTF-8');

            $datos_proveedor_reg = [
                "Nombre" => $nombre,
                "Telefono" => $telefono,
                "Email" => $email,
                "Direccion" => $direccion,
                "Estado" => "1",
            ];
            
            $agregar_proveedor = proveedorModelo::agregar_proveedor_modelo($datos_proveedor_reg);

            if ($agregar_proveedor->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "limpiar",
                    "Tipo" => "success",
                    "Titulo" => "Proveedor registrado",
                    "Texto" => "El proveedor fue registrado con éxito."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El proveedor no fue registrado correctamente, intente de nuevo."
                ];
            }
            echo json_encode($alerta);
        }/* Fin controlador */

        /*-----------Controlador para paginar proveedores----------*/
        public function paginador_proveedor_controlador($pagina, $registros, $privilegio, $url, $busqueda){
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
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM proveedores WHERE Estado = '1' AND Nombre LIKE '%$busqueda%' OR Telefono LIKE '%$busqueda%' ORDER BY Nombre ASC LIMIT $inicio, $registros";
            } else {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM proveedores WHERE Estado = '1' ORDER BY Nombre ASC LIMIT $inicio, $registros";
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
                            <th>NOMBRE COMPLETO</th>
                            <th>TELÉFONO</th>
                            <th>EMAIL</th>
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
                        <td>'.$rows['Nombre'].'</td>
                        <td>'.$rows['Telefono'].'</td>
                        <td>'.$rows['Email'].'</td>
                        <td>'.$rows['Direccion'].'</td>';
                        
                        if ($privilegio == 1 || $privilegio == 2) {
                            $tabla .= '<td>
                                        <a href="'.SERVERURL.'provider-update/'.mainModel::encryption($rows['IdProveedores']).'/" class="btn btn-success">
                                            <i class="fas fa-sync-alt"></i>	
                                        </a>
                                    </td>';
                        }
                        if ($privilegio == 1) {
                            $tabla .= '<td>
                                        <form class="FormularioAjax" action="'.SERVERURL.'ajax/proveedorAjax.php" method="POST" data-form="delete" autocomplete="off">
                                            <input type="hidden" name="proveedor_id_del" value="'.mainModel::encryption($rows['IdProveedores']).'">
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
                $tabla .= '<p class="text-right">Mostrando proveedor '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
            }
            return $tabla;
        }/*Fin controlador*/

        /*-----------Controlador para eliminar un proveedor----------*/
        public function eliminar_proveedor_controlador(){
            /* Recuperando el id del proveedor */
            $id = mainModel::decryption($_POST['proveedor_id_del']);
            $id = mainModel::limpiar_cadena($id);

            /* Comprobando el proveedor en la BD */
            $check_proveedor = mainModel::ejecutar_consulta_simple("SELECT IdProveedores FROM proveedores WHERE IdProveedores = '$id'");
            if ($check_proveedor->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El proveedor que desea eliminar no existe en el sistema."
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

            $eliminar_proveedor = proveedorModelo::eliminar_proveedor_modelo($id);
            if ($eliminar_proveedor->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Proveedor eliminado",
                    "Texto" => "El proveedor fue eliminado del sistema correctamente."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El proveedor no fue eliminado, por favor intente de nuevo."
                ];
            }
            echo json_encode($alerta);

        }/*Fin controlador*/

        /*-----------Controlador para datos de proveedor----------*/
        public function datos_proveedor_controlador($tipo, $id){
            $tipo = mainModel::limpiar_cadena($tipo);
            $id = mainModel::decryption($id);
            $id = mainModel::limpiar_cadena($id);

            return proveedorModelo::datos_proveedor_modelo($tipo, $id);
        }/*Fin controlador*/

        /*-----------Controlador para actualizar proveedor----------*/
        public function actualizar_proveedor_controlador(){
            /* Recibiendo el id */
            $id = mainModel::decryption($_POST['proveedor_id_up']);
            $id = mainModel::limpiar_cadena($id);

            /* Comprobar proveedor en la BD */
            $check_proveedor = mainModel::ejecutar_consulta_simple("SELECT * FROM proveedores WHERE IdProveedores='$id'");
            if ($check_proveedor->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El proveedor no pudo ser encontrado en el sistema."
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $campos = $check_proveedor->fetch();
            }

            $nombre = mainModel::limpiar_cadena($_POST['proveedor_nombre_up']);
            $telefono = mainModel::limpiar_cadena($_POST['proveedor_telefono_up']);
            $email = mainModel::limpiar_cadena($_POST['proveedor_email_up']);
            $direccion = mainModel::limpiar_cadena($_POST['proveedor_direccion_up']);
            
            /*-----------Comprobar campos vacíos-------------*/
            if ($nombre == "" || $telefono == "") {
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
            if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,50}", $nombre)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El Nombre no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if(mainModel::verificar_datos("[0-9]{7,8}", $telefono)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El telefono no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if ($direccion != "") {
                if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $direccion)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "La direccion no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            /*-----------Comprobar que el ci sea único-------------*/
            if ($telefono != $campos['Telefono']) {
                $check_telefono = mainModel::ejecutar_consulta_simple("SELECT Telefono FROM proveedores WHERE Telefono = '$telefono'");
                if ($check_telefono->rowCount()>0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El telefono ingresado ya se encuentra registrado en el sistema."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
            
            /*-----------Comprobar email-------------*/
            if ($email != $campos['Email']) {
                if ($email != "") {
                    if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
                        $check_email = mainModel::ejecutar_consulta_simple("SELECT Email FROM proveedores WHERE Email = '$email'");
                        if ($check_email->rowCount()>0) {
                            $alerta = [
                                "Alerta" => "simple",
                                "Tipo" => "error",
                                "Titulo" => "Ocurrió un error inesperado",
                                "Texto" => "El correo electrónico ingresado ya se encuentra registrado en el sistema."
                            ];
                            echo json_encode($alerta);
                            exit();
                        }
                    } else {
                        $alerta = [
                            "Alerta" => "simple",
                            "Tipo" => "error",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "El correo electrónico ingresado no es válido."
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
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
            $nombre = mb_strtolower($nombre, 'UTF-8');
            $nombre = mb_convert_case($nombre, MB_CASE_TITLE, 'UTF-8');
            $email = mb_strtolower($email, 'UTF-8');
            $direccion = mb_strtolower($direccion, 'UTF-8');
            $direccion = mainModel::mb_ucfirst($direccion, 'UTF-8');
            
            $datos_proveedor_up =[
                "Nombre" => $nombre,
                "Telefono" => $telefono,
                "Email" => $email,
                "Direccion" => $direccion,
                "ID" => $id
            ];
            if (proveedorModelo::actualizar_proveedor_modelo($datos_proveedor_up)) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Datos actualizados",
                    "Texto" => "Los datos del proveedor han sido actualizados con éxito."
                ]; 
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido actualizar los datos del proveedor, intente de nuevo."
                ]; 
            }
            echo json_encode($alerta);
        }/*Fin controlador*/
    }
?>  