<?php
    require_once "mainModel.php";

    class servicioModelo extends mainModel{

        /*-----------Modelo para agregar un servicio----------*/
        protected static function agregar_servicio_modelo($datos){
            $sql = mainModel::conectar()->prepare("INSERT INTO servicios(Codigo, Nombre, Detalle, Precio, Estado, EstadoBD) VALUES(:Codigo, :Nombre, :Detalle, :Precio, :Estado, :EstadoBD)");

            $sql->bindParam(":Codigo", $datos['Codigo']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Detalle", $datos['Detalle']);
            $sql->bindParam(":Precio", $datos['Precio']);
            $sql->bindParam(":Estado", $datos['Estado']);
            $sql->bindParam(":EstadoBD", $datos['EstadoBD']);

            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para eliminar un servicio----------*/
        protected static function eliminar_servicio_modelo($id){
            //$sql = mainModel::conectar()->prepare("DELETE FROM servicios WHERE IdServicios = :Id");
            $sql = mainModel::conectar()->prepare("UPDATE servicios SET EstadoBD = '0' WHERE IdServicios = :Id");
            $sql->bindParam(":Id", $id);
            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para datos del servicio----------*/
        protected static function datos_servicio_modelo($tipo, $id){
            if ($tipo == "Unico") {
                $sql = mainModel::conectar()->prepare("SELECT * FROM servicios WHERE IdServicios = :Id AND EstadoBD = '1'");
                $sql->bindParam(":Id", $id);
            } else if($tipo == "Conteo") {
                $sql = mainModel::conectar()->prepare("SELECT IdServicios FROM servicios WHERE EstadoBD = '1'");
            }
            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para actualizar un servicio----------*/
        protected static function actualizar_servicio_modelo($datos){
            $sql = mainModel::conectar()->prepare("UPDATE servicios SET Codigo=:Codigo, Nombre=:Nombre, Detalle=:Detalle, Precio=:Precio, Estado=:Estado WHERE IdServicios=:ID");
            $sql->bindParam(":Codigo", $datos['Codigo']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Detalle", $datos['Detalle']);
            $sql->bindParam(":Precio", $datos['Precio']);
            $sql->bindParam(":Estado", $datos['Estado']);
            $sql->bindParam(":ID", $datos['ID']);
            $sql->execute();
            return $sql;
        }
    }
?>