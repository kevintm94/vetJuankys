<?php
    require_once "mainModel.php";

    class loginModelo extends mainModel{

        /*-----------Modelo para iniciar sesión----------*/
        protected static function iniciar_sesion_modelo($datos){
            $sql = mainModel::conectar()->prepare("SELECT * FROM empleados WHERE Usuario = :Usuario AND Clave = :Clave AND Estado = '1'");
            $sql->bindParam(":Usuario", $datos['Usuario']);
            $sql->bindParam(":Clave", $datos['Clave']);
            $sql->execute();
            return $sql;
        }
    }
?>