<?php
    require_once "mainModel.php";

    class proveedorModelo extends mainModel{
        
        /*-----------Modelo para agregar proveedor----------*/
        protected static function agregar_proveedor_modelo($datos){
            $sql = mainModel::conectar()->prepare("INSERT INTO proveedores(Nombre, Telefono, Email, Direccion, Estado) VALUES(:Nombre, :Telefono, :Email, :Direccion, :Estado)");
            
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Telefono", $datos['Telefono']);
            $sql->bindParam(":Email", $datos['Email']);
            $sql->bindParam(":Direccion", $datos['Direccion']);
            $sql->bindParam(":Estado", $datos['Estado']);
            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para eliminar un proveedor----------*/
        protected static function eliminar_proveedor_modelo($id){
            //$sql = mainModel::conectar()->prepare("DELETE FROM proveedores WHERE IdProveedores = :Id");
            $sql = mainModel::conectar()->prepare("UPDATE proveedores SET Estado = '0' WHERE IdProveedores = :Id");
            $sql->bindParam(":Id", $id);
            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para datos del proveedor----------*/
        protected static function datos_proveedor_modelo($tipo, $id){
            if ($tipo == "Unico") {
                $sql = mainModel::conectar()->prepare("SELECT * FROM proveedores WHERE IdProveedores = :Id AND Estado = '1'");
                $sql->bindParam(":Id", $id);
            } else if($tipo == "Conteo") {
                $sql = mainModel::conectar()->prepare("SELECT IdProveedores FROM proveedores WHERE Estado = '1'");
            }
            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para actualizar un proveedor----------*/
        protected static function actualizar_proveedor_modelo($datos){
            $sql = mainModel::conectar()->prepare("UPDATE proveedores SET Nombre=:Nombre, Telefono=:Telefono, Email=:Email, Direccion=:Direccion WHERE IdProveedores=:ID");
            
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Telefono", $datos['Telefono']);
            $sql->bindParam(":Email", $datos['Email']);
            $sql->bindParam(":Direccion", $datos['Direccion']);
            $sql->bindParam(":ID", $datos['ID']);
            $sql->execute();
            return $sql;
        }
    }
?>