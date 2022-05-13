<?php
    require_once "mainModel.php";

    class loteModelo extends mainModel{

        /*-----------Modelo para agregar un lote----------*/
        protected static function agregar_lote_modelo($datos){
            $sql = mainModel::conectar()->prepare("INSERT INTO lotes(Stock, FechaVencimiento, PrecioCompra, Estado, IdPresentaciones) VALUES(:Stock, :FechaVencimiento, :PrecioCompra, :Estado, :IdPresentaciones)");

            $sql->bindParam(":Stock", $datos['Stock']);
            $sql->bindParam(":FechaVencimiento", $datos['FechaVencimiento']);
            $sql->bindParam(":PrecioCompra", $datos['PrecioCompra']);
            $sql->bindParam(":Estado", $datos['Estado']);
            $sql->bindParam(":IdPresentaciones", $datos['IdPresentaciones']);

            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para datos de presentaciones disponibles de una medicina----------*/
        protected static function presentaciones_lote_modelo($id){
            $sql = mainModel::conectar()->prepare("SELECT * FROM presentaciones WHERE IdMedicinas = :Id ORDER BY Nombre ASC");
            $sql->bindParam(":Id", $id);

            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para datos lotes de una medicina----------*/
        protected static function lista_lote_modelo($id){
            $sql = mainModel::conectar()->prepare("SELECT lotes.*, presentaciones.* FROM lotes JOIN presentaciones ON lotes.IdPresentaciones = presentaciones.IdPresentaciones WHERE presentaciones.IdMedicinas = :Id AND lotes.Estado = '1' ORDER BY presentaciones.Nombre, lotes.FechaVencimiento ASC");
            $sql->bindParam(":Id", $id);

            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para eliminar un lote----------*/
        protected static function eliminar_lote_modelo($id){
            //$sql = mainModel::conectar()->prepare("DELETE FROM lotes WHERE IdLotes = :Id");
            $sql = mainModel::conectar()->prepare("UPDATE lotes SET Estado = '0' WHERE IdLotes = :Id");
            $sql->bindParam(":Id", $id);
            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para datos del lote----------*/
        protected static function datos_lote_modelo($tipo, $id){
            if ($tipo == "Unico") {
                $sql = mainModel::conectar()->prepare("SELECT * FROM lotes WHERE IdLotes = :Id");
                $sql->bindParam(":Id", $id);
            } else if($tipo == "Conteo") {
                $sql = mainModel::conectar()->prepare("SELECT IdLotes FROM lotes WHERE Estado = '1'");
            } else {
                $sql = mainModel::conectar()->prepare("SELECT * FROM lotes WHERE IdPresentaciones = :Id");
                $sql->bindParam(":Id", $id);
            }
            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para actualizar un artículo----------*/
        protected static function actualizar_lote_modelo($datos){
            $sql = mainModel::conectar()->prepare("UPDATE lotes SET Stock=:Stock, FechaVencimiento=:FechaVencimiento, PrecioCompra=:PrecioCompra, IdPresentaciones=:IdPresentaciones WHERE IdLotes=:ID");
            
            $sql->bindParam(":Stock", $datos['Stock']);
            $sql->bindParam(":FechaVencimiento", $datos['FechaVencimiento']);
            $sql->bindParam(":PrecioCompra", $datos['PrecioCompra']);
            $sql->bindParam(":IdPresentaciones", $datos['IdPresentaciones']);
            $sql->bindParam(":ID", $datos['ID']);
            $sql->execute();
            return $sql;
        }
    }
?>