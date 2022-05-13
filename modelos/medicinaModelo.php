<?php
    require_once "mainModel.php";

    class medicinaModelo extends mainModel{

        /*-----------Modelo para agregar una medicina----------*/
        protected static function agregar_medicina_modelo($datos){
            $sql = mainModel::conectar()->prepare("INSERT INTO medicinas(Codigo, Nombre, Detalle, Laboratorio, Estado, IdProveedores) VALUES(:Codigo, :Nombre, :Detalle, :Laboratorio, :Estado, :IdProveedores)");

            $sql->bindParam(":Codigo", $datos['Codigo']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Detalle", $datos['Detalle']);
            $sql->bindParam(":Laboratorio", $datos['Laboratorio']);
            $sql->bindParam(":Estado", $datos['Estado']);
            $sql->bindParam(":IdProveedores", $datos['IdProveedores']);

            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para datos de un proveedor----------*/
        protected static function proveedor_medicina_modelo($id){
            $sql = mainModel::conectar()->prepare("SELECT * FROM proveedores WHERE IdProveedores = :Id AND Estado = '1'");
            $sql->bindParam(":Id", $id);

            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para eliminar una medicina----------*/
        protected static function eliminar_medicina_modelo($id){
            //$sql = mainModel::conectar()->prepare("DELETE FROM medicinas WHERE IdMedicinas = :Id");
            $sql = mainModel::conectar()->prepare("UPDATE medicinas SET Estado = '0' WHERE IdMedicinas = :Id");
            $sql->bindParam(":Id", $id);
            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para datos de la medicina----------*/
        protected static function datos_medicina_modelo($tipo, $id){
            if ($tipo == "Unico") {
                $sql = mainModel::conectar()->prepare("SELECT * FROM medicinas WHERE IdMedicinas = :Id AND Estado = '1'");
                $sql->bindParam(":Id", $id);
            } else if($tipo == "Conteo") {
                $sql = mainModel::conectar()->prepare("SELECT IdMedicinas FROM medicinas WHERE Estado = '1'");
            }
            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para stock de la medicina----------*/
        protected static function stock_medicina_modelo($id){
            $sql = mainModel::conectar()->prepare("SELECT SUM(lotes.Stock) AS Suma FROM lotes JOIN presentaciones ON lotes.IdPresentaciones = presentaciones.IdPresentaciones WHERE presentaciones.IdMedicinas = :Id AND lotes.Estado = '1'");
            $sql->bindParam(":Id", $id);
            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para actualizar una medicina----------*/
        protected static function actualizar_medicina_modelo($datos){
            $sql = mainModel::conectar()->prepare("UPDATE medicinas SET Codigo=:Codigo, Nombre=:Nombre, Detalle=:Detalle, Laboratorio=:Laboratorio, IdProveedores=:IdProveedores WHERE IdMedicinas=:ID");
            $sql->bindParam(":Codigo", $datos['Codigo']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Detalle", $datos['Detalle']);
            $sql->bindParam(":Laboratorio", $datos['Laboratorio']);
            $sql->bindParam(":IdProveedores", $datos['IdProveedores']);
            $sql->bindParam(":ID", $datos['ID']);
            $sql->execute();
            return $sql;
        }
    }
?>