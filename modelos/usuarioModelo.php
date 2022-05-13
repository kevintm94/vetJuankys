<?php
    require_once "mainModel.php";

    class usuarioModelo extends mainModel{

        /*-----------Modelo para agregar empleado y usuario----------*/
        protected static function agregar_usuario_modelo($datos){
            $sql = mainModel::conectar()->prepare("INSERT INTO empleados(CI, Nombres, Apellidos, Telefono, Direccion, Usuario, Clave, Estado, IdRoles) VALUES(:CI, :Nombres, :Apellidos, :Telefono, :Direccion, :Usuario, :Clave, :Estado, :IdRoles)");

            $sql->bindParam(":CI", $datos['CI']);
            $sql->bindParam(":Nombres", $datos['Nombres']);
            $sql->bindParam(":Apellidos", $datos['Apellidos']);
            $sql->bindParam(":Telefono", $datos['Telefono']);
            $sql->bindParam(":Direccion", $datos['Direccion']);

            $sql->bindParam(":Usuario", $datos['Usuario']);
            $sql->bindParam(":Clave", $datos['Clave']);
            $sql->bindParam(":Estado", $datos['Estado']);
            
            $sql->bindParam(":IdRoles", $datos['IdRoles']);

            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para eliminar empleado y usuario----------*/
        protected static function eliminar_usuario_modelo($id){
            //$sql = mainModel::conectar()->prepare("DELETE FROM empleados WHERE IdEmpleados = :Id");
            $sql = mainModel::conectar()->prepare("UPDATE empleados SET Estado = '0' WHERE IdEmpleados = :Id");
            $sql->bindParam(":Id", $id);
            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para datos del empleado y usuario----------*/
        protected static function datos_usuario_modelo($tipo, $id){
            if ($tipo == "Unico") {
                $sql = mainModel::conectar()->prepare("SELECT * FROM empleados WHERE IdEmpleados = :Id AND Estado = '1'");
                $sql->bindParam(":Id", $id);
            } else if($tipo == "Conteo") {
                $sql = mainModel::conectar()->prepare("SELECT IdEmpleados FROM empleados WHERE IdEmpleados != '1' AND Estado = '1'");
            }
            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para actuaizar empleado y usuario----------*/
        protected static function actualizar_usuario_modelo($datos){
            $sql = mainModel::conectar()->prepare("UPDATE empleados SET CI=:CI, Nombres=:Nombres, Apellidos=:Apellidos, Telefono=:Telefono, Direccion=:Direccion, Usuario=:Usuario, Clave=:Clave, Estado=:Estado, IdRoles=:IdRoles WHERE IdEmpleados=:ID");
            $sql->bindParam(":CI", $datos['CI']);
            $sql->bindParam(":Nombres", $datos['Nombres']);
            $sql->bindParam(":Apellidos", $datos['Apellidos']);
            $sql->bindParam(":Telefono", $datos['Telefono']);
            $sql->bindParam(":Direccion", $datos['Direccion']);
            $sql->bindParam(":Usuario", $datos['Usuario']);
            $sql->bindParam(":Clave", $datos['Clave']);
            $sql->bindParam(":Estado", $datos['Estado']);
            $sql->bindParam(":IdRoles", $datos['IdRoles']);
            $sql->bindParam(":ID", $datos['ID']);
            $sql->execute();
            return $sql;
        }
    }
?>