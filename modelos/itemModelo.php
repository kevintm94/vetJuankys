<?php
    require_once "mainModel.php";

    class itemModelo extends mainModel{

        /*-----------Modelo para agregar un articulo----------*/
        protected static function agregar_item_modelo($datos){
            $sql = mainModel::conectar()->prepare("INSERT INTO articulos(Codigo, Nombre, Detalle, Fabricante, Stock, PrecioCompra, PrecioVenta, Estado, IdProveedores) VALUES(:Codigo, :Nombre, :Detalle, :Fabricante, :Stock, :PrecioCompra, :PrecioVenta, :Estado, :IdProveedores)");

            $sql->bindParam(":Codigo", $datos['Codigo']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Detalle", $datos['Detalle']);
            $sql->bindParam(":Stock", $datos['Stock']);
            $sql->bindParam(":Fabricante", $datos['Fabricante']);
            $sql->bindParam(":PrecioCompra", $datos['PrecioCompra']);
            $sql->bindParam(":PrecioVenta", $datos['PrecioVenta']);
            $sql->bindParam(":Estado", $datos['Estado']);
            $sql->bindParam(":IdProveedores", $datos['IdProveedores']);

            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para datos de un proveedor----------*/
        protected static function proveedor_item_modelo($id){
            $sql = mainModel::conectar()->prepare("SELECT * FROM proveedores WHERE IdProveedores = :Id AND Estado = '1'");
            $sql->bindParam(":Id", $id);

            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para eliminar un articulo----------*/
        protected static function eliminar_item_modelo($id){
            //$sql = mainModel::conectar()->prepare("DELETE FROM articulos WHERE IdArticulos = :Id");
            $sql = mainModel::conectar()->prepare("UPDATE articulos SET Estado = '0' WHERE IdArticulos = :Id");
            $sql->bindParam(":Id", $id);
            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para datos del artículo----------*/
        protected static function datos_item_modelo($tipo, $id){
            if ($tipo == "Unico") {
                $sql = mainModel::conectar()->prepare("SELECT * FROM articulos WHERE IdArticulos = :Id AND Estado = '1'");
                $sql->bindParam(":Id", $id);
            } else if($tipo == "Conteo") {
                $sql = mainModel::conectar()->prepare("SELECT IdArticulos FROM articulos WHERE Estado = '1'");
            } else if($tipo == "Todos") {
                $sql = mainModel::conectar()->prepare("SELECT * FROM articulos WHERE Estado = '1'");
            }
            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para actualizar un artículo----------*/
        protected static function actualizar_item_modelo($datos){
            $sql = mainModel::conectar()->prepare("UPDATE articulos SET Codigo=:Codigo, Nombre=:Nombre, Detalle=:Detalle, Fabricante=:Fabricante, Stock=:Stock, PrecioCompra=:PrecioCompra, PrecioVenta=:PrecioVenta, IdProveedores=:IdProveedores WHERE IdArticulos=:ID");
            $sql->bindParam(":Codigo", $datos['Codigo']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Detalle", $datos['Detalle']);
            $sql->bindParam(":Fabricante", $datos['Fabricante']);
            $sql->bindParam(":Stock", $datos['Stock']);
            $sql->bindParam(":PrecioCompra", $datos['PrecioCompra']);
            $sql->bindParam(":PrecioVenta", $datos['PrecioVenta']);
            $sql->bindParam(":IdProveedores", $datos['IdProveedores']);
            $sql->bindParam(":ID", $datos['ID']);
            $sql->execute();
            return $sql;
        }
    }
?>