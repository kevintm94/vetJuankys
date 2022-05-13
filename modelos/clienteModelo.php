<?php
    require_once "mainModel.php";

    class clienteModelo extends mainModel{
        
        /*-----------Modelo para agregar cliente----------*/
        protected static function agregar_cliente_modelo($datos){
            $sql = mainModel::conectar()->prepare("INSERT INTO clientes(CI, Nombres, Apellidos, Telefono, Direccion, Estado) VALUES(:CI, :Nombres, :Apellidos, :Telefono, :Direccion, :Estado)");
            
            $sql->bindParam(":CI", $datos['CI']);
            $sql->bindParam(":Nombres", $datos['Nombres']);
            $sql->bindParam(":Apellidos", $datos['Apellidos']);
            $sql->bindParam(":Telefono", $datos['Telefono']);
            $sql->bindParam(":Direccion", $datos['Direccion']);
            $sql->bindParam(":Estado", $datos['Estado']);
            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para eliminar un cliente----------*/
        protected static function eliminar_cliente_modelo($id){
            //$sql = mainModel::conectar()->prepare("DELETE FROM clientes WHERE IdClientes = :Id");
            $sql = mainModel::conectar()->prepare("UPDATE clientes SET Estado = '0' WHERE IdClientes = :Id");
            $sql->bindParam(":Id", $id);
            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para datos del cliente----------*/
        protected static function datos_cliente_modelo($tipo, $id){
            if ($tipo == "Unico") {
                $sql = mainModel::conectar()->prepare("SELECT * FROM clientes WHERE IdClientes = :Id AND Estado = '1'");
                $sql->bindParam(":Id", $id);
            } else if($tipo == "Conteo") {
                $sql = mainModel::conectar()->prepare("SELECT IdClientes FROM clientes WHERE Estado = '1'");
            }
            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para actualizar un cliente----------*/
        protected static function actualizar_cliente_modelo($datos){
            $sql = mainModel::conectar()->prepare("UPDATE clientes SET CI=:CI, Nombres=:Nombres, Apellidos=:Apellidos, Telefono=:Telefono, Direccion=:Direccion WHERE IdClientes=:ID");
            $sql->bindParam(":CI", $datos['CI']);
            $sql->bindParam(":Nombres", $datos['Nombres']);
            $sql->bindParam(":Apellidos", $datos['Apellidos']);
            $sql->bindParam(":Telefono", $datos['Telefono']);
            $sql->bindParam(":Direccion", $datos['Direccion']);
            $sql->bindParam(":ID", $datos['ID']);
            $sql->execute();
            return $sql;
        }
    }
?>