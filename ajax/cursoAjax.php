<?php
    $peticionAjax = true;
    require_once "../config/app.php";

    if (isset($_POST['curso_codigo_reg']) || isset($_POST['curso_id_del']) || isset($_POST['curso_id_up'])) {
        /*-----------Instancia al controlador----------*/ 
        require_once "../controladores/cursoControlador.php";
        $ins_curso = new cursoControlador();

        /*-----------Agregar un curso----------*/
        if (isset($_POST['curso_codigo_reg'])) {
            echo $ins_curso->agregar_curso_controlador();
        }

        /*-----------Eliminar un curso----------*/
        if (isset($_POST['curso_id_del'])) {
            echo $ins_curso->eliminar_curso_controlador();
        }

        /*-----------Actualizar un curso----------*/
        if (isset($_POST['curso_id_up'])) {
            echo $ins_curso->actualizar_curso_controlador();
        }
    } else {
        session_start(['name' => 'Auth']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }
?>