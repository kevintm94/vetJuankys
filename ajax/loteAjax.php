<?php
    $peticionAjax = true;
    require_once "../config/app.php";

    if (isset($_POST['lote_presentacion_reg']) || isset($_POST['lote_id_del']) || isset($_POST['lote_id_up'])) {
        /*-----------Instancia al controlador----------*/ 
        require_once "../controladores/loteControlador.php";
        $ins_lote = new loteControlador();

        /*-----------Agregar un lote----------*/
        if (isset($_POST['lote_presentacion_reg'])) {
            echo $ins_lote->agregar_lote_controlador();
        }

        /*-----------Eliminar un lote----------*/
        if (isset($_POST['lote_id_del'])) {
            echo $ins_lote->eliminar_lote_controlador();
        }

        /*-----------Actualizar un lote----------*/
        if (isset($_POST['lote_id_up'])) {
            echo $ins_lote->actualizar_lote_controlador();
        }
    } else {
        session_start(['name' => 'Auth']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
?>