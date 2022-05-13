<?php
    session_start(['name' => 'Auth']);
    require_once "../config/app.php";

    if (isset($_POST['busqueda_inicial']) || isset($_POST['eliminar_busqueda']) || isset($_POST['fecha_inicio'])) {
        $data_url = [
            "usuario" => "user-search",
            "cliente" => "client-search",
            "curso" => "course-search",
            "item" => "item-search",
            "medicina" => "medicine-search",
            "proveedor" => "provider-search",
            "venta" => "reservation-search",
            "servicio" => "service-search"
        ];
        if (isset($_POST['modulo'])) {
            $modulo = $_POST['modulo'];
            if (!isset($data_url[$modulo])) {
                $alerta = [
                    "Alerta" => "simple",
                    "Tipo" => "error",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "No podemos continuar con la búsqueda debido a un error."
                ];
                echo json_encode($alerta);
                exit();
            }
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Tipo" => "error",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "No podemos continuar con la busqueda por un erro de configuración."
            ];
            echo json_encode($alerta);
            exit();
        }
        
        if ($modulo == "venta") {
            $fecha_inicio = "fecha_inicio_".$modulo;
            $fecha_final = "fecha_final_".$modulo;

            /** Iniciar busqueda */
            if (isset($_POST['fecha_inicio']) || isset($_POST['fecha_final'])) {
                if ($_POST['fecha_inicio'] == "" || $_POST['fecha_final'] == "") {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrio un error inesperado",
                        "Texto" => "Por favor introduzca una fecha de inicio y fecha final."
                    ];
                    echo json_encode($alerta);
                    exit();
                }

                $_SESSION[$fecha_inicio] = $_POST['fecha_inicio'];
                $_SESSION[$fecha_final] = $_POST['fecha_final'];
            }

            /** Eliminar busqueda */
            if (isset($_POST['eliminar_busqueda'])) {
                unset($_SESSION[$fecha_inicio]);
                unset($_SESSION[$fecha_final]);
            }
        } else {
            $name_var = "busqueda_" . $modulo;

            /** Iniciar busqueda */
            if (isset($_POST['busqueda_inicial'])) {
                if ($_POST['busqueda_inicial'] == "") {
                    $alerta = [
                        "Alerta" => "simple",
                        "Tipo" => "error",
                        "Titulo" => "Ocurrio un error inesperado",
                        "Texto" => "Introduzca un termino de busqueda correcto."
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                $_SESSION[$name_var] = $_POST['busqueda_inicial'];
            }

            /** Eliminar busqueda */
            if (isset($_POST['eliminar_busqueda'])) {
                unset($_SESSION[$name_var]);
            }
        }
        
        //redireccionar
        $url = $data_url[$modulo];
        $alerta = [
            "Alerta" => "redireccionar",
            "URL" => SERVERURL . $url . "/"
        ];
        echo json_encode($alerta);
    } else {
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
    
?>