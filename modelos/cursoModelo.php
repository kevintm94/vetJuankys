<?php
    require_once "mainModel.php";

    class cursoModelo extends mainModel{

        /*-----------Modelo para agregar un curso----------*/
        protected static function agregar_curso_modelo($datos){
            $sql = mainModel::conectar()->prepare("INSERT INTO cursos(Codigo, Nombre, Detalle, Sesiones, Precio, Estado, EstadoBD) VALUES(:Codigo, :Nombre, :Detalle, :Sesiones, :Precio, :Estado, :EstadoBD)");

            $sql->bindParam(":Codigo", $datos['Codigo']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Detalle", $datos['Detalle']);
            $sql->bindParam(":Sesiones", $datos['Sesiones']);
            $sql->bindParam(":Precio", $datos['Precio']);
            $sql->bindParam(":Estado", $datos['Estado']);
            $sql->bindParam(":EstadoBD", $datos['EstadoBD']);

            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para eliminar un curso----------*/
        protected static function eliminar_curso_modelo($id){
            //$sql = mainModel::conectar()->prepare("DELETE FROM cursos WHERE IdCursos = :Id");
            $sql = mainModel::conectar()->prepare("UPDATE cursos SET EstadoBD = '0' WHERE IdCursos = :Id");
            $sql->bindParam(":Id", $id);
            $sql->execute();

            return $sql;
        }

        /*-----------Modelo para datos del curso----------*/
        protected static function datos_curso_modelo($tipo, $id){
            if ($tipo == "Unico") {
                $sql = mainModel::conectar()->prepare("SELECT * FROM cursos WHERE IdCursos = :Id AND EstadoBD = '1'");
                $sql->bindParam(":Id", $id);
            } else if($tipo == "Conteo") {
                $sql = mainModel::conectar()->prepare("SELECT IdCursos FROM cursos WHERE EstadoBD = '1'");
            }
            $sql->execute();
            return $sql;
        }

        /*-----------Modelo para actualizar un curso----------*/
        protected static function actualizar_curso_modelo($datos){
            $sql = mainModel::conectar()->prepare("UPDATE cursos SET Codigo=:Codigo, Nombre=:Nombre, Detalle=:Detalle, Sesiones=:Sesiones, Precio=:Precio, Estado=:Estado WHERE IdCursos=:ID");
            $sql->bindParam(":Codigo", $datos['Codigo']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Detalle", $datos['Detalle']);
            $sql->bindParam(":Sesiones", $datos['Sesiones']);
            $sql->bindParam(":Precio", $datos['Precio']);
            $sql->bindParam(":Estado", $datos['Estado']);
            $sql->bindParam(":ID", $datos['ID']);
            $sql->execute();
            return $sql;
        }
    }
?>