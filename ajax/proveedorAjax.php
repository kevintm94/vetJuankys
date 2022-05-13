<?php
    $peticionAjax = true;
    require_once "../config/app.php";

    if (isset($_POST['proveedor_nombre_reg']) || isset($_POST['proveedor_id_del']) || isset($_POST['proveedor_id_up'])) {
        
        /*-----------Instancia al controlador----------*/
        require_once "../controladores/proveedorControlador.php";
        $ins_proveedor = new proveedorControlador();

        /*-----------Agregar un proveedor----------*/
        if (isset($_POST['proveedor_nombre_reg']) && isset($_POST['proveedor_telefono_reg'])) {
            echo $ins_proveedor->agregar_proveedor_controlador();
        }

        /*-----------Eliminar un proveedor----------*/
        if (isset($_POST['proveedor_id_del'])) {
            echo $ins_proveedor->eliminar_proveedor_controlador();
        }

        /*-----------Actualizar un proveedor----------*/
        if (isset($_POST['proveedor_id_up'])) {
            echo $ins_proveedor->actualizar_proveedor_controlador();
        }
    } else {
        session_start(['name' => 'Auth']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
?>