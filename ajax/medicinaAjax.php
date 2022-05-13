<?php
    $peticionAjax = true;
    require_once "../config/app.php";

    if (isset($_POST['medicina_codigo_reg']) || isset($_POST['medicina_id_del']) || isset($_POST['medicina_id_up']) || isset($_POST['presentacion_id_del'])) {
        /*-----------Instancia al controlador----------*/ 
        require_once "../controladores/medicinaControlador.php";
        $ins_medicina = new medicinaControlador();

        /*-----------Agregar una medicina----------*/
        if (isset($_POST['medicina_codigo_reg'])) {
            echo $ins_medicina->agregar_medicina_controlador();
        }

        /*-----------Eliminar una medicina----------*/
        if (isset($_POST['medicina_id_del'])) {
            echo $ins_medicina->eliminar_medicina_controlador();
        }

        /*-----------Eliminar una presentacion----------*/
        if (isset($_POST['presentacion_id_del'])) {
            echo $ins_medicina->eliminar_presentacion_medicina_controlador();
        }

        /*-----------Actualizar una medicina----------*/
        if (isset($_POST['medicina_id_up'])) {
            echo $ins_medicina->actualizar_medicina_controlador();
        }
    } else {
        session_start(['name' => 'Auth']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
?>