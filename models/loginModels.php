<?php
    if ($requestAjax) {
        require_once("../core/mainModel.php");
    } else {
        require_once("./core/mainModel.php");
    }

class LoginModel extends MainModel{
    protected function startSessionModel($data){
        $sql = MainModel::connect()->prepare("SELECT * FROM cuenta WHERE CuentaUsuario = :Usuario AND CuentaClave = :Clave AND CuentaEstado = 'Activo'");
        $sql->bindParam(":Usuario", $data['Usuario']);
        $sql->bindParam(":Clave", $data['Clave']);
        $sql->execute();
        return $sql;
    }

    protected function destroySessionModelLogin($data){
        if ($data['User'] !== "" && $data['Token_S'] === $data['Token']) {
            $UpdateLog = MainModel::updateLog($data['Code'], $data['Your']);
            if ($UpdateLog->rowCount() >= 1) {
                // session_start(['name' => 'SBP']);
                session_unset();
                session_destroy();
                $response = "true";
            } else {
                $response = "false";
            }
            
        } else {
            $response = "false";
        }
        
        return $response;
    }
}
