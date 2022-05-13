<?php
    if ($peticionAjax) {
        require_once "../modelos/usuarioModelo.php";
    } else {
        require_once "./modelos/usuarioModelo.php";
    }
    
    class usuarioControlador extends usuarioModelo{
        
        /*-----------Controlador para agregar empleado y su usuario----------*/
        public function agregar_usuario_controlador(){
            $ci = mainModel::limpiar_cadena($_POST['usuario_ci_reg']);
            $nombres = mainModel::limpiar_cadena($_POST['usuario_nombre_reg']);
            $apellidos = mainModel::limpiar_cadena($_POST['usuario_apellido_reg']);
            $telefono = mainModel::limpiar_cadena($_POST['usuario_telefono_reg']);
            $direccion = mainModel::limpiar_cadena($_POST['usuario_direccion_reg']);

            $usuario = mainModel::limpiar_cadena($_POST['usuario_usuario_reg']);
            $clave1 = mainModel::limpiar_cadena($_POST['usuario_clave_1_reg']);
            $clave2 = mainModel::limpiar_cadena($_POST['usuario_clave_2_reg']);

            $privilegio = mainModel::limpiar_cadena($_POST['usuario_privilegio_reg']);

            /*-----------Comprobar campos vacios-------------*/
            if ($ci == "" || $nombres == "" || $apellidos == "" || $usuario == "" || $clave1 == "" || $clave2 == "") {
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
            if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}", $usuario)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El usuario no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave1) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave2)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "Las claves no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }

            /*-----------Comprobar que el ci sea único-------------*/
            $check_ci = mainModel::ejecutar_consulta_simple("SELECT CI FROM empleados WHERE CI = '$ci'");
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

            /*-----------Comprobar que el usuario sea único-------------*/
            $check_user = mainModel::ejecutar_consulta_simple("SELECT Usuario FROM empleados WHERE Usuario = '$usuario'");
            if ($check_user->rowCount()>0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El usuario ingresado ya se encuentra registrado en el sistema."
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------Comprobar que las claves coincidan-------------*/
            if ($clave1 != $clave2) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "Las claves que ingreso no coinciden."
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $clave = mainModel::encryption($clave1);
            }
            
            /*-----------Comprobar privilegios-------------*/
            if ($privilegio < 1 || $privilegio > 3) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El privilegio seleccionado no es válido."
                ];
                echo json_encode($alerta);
                exit();
            }
            
            /*-----------Preparando datos para enviar al modelo-------------*/
            $nombres = mb_strtolower($nombres, 'UTF-8');
            $nombres = mb_convert_case($nombres, MB_CASE_TITLE, 'UTF-8');
            $apellidos = mb_strtolower($apellidos, 'UTF-8');
            $apellidos = mb_convert_case($apellidos, MB_CASE_TITLE, 'UTF-8');
            $direccion = mb_strtolower($direccion, 'UTF-8');
            $direccion = mainModel::mb_ucfirst($direccion, 'UTF-8');
            $datos_usuario_reg = [
                "CI" => $ci,
                "Nombres" => $nombres,
                "Apellidos" => $apellidos,
                "Telefono" => $telefono,
                "Direccion" => $direccion,
                "Usuario" => $usuario,
                "Clave" => $clave,
                "Estado" => "1",
                "IdRoles" => $privilegio
            ];
            $agregar_usuario = usuarioModelo::agregar_usuario_modelo($datos_usuario_reg);
            if ($agregar_usuario->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "limpiar",
                    "Tipo" => "success",
                    "Titulo" => "Empleado registrado",
                    "Texto" => "El empleado fue registrado con exito."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El empleado no fue registrado correctamente."
                ];
            }
            echo json_encode($alerta);
        } /*Fin controlador*/

        /*-----------Controlador para paginar empleado y su usuario----------*/
        public function paginador_usuario_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda){
            $pagina = mainModel::limpiar_cadena($pagina);
            $registros = mainModel::limpiar_cadena($registros);
            $privilegio = mainModel::limpiar_cadena($privilegio);
            $id = mainModel::limpiar_cadena($id);
            $url = mainModel::limpiar_cadena($url);
            $url = SERVERURL . $url . "/";
            $busqueda = mainModel::limpiar_cadena($busqueda);
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1 ;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0 ;

            if (isset($busqueda) && $busqueda != "") {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM empleados WHERE ((IdEmpleados != '$id' AND IdEmpleados != '1' AND Estado != '0') AND (CI LIKE '%$busqueda%' OR Nombres LIKE '%$busqueda%' OR Apellidos LIKE '%$busqueda%' OR Usuario LIKE '%$busqueda%')) ORDER BY Nombres ASC LIMIT $inicio, $registros";
            } else {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM empleados WHERE IdEmpleados != '$id' AND IdEmpleados != '1' AND Estado != '0' ORDER BY Nombres ASC LIMIT $inicio, $registros";
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
                            <th>CI</th>
                            <th>NOMBRE COMPLETO</th>
                            <th>TELÉFONO</th>
                            <th>USUARIO</th>
                            <th>ACTUALIZAR</th>
                            <th>ELIMINAR</th>
                        </tr>
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
                        <td>'.$rows['Usuario'].'</td>
                        <td>
                            <a href="'.SERVERURL.'user-update/'.mainModel::encryption($rows['IdEmpleados']).'/" class="btn btn-success">
                                <i class="fas fa-sync-alt"></i>	
                            </a>
                        </td>
                        <td>
                            <form class="FormularioAjax" action="'.SERVERURL.'ajax/usuarioAjax.php" method="POST" data-form="delete" autocomplete="off">
                                <input type="hidden" name="usuario_id_del" value="'.mainModel::encryption($rows['IdEmpleados']).'">
                                <button type="submit" class="btn btn-warning">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>';
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
                $tabla .= '<p class="text-right">Mostrando empleado '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
            }
            return $tabla;
        }/*Fin controlador*/

        /*-----------Controlador para eliminar empleado y su usuario----------*/
        public function eliminar_usuario_controlador(){
            /* Recuperando el id del empleado */
            $id = mainModel::decryption($_POST['usuario_id_del']);
            $id = mainModel::limpiar_cadena($id);
            
            /* Comprobando el usuario */
            if ($id == 1) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No podemos eliminar el usuario principal del sistema."
                ];
                echo json_encode($alerta);
                exit();
            }

            /* Comprobando el empleado en la BD */
            $check_usuario = mainModel::ejecutar_consulta_simple("SELECT IdEmpleados FROM empleados WHERE IdEmpleados = '$id'");
            if ($check_usuario->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El empleado que desea eliminar no existe en el sistema."
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

            $eliminar_usuario = usuarioModelo::eliminar_usuario_modelo($id);
            if ($eliminar_usuario->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Empleado eliminado",
                    "Texto" => "El empleado fue eliminado del sistema correctamente."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El empleado no fue eliminado, por favor intente de nuevo."
                ];
            }
            echo json_encode($alerta);

        }/*Fin controlador*/

        /*-----------Controlador para datos de empleado y su usuario----------*/
        public function datos_usuario_controlador($tipo, $id){
            $tipo = mainModel::limpiar_cadena($tipo);
            $id = mainModel::decryption($id);
            $id = mainModel::limpiar_cadena($id);

            return usuarioModelo::datos_usuario_modelo($tipo, $id);
        }/*Fin controlador*/

        /*-----------Controlador para actualizar empleado y su usuario----------*/
        public function actualizar_usuario_controlador(){
            /* Recibiendo el id */
            $id = mainModel::decryption($_POST['usuario_id_up']);
            $id = mainModel::limpiar_cadena($id);

            /* Comprobar usuario en la BD */
            $check_user = mainModel::ejecutar_consulta_simple("SELECT * FROM empleados WHERE IdEmpleados='$id'");
            if ($check_user->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El empleado no pudo ser encontrado en el sistema."
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $campos = $check_user->fetch();
            }

            $ci = mainModel::limpiar_cadena($_POST['usuario_ci_up']);
            $nombres = mainModel::limpiar_cadena($_POST['usuario_nombre_up']);
            $apellidos = mainModel::limpiar_cadena($_POST['usuario_apellido_up']);
            $telefono = mainModel::limpiar_cadena($_POST['usuario_telefono_up']);
            $direccion = mainModel::limpiar_cadena($_POST['usuario_direccion_up']);
            
            $usuario = mainModel::limpiar_cadena($_POST['usuario_usuario_up']);
            
            if (isset($_POST['usuario_estado_up'])) {
                $estado = mainModel::limpiar_cadena($_POST['usuario_estado_up']);
            } else {
                $estado = $campos['Estado'];
            }
            
            if (isset($_POST['usuario_privilegio_up'])) {
                $privilegio = mainModel::limpiar_cadena($_POST['usuario_privilegio_up']);
            } else {
                $privilegio = $campos['IdRoles'];
            }

            $admin_usuario = mainModel::limpiar_cadena($_POST['usuario_admin']);
            $admin_clave = mainModel::limpiar_cadena($_POST['clave_admin']);

            $tipo_cuenta = mainModel::limpiar_cadena($_POST['tipo_cuenta']);
            
            /*-----------Comprobar campos vacios-------------*/
            if ($ci == "" || $nombres == "" || $apellidos == "" || $usuario == "" || $admin_usuario == "" || $admin_clave == "") {
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
            if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}", $usuario)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El usuario no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}", $admin_usuario)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "Tu usuario no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $admin_clave)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "Tu clave no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }
            
            $admin_clave = mainModel::encryption($admin_clave);
            
            if ($privilegio < 1 || $privilegio > 3) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El privilegio no corresponde a un valor válido."
                ];
                echo json_encode($alerta);
                exit();
            }
            if ($estado != 1 && $estado != 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El estado de la cuenta es un valor inválido."
                ];
                echo json_encode($alerta);
                exit();
            }

            /*-----------Comprobar que el ci sea único-------------*/
            if ($ci != $campos['CI']) {
                $check_ci = mainModel::ejecutar_consulta_simple("SELECT CI FROM empleados WHERE CI = '$ci'");
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
            

            /*-----------Comprobar que el usuario sea único-------------*/
            if ($usuario != $campos['Usuario']) {
                $check_user = mainModel::ejecutar_consulta_simple("SELECT Usuario FROM empleados WHERE Usuario = '$usuario'");
                if ($check_user->rowCount()>0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrio un error inesperado",
                        "Texto" => "El usuario ingresado ya se encuentra registrado en el sistema."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            /*-----------Comprobar claves-------------*/
            if ($_POST['usuario_clave_nueva_1'] != "" || $_POST['usuario_clave_nueva_2'] != "") {
                if ($_POST['usuario_clave_nueva_1'] != $_POST['usuario_clave_nueva_2']) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrio un error inesperado",
                        "Texto" => "Las nuevas claves ingresadas no coinciden."
                    ];
                    echo json_encode($alerta);
                    exit();
                } else {
                    if (mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $_POST['usuario_clave_nueva_1']) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $_POST['usuario_clave_nueva_2'])) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Tipo" => "error",
                            "Titulo" => "Ocurrio un error inesperado",
                            "Texto" => "Las nuevas claves ingresadas no coinciden con el formato solicitado."
                        ]; 
                        echo json_encode($alerta);
                        exit();
                    }
                    $clave = mainModel::encryption($_POST['usuario_clave_nueva_1']);
                }
                
            } else {
                $clave = $campos['Clave'];
            }
            
            /*-----------Comprobar credenciales para actualizar datos-------------*/
            if ($tipo_cuenta == "Propia") {
                $check_cuenta = mainModel::ejecutar_consulta_simple("SELECT IdEmpleados FROM empleados WHERE Usuario='$admin_usuario' AND Clave='$admin_clave' AND IdEmpleados='$id'");
            } else {
                session_start(['name' => 'Auth']);
                if ($_SESSION['rol_auth'] != 1) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrio un error inesperado",
                        "Texto" => "No tiene los permisos necesarios para realizar estos cambios."
                    ]; 
                    echo json_encode($alerta);
                    exit();
                }
                $check_cuenta = mainModel::ejecutar_consulta_simple("SELECT IdEmpleados FROM empleados WHERE Usuario='$admin_usuario' AND Clave='$admin_clave'");
            }
            if ($check_cuenta->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "Nombre y clave de administrador no válidos."
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
            $datos_usuario_up =[
                "CI" => $ci,
                "Nombres" => $nombres,
                "Apellidos" => $apellidos,
                "Telefono" => $telefono,
                "Direccion" => $direccion,
                "Usuario" => $usuario,
                "Clave" => $clave,
                "Estado" => $estado,
                "IdRoles" => $privilegio,
                "ID" => $id
            ];
            if (usuarioModelo::actualizar_usuario_modelo($datos_usuario_up)) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Datos actualizados",
                    "Texto" => "Los datos han sido actualizados con exito."
                ]; 
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "No hemos podido actualizar los datos, intente de nuevo."
                ]; 
            }
            echo json_encode($alerta);
        }/*Fin controlador*/
    }
?>