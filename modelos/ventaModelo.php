<?php
    require_once "mainModel.php";

    class ventaModelo extends mainModel{

        /*-----------Modelo para agregar un venta----------*/
        protected static function agregar_venta_modelo($datos){
            $sql = mainModel::conectar()->prepare("INSERT INTO ventas(TotalPagar, IdClientes, IdEmpleados, Fecha, Estado) VALUES(:TotalPagar, :IdClientes, :IdEmpleados, :Fecha, :Estado)");

            $sql->bindParam(":TotalPagar", $datos['TotalPagar']);
            $sql->bindParam(":IdClientes", $datos['IdClientes']);
            $sql->bindParam(":IdEmpleados", $datos['IdEmpleados']);
            $sql->bindParam(":Fecha", $datos['Fecha']);
            $sql->bindParam(":Estado", $datos['Estado']);

            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para agregar un detalle de venta----------*/
        protected static function agregar_detalle_venta_articulo_modelo($datos){
            //$sql = mainModel::conectar()->prepare("INSERT INTO detalleventas(IdArticulos, IdMedicinas, IdPresentaciones, IdLotes, IdServicios, IdVentas, Cantidad) VALUES(:IdArticulos, :IdMedicinas, :IdPresentaciones, :IdLotes, :IdServicios, :IdVentas, :Cantidad)");
            $sql = mainModel::conectar()->prepare("INSERT INTO detalleventas(IdArticulos, IdVentas, Cantidad) VALUES(:IdArticulos, :IdVentas, :Cantidad)");

            $sql->bindParam(":IdArticulos", $datos['IdArticulos']);
            //$sql->bindParam(":IdMedicinas", $datos['IdMedicinas']);
            //$sql->bindParam(":IdPresentaciones", $datos['IdPresentaciones']);
            //$sql->bindParam(":IdLotes", $datos['IdLotes']);
            //$sql->bindParam(":IdServicios", $datos['IdServicios']);
            $sql->bindParam(":IdVentas", $datos['IdVentas']);
            $sql->bindParam(":Cantidad", $datos['Cantidad']);

            $sql->execute();

            return $sql;
        }

        protected static function agregar_detalle_venta_medicina_modelo($datos){
            //$sql = mainModel::conectar()->prepare("INSERT INTO detalleventas(IdArticulos, IdMedicinas, IdPresentaciones, IdLotes, IdServicios, IdVentas, Cantidad) VALUES(:IdArticulos, :IdMedicinas, :IdPresentaciones, :IdLotes, :IdServicios, :IdVentas, :Cantidad)");
            $sql = mainModel::conectar()->prepare("INSERT INTO detalleventas(IdMedicinas, IdLotes, IdVentas, Cantidad) VALUES(:IdMedicinas, :IdLotes, :IdVentas, :Cantidad)");

            //$sql->bindParam(":IdArticulos", $datos['IdArticulos']);
            $sql->bindParam(":IdMedicinas", $datos['IdMedicinas']);
            //$sql->bindParam(":IdPresentaciones", $datos['IdPresentaciones']);
            $sql->bindParam(":IdLotes", $datos['IdLotes']);
            //$sql->bindParam(":IdServicios", $datos['IdServicios']);
            $sql->bindParam(":IdVentas", $datos['IdVentas']);
            $sql->bindParam(":Cantidad", $datos['Cantidad']);

            $sql->execute();

            return $sql;
        }

        protected static function agregar_detalle_venta_servicio_modelo($datos){
            //$sql = mainModel::conectar()->prepare("INSERT INTO detalleventas(IdArticulos, IdMedicinas, IdPresentaciones, IdLotes, IdServicios, IdVentas, Cantidad) VALUES(:IdArticulos, :IdMedicinas, :IdPresentaciones, :IdLotes, :IdServicios, :IdVentas, :Cantidad)");
            $sql = mainModel::conectar()->prepare("INSERT INTO detalleventas(IdServicios, IdVentas, Cantidad) VALUES(:IdServicios, :IdVentas, :Cantidad)");

            //$sql->bindParam(":IdArticulos", $datos['IdArticulos']);
            //$sql->bindParam(":IdMedicinas", $datos['IdMedicinas']);
            //$sql->bindParam(":IdPresentaciones", $datos['IdPresentaciones']);
            //$sql->bindParam(":IdLotes", $datos['IdLotes']);
            $sql->bindParam(":IdServicios", $datos['IdServicios']);
            $sql->bindParam(":IdVentas", $datos['IdVentas']);
            $sql->bindParam(":Cantidad", $datos['Cantidad']);

            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para eliminar un venta----------*/
        protected static function eliminar_venta_modelo($id){
            //$sql = mainModel::conectar()->prepare("DELETE FROM ventas WHERE IdVentas = :Id");
            $sql = mainModel::conectar()->prepare("UPDATE ventas SET Estado = '0' WHERE IdVentas = :Id");
            $sql->bindParam(":Id", $id);
            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para datos de la venta----------*/
        protected static function datos_venta_modelo($tipo, $id){
            if ($tipo == "Unico") {
                $sql = mainModel::conectar()->prepare("SELECT * FROM detalleventas JOIN ventas ON detalleventas.IdVentas = ventas.IdVentas WHERE ventas.IdVentas = :Id AND ventas.Estado = '1'");
                $sql->bindParam(":Id", $id);
            } else if($tipo == "Conteo") {
                $sql = mainModel::conectar()->prepare("SELECT IdVentas FROM ventas WHERE Estado = '1'");
            }
            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para actualizar un venta----------*/
        protected static function actualizar_venta_modelo($datos){
            $sql = mainModel::conectar()->prepare("UPDATE ventas SET TotalPagar=:TotalPagar, IdClientes=:IdClientes, IdEmpleados=:IdEmpleados WHERE IdVentas=:ID");
            $sql->bindParam(":TotalPagar", $datos['TotalPagar']);
            $sql->bindParam(":IdClientes", $datos['IdClientes']);
            $sql->bindParam(":IdEmpleados", $datos['IdEmpleados']);
            $sql->bindParam(":ID", $datos['ID']);
            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para actualizar un detalle de venta----------*/
        protected static function actualizar_detalle_venta_modelo($datos){
            $sql = mainModel::conectar()->prepare("UPDATE detalleventas SET IdArticulos=:IdArticulos, IdMedicinas=:IdMedicinas, IdServicios=:IdServicios WHERE IdDetalleVentas=:ID");
            $sql->bindParam(":IdArticulos", $datos['IdArticulos']);
            $sql->bindParam(":IdMedicinas", $datos['IdMedicinas']);
            $sql->bindParam(":IdServicios", $datos['IdServicios']);
            $sql->bindParam(":ID", $datos['ID']);
            $sql->execute();
            return $sql;
        }
    }
?>