<?php
    require_once "mainModel.php";

    class presentacionModelo extends mainModel{

        /*-----------Modelo para agregar una presentacion----------*/
        public static function agregar_presentacion_modelo($datos){
            $sql = mainModel::conectar()->prepare("INSERT INTO presentaciones(Nombre, PrecioVenta, IdMedicinas) VALUES(:Nombre, :PrecioVenta, :IdMedicinas)");

            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":PrecioVenta", $datos['PrecioVenta']);
            $sql->bindParam(":IdMedicinas", $datos['IdMedicinas']);

            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para eliminar una presentacion----------*/
        public static function eliminar_presentacion_modelo($id){
            $sql = mainModel::conectar()->prepare("DELETE FROM presentaciones WHERE IdPresentaciones = :Id");
            $sql->bindParam(":Id", $id);
            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para datos de la presentacion----------*/
        public static function datos_presentacion_modelo($tipo, $id){
            if ($tipo == "Unico") {
                $sql = mainModel::conectar()->prepare("SELECT * FROM presentaciones WHERE IdMedicinas = :Id");
                $sql->bindParam(":Id", $id);
            } else if($tipo == "Uno") {
                $sql = mainModel::conectar()->prepare("SELECT * FROM presentaciones WHERE IdPresentaciones = :Id");
                $sql->bindParam(":Id", $id);
            } else if($tipo == "Completo") {
                $sql = mainModel::conectar()->prepare("SELECT presentaciones.* FROM presentaciones JOIN medicinas ON medicinas.IdMedicinas = presentaciones.IdMedicinas WHERE medicinas.IdMedicinas = :Id");
                $sql->bindParam(":Id", $id);
            } else{
                $sql = mainModel::conectar()->prepare("SELECT IdPresentaciones FROM presentaciones");
            }
            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para actualizar una presentacion----------*/
        public static function actualizar_presentacion_modelo($datos){
            $sql = mainModel::conectar()->prepare("UPDATE presentaciones SET Nombre=:Nombre, PrecioVenta=:PrecioVenta, IdMedicinas=:IdMedicinas WHERE IdPresentaciones=:ID");
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":PrecioVenta", $datos['PrecioVenta']);
            $sql->bindParam(":IdMedicinas", $datos['IdMedicinas']);
            $sql->bindParam(":ID", $datos['ID']);
            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para stock de la presentacion----------*/
        public static function stock_presentacion_modelo($id){
            $sql = mainModel::conectar()->prepare("SELECT SUM(lotes.Stock) AS Suma FROM lotes JOIN presentaciones ON lotes.IdPresentaciones = presentaciones.IdPresentaciones WHERE presentaciones.IdPresentaciones = :Id AND lotes.Estado = '1'");
            $sql->bindParam(":Id", $id);
            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para datos de la presentacion----------*/
        public static function datos_presentacion_medicina_modelo(){
            $sql = mainModel::conectar()->prepare("SELECT IdMedicinas, Nombre FROM medicinas");
            $sql->execute();
            return $sql;
        }

    }
?>