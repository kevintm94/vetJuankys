<?php
    if ($peticionAjax) {
        require_once "../modelos/loginModelo.php";
    } else {
        require_once "./modelos/loginModelo.php";
    }
    
    class loginControlador extends loginModelo{
        /*-----------Controlador para iniciar sesión----------*/
        public function iniciar_sesion_controlador(){
            $usuario = mainModel::limpiar_cadena($_POST['usuario_log']);
            $clave = mainModel::limpiar_cadena($_POST['clave_log']);

            /*-------------Comprobar campos vacios-------------*/
            if ($usuario == "" || $clave == "") {
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
            if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}", $usuario)){
                echo '
                    <script>
                        Swal.fire({
                            type: "error",
                            title: "Ocurrio un error inesperador",
                            text: "El usuario no coincide con el formato requerido.",
                            confirmButtonText: "Aceptar"
                        });
                    </script>
                ';
                exit();
            }
            if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave)){
                echo '
                    <script>
                        Swal.fire({
                            type: "error",
                            title: "Ocurrio un error inesperador",
                            text: "La clave no coincide con el formato requerido.",
                            confirmButtonText: "Aceptar"
                        });
                    </script>
                ';
                exit();
            }
            
            $clave = mainModel::encryption($clave);
            $datos_login = [
                "Usuario" => $usuario,
                "Clave" => $clave
            ];
            
            $datos_cuenta = loginModelo::iniciar_sesion_modelo($datos_login);
            if ($datos_cuenta->rowCount() == 1) {
                $row = $datos_cuenta->fetch();
                session_start(['name' => 'Auth']);
                $_SESSION['id_auth'] = $row['IdEmpleados'];
                $_SESSION['nombres_auth'] = $row['Nombres'];
                $_SESSION['apellidos_auth'] = $row['Apellidos'];
                $_SESSION['usuario_auth'] = $row['Usuario'];
                $_SESSION['rol_auth'] = $row['IdRoles'];
                $_SESSION['token_auth'] = md5(uniqid(mt_rand(), true));
                $_SESSION['modal'] = true;

                return header("Location: ".SERVERURL."home/");
            } else {
                echo '
                    <script>
                        Swal.fire({
                            type: "error",
                            title: "Ocurrio un error inesperador",
                            text: "El usuario y/o clave son incorrectos.",
                            confirmButtonText: "Aceptar"
                        });
                    </script>
                ';
            }
            
        }/* Fin controlador */

        /*-----------Controlador para forzar cierre sesión----------*/
        public function forzar_cierre_sesion_controlador(){
            session_unset();
            session_destroy();
            if (headers_sent()) {
                return "<script> window.location.href = '".SERVERURL."login/'; </script>";
            } else {
                return header("Location: ".SERVERURL."login/");
            }
            
        }/* Fin controlador */

        /*-----------Controlador para cerrar sesión----------*/
        public function cerrar_sesion_controlador(){
            session_start(['name' => 'Auth']);
            $token = mainModel::decryption($_POST['token']);
            $usuario = mainModel::decryption($_POST['usuario']);

            if ($token == $_SESSION['token_auth'] && $usuario == $_SESSION['usuario_auth']) {
                session_unset();
                session_destroy();
                $alerta = [
                    "Alerta" => "redireccionar",
                    "URL" => SERVERURL."login/"
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "No se puedo cerrar la sesión del sistema."
                ];
            }
            echo json_encode($alerta);
        }/* Fin controlador */
    }
?>