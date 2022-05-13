<?php
    if ($peticionAjax) {
        require_once "../modelos/medicinaModelo.php";
        require_once "../modelos/presentacionModelo.php";
    } else {
        require_once "./modelos/medicinaModelo.php";
        require_once "./modelos/presentacionModelo.php";
    }
    
    class medicinaControlador extends medicinaModelo{
        
        /*-----------Controlador para agregar una medicina----------*/
        public function agregar_medicina_controlador(){
            $codigo = mainModel::limpiar_cadena($_POST['medicina_codigo_reg']);
            $nombre = mainModel::limpiar_cadena($_POST['medicina_nombre_reg']);
            $detalle = mainModel::limpiar_cadena($_POST['medicina_detalle_reg']);
            $laboratorio = mainModel::limpiar_cadena($_POST['medicina_laboratorio_reg']);
            $proveedor = mainModel::limpiar_cadena($_POST['medicina_proveedor_reg']);
            $estado = 1;

            /*----------Datos de las presentaciones----------*/
            $nombrepre = $_POST['presentacion_embase_reg'];
            $contenidopre = $_POST['presentacion_contenido_reg'];
            $medidapre = $_POST['presentacion_medida_reg'];
            $preciovpre = $_POST['presentacion_preciov_reg'];
            //echo('<pre>');
            //var_dump($nombrepre);
            //echo('</pre>');
            $contador = 0;
            foreach ($nombrepre as $keynom) {
                $nombrepre[$contador] = mainModel::limpiar_cadena($keynom);
                $contador ++;
            }
            $contador = 0;
            foreach ($contenidopre as $keycon) {
                $contenidopre[$contador] = mainModel::limpiar_cadena($keycon);
                $contador ++;
            }
            $contador = 0;
            foreach ($medidapre as $keymed) {
                $medidapre[$contador] = mainModel::limpiar_cadena($keymed);
                $contador ++;
            }
            $contador = 0;
            foreach ($preciovpre as $keyprev) {
                $preciovpre[$contador] = mainModel::limpiar_cadena($keyprev);
                $contador ++;
            }

            /*-----------Comprobar campos vacios-------------*/
            if ($codigo == "" || $nombre == "" || $nombrepre == "" || $contenidopre == "" || $medidapre == "" || $preciovpre == "") {
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
            if(mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ,.]{1,50}", $nombre)){
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
            if ($laboratorio != "") {
                if(mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,50}", $laboratorio)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El nombre del laboratorio no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            /*-----------Comprobar formato de campos de las presentaciones-------------*/
            foreach ($nombrepre as $valuenom) {
                if(mainModel::verificar_datos("[a-zA-Z0-9-]{1,10}", $valuenom)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Uno de los nombres de los embases no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
            foreach ($contenidopre as $valuecon) {
                if(mainModel::verificar_datos("[0-9]{1,4}", $valuecon)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Uno de los valores de los contenidos no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
            foreach ($preciovpre as $valueprev) {
                if(mainModel::verificar_datos("[0-9.]{1,4}", $valueprev)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Uno de los valores de los precios de venta no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
    
            /*-----------Comprobar que el codigo sea único-------------*/
            $check_codigo = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM medicinas WHERE Codigo = '$codigo'");
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
                       
            /*-----------Preparando los datos para enviar al modelo-------------*/
            $codigo = mb_strtolower($codigo, 'UTF-8');
            $nombre = mb_strtolower($nombre, 'UTF-8');
            $nombre = mainModel::mb_ucfirst($nombre, 'UTF-8');
            $detalle = mb_strtolower($detalle, 'UTF-8');
            $detalle = mainModel::mb_ucfirst($detalle, 'UTF-8');
            $laboratorio = mb_strtolower($laboratorio, 'UTF-8');
            $laboratorio = mainModel::mb_ucfirst($laboratorio, 'UTF-8');
            $datos_medicina_reg = [
                "Codigo" => $codigo,
                "Nombre" => $nombre,
                "Detalle" => $detalle,
                "Laboratorio" => $laboratorio,
                "Estado" => $estado,
                "IdProveedores" => $proveedor
            ];

            $contador = 0;
            foreach ($nombrepre as $keynom) {
                $nombrepre[$contador] = mb_strtolower($keynom, 'UTF-8');
                $nombrepre[$contador] = mainModel::mb_ucfirst($keynom, 'UTF-8');
                $contador ++;
            }
            

            $agregar_medicina = medicinaModelo::agregar_medicina_modelo($datos_medicina_reg);
            if ($agregar_medicina->rowCount() == 1) {
                $consulta = "SELECT IdMedicinas FROM medicinas WHERE Codigo = '$codigo' AND Estado = '1'";
                $conexion = mainModel::conectar();
                $datos = $conexion->query($consulta);
                $datos = $datos->fetch();
                $nro = sizeof($nombrepre);
                $idmedicina = $datos['IdMedicinas'];
                for ($i=0; $i < $nro; $i++) {
                    $datos_presentacion_reg = [
                        "Nombre" => $nombrepre[$i] . " " . $contenidopre[$i] . " " . $medidapre[$i],
                        "PrecioVenta" => $preciovpre[$i],
                        "IdMedicinas" => $idmedicina
                    ];
                    $agregar_presentacion = presentacionModelo::agregar_presentacion_modelo($datos_presentacion_reg);
                    if ($agregar_presentacion->rowCount() != 1) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Tipo" => "error",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "Las presentaciones no se pudieron registrar correctamente."
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                }

                $alerta = [
                    "Alerta" => "limpiar",
                    "Tipo" => "success",
                    "Titulo" => "Medicina registrada",
                    "Texto" => "La medicina fue registrada con éxito."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La medicina no fue registrada correctamente."
                ];
            }
            echo json_encode($alerta);
        } /*Fin controlador*/

        /*-----------Modelo para listar proveedores----------*/
        public function proveedores_medicina_controlador($tipo, $id){
            $consulta = "SELECT IdProveedores, Nombre FROM proveedores WHERE Estado = '1'";
            $conexion = mainModel::conectar();
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();

            if ($tipo == "Nuevo") {
                $lista = '<div class="form-group">
                        <label for="medicina_proveedor_reg" class="bmd-label-floating">Proveedor</label>
                        <select class="form-control" name="medicina_proveedor_reg">';

                $lista .= '<option value="0" selected="">Seleccione una opción</option>';
                foreach ($datos as $rows) {
                    $lista .= '<option value="'.$rows['IdProveedores'].'">'.$rows['Nombre'].'</option>';
                }
            } else {
                $lista = '<div class="form-group">
                        <label for="medicina_proveedor_up" class="bmd-label-floating">Proveedor</label>
                        <select class="form-control" name="medicina_proveedor_up">';
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

        /*-----------Controlador para paginar medicinas----------*/
        public function paginador_medicina_controlador($pagina, $registros, $privilegio, $url, $busqueda){
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
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM medicinas WHERE (Estado = 1) AND (Codigo LIKE '%$busqueda%' OR Nombre LIKE '%$busqueda%' OR Laboratorio LIKE '%$busqueda%') ORDER BY Codigo ASC LIMIT $inicio, $registros";
            } else {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM medicinas WHERE Estado = 1 ORDER BY Codigo ASC LIMIT $inicio, $registros";
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
                            <th>LABORATORIO</th>
                            <th>DETALLE</th>
                            <th>PROVEEDOR</th>
                            <th>ESTADO</th>
                            <th>LOTES</th>';
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
                    $stock = medicinaModelo::stock_medicina_modelo($rows['IdMedicinas']);
                    $stock = $stock->fetch();
                    $tabla .= '
                    <tr class="text-center" >
                        <td>'.$contador.'</td>
                        <td>'.$rows['Codigo'].'</td>
                        <td>'.$rows['Nombre'].'</td>
                        <td>'.$stock['Suma'].'</td>
                        <td>'.$rows['Laboratorio'].'</td>
                        <td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="'.$rows['Nombre'].'" data-content="'.$rows['Detalle'].'">
                            <i class="fas fa-info-circle"></i>
                        </button></td>';
                        if ($rows['IdProveedores'] != "") {
                            $proveedor = medicinaModelo::proveedor_medicina_modelo($rows['IdProveedores']);
                            if ($proveedor->rowCount() == 1) {
                                $campos = $proveedor->fetch();
                                $tabla .= '<td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="'.$campos['Nombre'].'" data-content="'.$campos['Telefono'].'&#10;'.$campos['Email'].'">
                                    <i class="fas fa-info-circle"></i>
                                </button></td>';
                            } else {
                                $tabla .= '<td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="Sin Nombre" data-content="Medicina sin proveedor registrado">
                                    <i class="fas fa-info-circle"></i>
                                </button></td>';
                            }
                            
                        } else {
                            $tabla .= '<td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="Sin Nombre" data-content="Medicina sin proveedor registrado">
                                    <i class="fas fa-info-circle"></i>
                                </button></td>';
                            
                        }
                        
                        
                        if ($stock['Suma'] > 0) {
                            $tabla .= '<td><span class="badge badge-success">Habilitado</span></td>';
                        } else {
                            $tabla .= '<td><span class="badge badge-danger">Deshabilitado</span></td>';
                        }
                        
                        $tabla .= '<td>
                                        <button type="button" class="btn btn-success" onclick="agregar_lote(\''.mainModel::encryption($rows['IdMedicinas']).'\')">
                                            <i class="fas fa-plus"></i>	
                                        </button>
                                        <button type="button" class="btn btn-warning" onclick="listar_lote(\''.mainModel::encryption($rows['IdMedicinas']).'\')">
                                            <i class="fas fa-list"></i>
                                        </button>
                                    </td>';

                        if ($privilegio == 1 || $privilegio == 2) {
                            $tabla .= '<td>
                                        <a href="'.SERVERURL.'medicine-update/'.mainModel::encryption($rows['IdMedicinas']).'/" class="btn btn-success">
                                            <i class="fas fa-sync-alt"></i>	
                                        </a>
                                    </td>';
                        }
                        if ($privilegio == 1) {
                            $tabla .= '<td>
                                        <form class="FormularioAjax" action="'.SERVERURL.'ajax/medicinaAjax.php" method="POST" data-form="delete" autocomplete="off">
                                            <input type="hidden" name="medicina_id_del" value="'.mainModel::encryption($rows['IdMedicinas']).'">
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
                $tabla .= '<p class="text-right">Mostrando medicinas '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
            }
            return $tabla;
        }/*Fin controlador*/

        /*-----------Controlador para eliminar una medicina----------*/
        public function eliminar_medicina_controlador(){
            /* Recuperando el id de la medicinas */
            $id = mainModel::decryption($_POST['medicina_id_del']);
            $id = mainModel::limpiar_cadena($id);

            /* Comprobando la medicina en la BD */
            $check_medicina = mainModel::ejecutar_consulta_simple("SELECT IdMedicinas FROM medicinas WHERE IdMedicinas = '$id' AND Estado = 1");
            if ($check_medicina->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La medicina que desea eliminar no existe en el sistema."
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

            $eliminar_medicina = medicinaModelo::eliminar_medicina_modelo($id);
            if ($eliminar_medicina->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Medicina eliminada",
                    "Texto" => "La medicina fue eliminada del sistema correctamente."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La medicina no fue eliminada, por favor intente de nuevo."
                ];
            }
            echo json_encode($alerta);

        }/*Fin controlador*/

        /*-----------Controlador para eliminar una presentacion de la medicina----------*/
        public function eliminar_presentacion_medicina_controlador(){
            /* Recuperando el id de la medicinas */
            $id = mainModel::limpiar_cadena($id);

            /* Comprobando la medicina en la BD */
            $check_presentacion = mainModel::ejecutar_consulta_simple("SELECT IdPresentaciones FROM presentaciones WHERE IdPresentaciones = '$id'");
            if ($check_presentacion->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La presentacion que desea eliminar no existe en el sistema."
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

            $eliminar_presentacion = presentacionModelo::eliminar_presentacion_modelo($id);
            if ($eliminar_presentacion->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Presentación eliminada",
                    "Texto" => "La presentación fue eliminada del sistema correctamente."
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La presentación no fue eliminada, por favor intente de nuevo."
                ];
            }
            echo json_encode($alerta);

        }/*Fin controlador*/

        /*-----------Controlador para datos de una medicina----------*/
        public function datos_medicina_controlador($tipo, $id){
            $tipo = mainModel::limpiar_cadena($tipo);
            $id = mainModel::decryption($id);
            $id = mainModel::limpiar_cadena($id);

            return medicinaModelo::datos_medicina_modelo($tipo, $id);
        }/*Fin controlador*/

        /*-----------Controlador para datos de las presentaciones una medicina----------*/
        public function datos_presentacion_medicina_controlador($tipo, $id){
            $tipo = mainModel::limpiar_cadena($tipo);
            $id = mainModel::decryption($id);
            $id = mainModel::limpiar_cadena($id);

            return presentacionModelo::datos_presentacion_modelo($tipo, $id);
        }/*Fin controlador*/

        /*-----------Controlador para actualizar una medicina----------*/
        public function actualizar_medicina_controlador(){
            /* Recibiendo el id */
            $id = mainModel::decryption($_POST['medicina_id_up']);
            $id = mainModel::limpiar_cadena($id);

            /* Comprobar medicina en la BD */
            $check_medicina = mainModel::ejecutar_consulta_simple("SELECT * FROM medicinas WHERE IdMedicinas='$id' AND Estado = 1");
            if ($check_medicina->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La medicina no pudo ser encontrada en el sistema."
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $campos = $check_medicina->fetch();
            }

            $codigo = mainModel::limpiar_cadena($_POST['medicina_codigo_up']);
            $nombre = mainModel::limpiar_cadena($_POST['medicina_nombre_up']);
            $detalle = mainModel::limpiar_cadena($_POST['medicina_detalle_up']);
            $laboratorio = mainModel::limpiar_cadena($_POST['medicina_laboratorio_up']);
            $proveedor = mainModel::limpiar_cadena($_POST['medicina_proveedor_up']);

            /*----------Datos de las presentaciones----------*/
            $idpresentaciones = $_POST['presentacion_id_up'];
            $embase = $_POST['presentacion_embase_up'];
            $contenido = $_POST['presentacion_contenido_up'];
            $medida = $_POST['presentacion_medida_up'];
            $preciovpre = $_POST['presentacion_preciov_up'];

            $contador = 0;
            foreach ($embase as $keynom) {
                $embase[$contador] = mainModel::limpiar_cadena($keynom);
                $contador ++;
            }
            $contador = 0;
            foreach ($contenido as $keycon) {
                $contenido[$contador] = mainModel::limpiar_cadena($keycon);
                $contador ++;
            }
            $contador = 0;
            foreach ($medida as $keymed) {
                $medida[$contador] = mainModel::limpiar_cadena($keymed);
                $contador ++;
            }
            $contador = 0;
            foreach ($preciovpre as $keyprev) {
                $preciovpre[$contador] = mainModel::limpiar_cadena($keyprev);
                $contador ++;
            }
            $contador = 0;
            foreach ($idpresentaciones as $keyid) {
                $idpresentaciones[$contador] = mainModel::decryption($keyid);
                $idpresentaciones[$contador] = mainModel::limpiar_cadena($idpresentaciones[$contador]);
                $contador ++;
            }

            /* Comprobar presentaciones en la BD */
            foreach ($idpresentaciones as $idpre) {
                $check_presentacion = mainModel::ejecutar_consulta_simple("SELECT * FROM presentaciones WHERE IdPresentaciones='$idpre'");
                if ($check_presentacion->rowCount() <= 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Una de las presentaciones no pudo ser encontrada en el sistema."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
                       
            /*-----------Comprobar campos vacios-------------*/
            if ($codigo == "" || $nombre == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "Faltan datos obligatorios."
                ];
                echo json_encode($alerta);
                exit();
            }
            $nroids = sizeof($idpresentaciones);
            $nropresentaciones = sizeof($embase);
            for ($i=0; $i < $nropresentaciones; $i++) { 
                if ($embase[$i] == "" || $contenido[$i] == "" || $preciovpre[$i] == "") {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Faltan datos obligatorios."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
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
            if(mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ,.]{1,50}", $nombre)){
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
            if(mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,50}", $laboratorio)){
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El nombre del laboratorio no coincide con el formato solicitado."
                ];
                echo json_encode($alerta);
                exit();
            }

            /*-----------Comprobar formato de campos de las presentaciones-------------*/
            foreach ($embase as $valuenom) {
                if(mainModel::verificar_datos("[a-zA-Z0-9-]{1,10}", $valuenom)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Uno de los nombres de los embases no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
            foreach ($contenido as $valuecon) {
                if(mainModel::verificar_datos("[0-9]{1,4}", $valuecon)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Uno de los valores de los contenidos no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
            foreach ($preciovpre as $valueprev) {
                if(mainModel::verificar_datos("[0-9.]{1,4}", $valueprev)){
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Uno de los valores de los precios de venta no coincide con el formato solicitado."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            /*-----------Comprobar que el código sea único-------------*/
            if ($codigo != $campos['Codigo']) {
                $check_codigo = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM medicinaís WHERE Codigo = '$codigo'");
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
            $laboratorio = mb_strtolower($laboratorio, 'UTF-8');
            $laboratorio = mainModel::mb_ucfirst($laboratorio, 'UTF-8');
            $datos_medicina_up =[
                "Codigo" => $codigo,
                "Nombre" => $nombre,
                "Detalle" => $detalle,
                "Laboratorio" => $laboratorio,
                "IdProveedores" => $proveedor,
                "ID" => $id
            ];

            $contador = 0;
            foreach ($embase as $keynom) {
                $embase[$contador] = mb_strtolower($keynom, 'UTF-8');
                $embase[$contador] = mainModel::mb_ucfirst($keynom, 'UTF-8');
                $contador ++;
            }

            if (medicinaModelo::actualizar_medicina_modelo($datos_medicina_up)) {
                if ($nroids == $nropresentaciones) {
                    for ($i=0; $i < $nroids; $i++) {
                        $datos_presentacion_up = [
                            "Nombre" => $embase[$i] . " " . $contenido[$i] . " " . $medida[$i],
                            "PrecioVenta" => $preciovpre[$i],
                            "IdMedicinas" => $id,
                            "ID" => $idpresentaciones[$i]
                        ];
                        if (!presentacionModelo::actualizar_presentacion_modelo($datos_presentacion_up)) {
                            $alerta = [
                                "Alerta" => "simple",
                                "Tipo" => "error",
                                "Titulo" => "Ocurrió un error inesperado",
                                "Texto" => "Las presentaciones no se pudieron actualizar correctamente."
                            ];
                            echo json_encode($alerta);
                            exit();
                        }
                    }
                } else if ( $nroids < $nropresentaciones ) {
                    for ($i=0; $i < $nroids; $i++) {
                        $datos_presentacion_up = [
                            "Nombre" => $embase[$i] . " " . $contenido[$i] . " " . $medida[$i],
                            "PrecioVenta" => $preciovpre[$i],
                            "IdMedicinas" => $id,
                            "ID" => $idpresentaciones[$i]
                        ];
                        if (!presentacionModelo::actualizar_presentacion_modelo($datos_presentacion_up)) {
                            $alerta = [
                                "Alerta" => "simple",
                                "Tipo" => "error",
                                "Titulo" => "Ocurrió un error inesperado",
                                "Texto" => "Las presentaciones no se pudieron actualizar correctamente."
                            ];
                            echo json_encode($alerta);
                            exit();
                        }
                    }
                    for ($i=$nroids; $i < $nropresentaciones; $i++) {
                        $datos_presentacion_reg = [
                            "Nombre" => $embase[$i] . " " . $contenido[$i] . " " . $medida[$i],
                            "PrecioVenta" => $preciovpre[$i],
                            "IdMedicinas" => $id
                        ];
                        $agregar_presentacion = presentacionModelo::agregar_presentacion_modelo($datos_presentacion_reg);
                        if ($agregar_presentacion->rowCount() != 1) {
                            $alerta = [
                                "Alerta" => "simple",
                                "Tipo" => "error",
                                "Titulo" => "Ocurrió un error inesperado",
                                "Texto" => "Las nuevas presentaciones no se pudieron registrar correctamente."
                            ];
                            echo json_encode($alerta);
                            exit();
                        }
                    }
                } else {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Las presentaciones no se pudieron actualizar correctamente, actualice la página."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                
                $alerta = [
                    "Alerta" => "recargar",
                    "Tipo" => "success",
                    "Titulo" => "Datos actualizados",
                    "Texto" => "Los datos de la medicina han sido actualizados con éxito."
                ]; 
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido actualizar los datos de la medicina, intente de nuevo."
                ]; 
            }
            echo json_encode($alerta);
        }/*Fin controlador*/

        public function encryption($string){
            $string = mainModel::limpiar_cadena($string);
            $encry = mainModel::encryption($string);
            return $encry;
        }
    }
?>