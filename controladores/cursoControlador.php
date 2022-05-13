<?php
    if ($peticionAjax) {
        require_once "../modelos/cursoModelo.php";
    } else {
        require_once "./modelos/cursoModelo.php";
    }
    
    class cursoControlador extends cursoModelo{
        
        /*-----------Controlador para agregar un curso----------*/
        public function agregar_curso_controlador(){
            $codigo = mainModel::limpiar_cadena($_POST['curso_codigo_reg']);
            $nombre = mainModel::limpiar_cadena($_POST['curso_nombre_reg']);
            $detalle = mainModel::limpiar_cadena($_POST['curso_detalle_reg']);
            $sesiones = mainModel::limpiar_cadena($_POST['curso_sesiones_reg']);
            $precio = mainModel::limpiar_cadena($_POST['curso_precio_reg']);
            $estado = 1;
            $estadobd = 1;
            
            /*-----------Comprobar campos vacios-------------*/
            if ($codigo == "" || $nombre == "" || $sesiones == "" || $precio == "") {
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
                    "Texto" => "El precio de venta no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }

            /*-----------Comprobar que el codigo sea único-------------*/
            $check_codigo = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM cursos WHERE Codigo = '$codigo'");
            if ($check_codigo->rowCount()>0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El codigo ingresado ya se encuentra registrado en el sistema."
                ];
                echo json_encode($alerta);
                exit();
            }

            /*-----------Comprobar que el nombre sea único-------------*/
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
            
            /*-----------Preparando los datos para el registro-------------*/
            $codigo = mb_strtolower($codigo, 'UTF-8');
            $nombre = mb_strtolower($nombre, 'UTF-8');
            $nombre = mainModel::mb_ucfirst($nombre, 'UTF-8');
            $detalle = mb_strtolower($detalle, 'UTF-8');
            $detalle = mainModel::mb_ucfirst($detalle, 'UTF-8');
            $datos_curso_reg = [
                "Codigo" => $codigo,
                "Nombre" => $nombre,
                "Detalle" => $detalle,
                "Sesiones" => $sesiones,
                "Precio" => $precio,
                "Estado" => $estado,
                "EstadoBD" => $estadobd
            ];
            $agregar_curso = cursoModelo::agregar_curso_modelo($datos_curso_reg);
            if ($agregar_curso->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "limpiar",
                    "Tipo" => "success",
                    "Titulo" => "Curso registrado",
                    "Texto" => "El curso fue registrado con éxito."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El curso no fue registrado correctamente."
                ];
            }
            echo json_encode($alerta);
        } /*Fin controlador*/

        /*-----------Controlador para paginar cursos----------*/
        public function paginador_curso_controlador($pagina, $registros, $privilegio, $url, $busqueda){
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
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM cursos WHERE (EstadoBD = 1) AND (Codigo LIKE '%$busqueda%' OR Nombre LIKE '%$busqueda%') ORDER BY Codigo ASC LIMIT $inicio, $registros";
            } else {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM cursos WHERE EstadoBD = 1 ORDER BY Codigo ASC LIMIT $inicio, $registros";
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
                            <th>NÚMERO DE SESIONES</th>
                            <th>PRECIO</th>
                            <th>DETALLE</th>
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
                        <td>'.$rows['Sesiones'].'</td>
                        <td>'. MONEDA. " " .$rows['Precio'].'</td>
                        <td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="'.$rows['Nombre'].'" data-content="'.$rows['Detalle'].'">
                            <i class="fas fa-info-circle"></i>
                        </button></td>';
                        if ($rows['Estado'] == 1) {
                            $tabla .= '<td><span class="badge badge-success">Habilitado</span></td>';
                        } else {
                            $tabla .= '<td><span class="badge badge-danger">Deshabilitado</span></td>';
                        }
                        
                        if ($privilegio == 1 || $privilegio == 2) {
                            $tabla .= '<td>
                                        <a href="'.SERVERURL.'course-update/'.mainModel::encryption($rows['IdCursos']).'/" class="btn btn-success">
                                            <i class="fas fa-sync-alt"></i>	
                                        </a>
                                    </td>';
                        }
                        if ($privilegio == 1) {
                            $tabla .= '<td>
                                        <form class="FormularioAjax" action="'.SERVERURL.'ajax/cursoAjax.php" method="POST" data-form="delete" autocomplete="off">
                                            <input type="hidden" name="curso_id_del" value="'.mainModel::encryption($rows['IdCursos']).'">
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

        /*-----------Controlador para eliminar un curso----------*/
        public function eliminar_curso_controlador(){
            /* Recuperando el id del artículos */
            $id = mainModel::decryption($_POST['curso_id_del']);
            $id = mainModel::limpiar_cadena($id);

            /* Comprobando el artículo en la BD */
            $check_curso = mainModel::ejecutar_consulta_simple("SELECT IdCursos FROM cursos WHERE IdCursos = '$id' AND EstadoBD = 1");
            if ($check_curso->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El curso que desea eliminar no existe en el sistema."
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

            $eliminar_curso = cursoModelo::eliminar_curso_modelo($id);
            if ($eliminar_curso->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Curso eliminado",
                    "Texto" => "El curso fue eliminado del sistema correctamente."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El curso no fue eliminado, por favor intente de nuevo."
                ];
            }
            echo json_encode($alerta);

        }/*Fin controlador*/

        /*-----------Controlador para datos de un curso----------*/
        public function datos_curso_controlador($tipo, $id){
            $tipo = mainModel::limpiar_cadena($tipo);
            $id = mainModel::decryption($id);
            $id = mainModel::limpiar_cadena($id);

            return cursoModelo::datos_curso_modelo($tipo, $id);
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