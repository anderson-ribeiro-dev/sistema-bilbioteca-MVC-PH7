<?php
    if ($requestAjax) {
        require_once("../core/mainModel.php");
    } else {
        require_once("../core/mainModel.php");
    }

    class AdminModels extends MainModel {
        protected function insertAdminModel($dados) {
            $sql = MainModel::connect()->prepare("INSERT INTO admin(AdminDNI, AdminNombre, AdminApellido, AdminTelefono, AdminDireccion, CuentaCodigo) VALUES (:DNI, :Nombre, :Apellido, :Telefono, :Direccion, :Codigo)");
            $sql->bindParam(":DNI", $dados["DNI"]);
            $sql->bindParam(":Nombre", $dados["Nombre"]);
            $sql->bindParam(":Apellido", $dados["Apellido"]);
            $sql->bindParam(":Telefono", $dados["Telefono"]);
            $sql->bindParam(":Direccion", $dados["Direccion"]);
            $sql->bindParam(":Codigo", $dados["Codigo"]);
            $sql->execute();
            return $sql;
        }
    }
