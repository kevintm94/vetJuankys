<?php
    if(isset($peticionAjax)){
        if ($peticionAjax) {
            require_once "../modelos/loteModelo.php";
        } else {
            require_once "./modelos/loteModelo.php";
        }
    }else{
        require_once "../modelos/loteModelo.php";
    }
    class loteControlador extends loteModelo{
        
        /*-----------Controlador para agregar un lote----------*/
        public function agregar_lote_controlador(){
            $presentacion = mainModel::limpiar_cadena($_POST['lote_presentacion_reg']);
            $fechav = mainModel::limpiar_cadena($_POST['lote_fechav_reg']);
            $stock = mainModel::limpiar_cadena($_POST['lote_stock_reg']);
            $precioc = mainModel::limpiar_cadena($_POST['lote_precioc_reg']);
            $estado = 1;
            
            /*-----------Comprobar campos vacios-------------*/
            if ($presentacion == "" || $fechav == "" || $stock == "") {
                echo '
                    <script>
                        Swal.fire({
                            type: "error",
                            title: "Ocurrio un error inesperador",
                            text: "No se han llenado todos los campos que son requeridos.",
                            confirmButtonText: "Aceptar"
                        });
                    </script>
                ';
                exit();
            }

            /*-----------Comprobar formato de campos-------------*/
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
            if(mainModel::verificar_datos("[0-9()-]{10,10}", $fechav)){
                $valores = explode('-', $fechav);
                if (count($valores) != 3 && !checkdate($valores[1],$valores[2],$valores[0])) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "La fecha de vencimiento no coincide con el formato solicitado."
                    ];
                }
                echo json_encode($alerta);
                exit();
            }
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

            /*-----------Comprobando el proveedor sea correcto-------------*/
            $check_presentacion = mainModel::ejecutar_consulta_simple("SELECT Nombre FROM presentaciones WHERE IdPresentaciones = '$presentacion'");
            if ($check_presentacion->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La presentacion seleccionada no se encuentra registrada en el sistema."
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------Preparando los datos para el registro-------------*/
            $datos_lote_reg = [
                "Stock" => $stock,
                "FechaVencimiento" => $fechav,
                "PrecioCompra" => $precioc,
                "Estado" => $estado,
                "IdPresentaciones" => $presentacion
            ];
            $agregar_lote = loteModelo::agregar_lote_modelo($datos_lote_reg);
            if ($agregar_lote->rowCount() == 1) {
                echo '
                    <script>
                        Swal.fire({
                            type: "success",
                            title: "Lote registrado",
                            text: "El lote fue registrado con éxito.",
                            confirmButtonText: "Aceptar"
                        });
                    </script>
                ';
            } else {
                echo '
                    <script>
                        Swal.fire({
                            type: "error",
                            title: "Ocurrio un error inesperador",
                            text: "El lote no fue registrado correctamente.",
                            confirmButtonText: "Aceptar"
                        });
                    </script>
                ';
            }
            return header("Location: ".SERVERURL."medicine-list/");
        } /*Fin controlador*/

        /*-----------Controlador para listar presentaciones----------*/
        public function presentaciones_lote_controlador($id){
            $id = mainModel::decryption($id);
            $id = mainModel::limpiar_cadena($id);
            $datos_presentaciones = loteModelo::presentaciones_lote_modelo($id);
            if($datos_presentaciones->rowCount() > 0){
                return $datos = $datos_presentaciones->fetchAll();
            }else{
                return $datos = "";
            }
        }

        /*-----------Controlador para listar lotes de un medicamento----------*/
        public function lista_lote_controlador($id){
            $id = mainModel::decryption($id);
            $id = mainModel::limpiar_cadena($id);
            $datos_lotes = loteModelo::lista_lote_modelo($id);
            if($datos_lotes->rowCount() > 0){
                return $datos = $datos_lotes->fetchAll();
            }else{
                return $datos = "";
            }
        }/*Fin controlador*/

        /*-----------Controlador para eliminar un artículo----------*/
        public function eliminar_lote_controlador(){
            /* Recuperando el id del artículos */
            $id = mainModel::decryption($_POST['lote_id_del']);
            $id = mainModel::limpiar_cadena($id);

            /* Comprobando el artículo en la BD */
            $check_lote = mainModel::ejecutar_consulta_simple("SELECT IdLotes FROM lotes WHERE IdLotes = '$id' AND Estado = 1");
            if ($check_lote->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El lote que desea eliminar no existe en el sistema."
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

            $eliminar_lote = loteModelo::eliminar_lote_modelo($id);
            if ($eliminar_lote->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Lote eliminado",
                    "Texto" => "El lote fue eliminado del sistema correctamente."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Lote un error inesperado",
                    "Texto" => "El lote no fue eliminado, por favor intente de nuevo."
                ];
            }
            return header("Location: ".SERVERURL."medicine-list/");

        }/*Fin controlador*/

        /*-----------Controlador para datos de un artículo----------*/
        public function datos_lote_controlador($tipo, $id){
            $tipo = mainModel::limpiar_cadena($tipo);
            $id = mainModel::decryption($id);
            $id = mainModel::limpiar_cadena($id);

            return loteModelo::datos_lote_modelo($tipo, $id);
        }/*Fin controlador*/

        /*-----------Controlador para actualizar un artículo----------*/
        public function actualizar_lote_controlador(){
            /* Recibiendo el id */
            $id = mainModel::decryption($_POST['lote_id_up']);
            $id = mainModel::limpiar_cadena($id);

            /* Comprobar lote en la BD */
            $check_lote = mainModel::ejecutar_consulta_simple("SELECT * FROM lotes WHERE IdLotes='$id' AND Estado = 1");
            if ($check_lote->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El lote no pudo ser encontrado en el sistema."
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $campos = $check_lote->fetch();
            }
            
            $presentacion = mainModel::limpiar_cadena($_POST['lote_presentacion_up']);
            $fechav = mainModel::limpiar_cadena($_POST['lote_fechav_up']);
            $stock = mainModel::limpiar_cadena($_POST['lote_stock_up']);
            $precioc = mainModel::limpiar_cadena($_POST['lote_precioc_up']);

            /*-----------Comprobar campos vacios-------------*/
            if ($presentacion == "" || $fechav == "" || $stock == "" || $precioc == "") {
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
            if(mainModel::verificar_datos("[0-9()-]{10,10}", $fechav)){
                $valores = explode('-', $fechav);
                if (count($valores) != 3 && !checkdate($valores[1],$valores[2],$valores[0])) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "La fecha de vencimiento no coincide con el formato solicitado."
                    ];
                }
                echo json_encode($alerta);
                exit();
            }
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
            $datos_lote_up =[
                "IdPresentaciones" => $presentacion,
                "FechaVencimiento" => $fechav,
                "Stock" => $stock,
                "PrecioCompra" => $precioc,
                "ID" => $id
            ];
            if (loteModelo::actualizar_lote_modelo($datos_lote_up)) {
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
            return header("Location: ".SERVERURL."medicine-list/");
        }/*Fin controlador*/
    }
?>