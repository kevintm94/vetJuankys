<?php
    $peticionAjax = true;
    require_once "../config/app.php";

    if (isset($_POST['id_cliente_seleccionado']) || isset($_POST['venta_id_del']) || isset($_POST['medicina_id_up'])) {
        /*-----------Instancia al controlador----------*/ 
        require_once "../controladores/ventaControlador.php";
        $ins_venta = new ventaControlador();

        /*-----------Agregar una venta----------*/
        if (isset($_POST['id_cliente_seleccionado'])) {
            echo $ins_venta->agregar_venta_controlador();
        }

        /*-----------Eliminar una venta----------*/
        if (isset($_POST['venta_id_del'])) {
            echo $ins_venta->eliminar_venta_controlador();
        }

        /*-----------Eliminar una presentacion----------*/
        if (isset($_POST['presentacion_id_del'])) {
            echo $ins_venta->eliminar_presentacion_venta_controlador();
        }

        /*-----------Actualizar una venta----------*/
        if (isset($_POST['venta_id_up'])) {
            echo $ins_venta->actualizar_venta_controlador();
        }
    } else {
        session_start(['name' => 'Auth']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
?>