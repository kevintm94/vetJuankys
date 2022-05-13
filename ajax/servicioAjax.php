<?php
    $peticionAjax = true;
    require_once "../config/app.php";

    if (isset($_POST['servicio_codigo_reg']) || isset($_POST['servicio_id_del']) || isset($_POST['servicio_id_up'])) {
        /*-----------Instancia al controlador----------*/ 
        require_once "../controladores/servicioControlador.php";
        $ins_servicio = new servicioControlador();

        /*-----------Agregar un servicio----------*/
        if (isset($_POST['servicio_codigo_reg'])) {
            echo $ins_servicio->agregar_servicio_controlador();
        }

        /*-----------Eliminar un servicio----------*/
        if (isset($_POST['servicio_id_del'])) {
            echo $ins_servicio->eliminar_servicio_controlador();
        }

        /*-----------Actualizar un servicio----------*/
        if (isset($_POST['servicio_id_up'])) {
            echo $ins_servicio->actualizar_servicio_controlador();
        }
    } else {
        session_start(['name' => 'Auth']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
?>