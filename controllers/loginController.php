<?php
    
    if ($requestAjax) {
       require_once("../models/loginModels.php");
    } else {
         require_once("./models/loginModels.php");
    }
    
    class LoginController extends LoginModel {
        public function startSessionController(){
            $user = MainModel::cleanLineForm($_POST['UserName']);
            $password = MainModel::cleanLineForm($_POST['UserPass']);

            $passwordEncryption = MainModel::encryption($password);

            $dataLogin = [
                "Usuario" => $user,
                "Clave" => $passwordEncryption,
            ];

            $dataAccount = LoginModel::startSessionModel($dataLogin);

            if ($dataAccount->rowCount() == 1) {
               
               $row = $dataAccount->fetch();
               $currentDate = date("Y-m-d");
               $currentYear = date("Y");
               $currentHour = date("h:i:s a");
               $searchLog = MainModel::execQuerySimple("SELECT id FROM bitacora");
               $codeId = ($searchLog->rowCount()) + 1;
               $randonCode = MainModel::generateRandomCode("CB", 7, $codeId);
                    $dataLog = [
                        "Codigo"      => $randonCode,
                        "Fecha"       => $currentDate,
                        "HoraInicio"  => $currentHour,
                        "HoraFinal"   => "Sem Registro",
                        "Tipo"        => $row['CuentaTipo'],
                        "Year"        => $currentYear,
                        "CuentaCodigo"=> $row['CuentaCodigo']
                    ];

               $saveLog =  MainModel::saveLog($dataLog);

               if ($saveLog->rowCount() >= 1) {
                   session_start(['name' => 'SBP']); //SistemaBibliotecaPublica
                   $_SESSION['usuario_sbp'] = $row['CuentaUsuario'];
                   $_SESSION['tipo_sbp'] = $row['CuentaTipo'];
                   $_SESSION['privilegio_sbp'] = $row['CuentaPrivilegio'];
                   $_SESSION['foto_sbp'] = $row['CuentaFoto'];
                   $_SESSION['token_sbp'] = md5(uniqid(mt_rand(), true)); // generate token unique to session
                   $_SESSION['codigo_cuenta_sbp'] = $row['CuentaCodigo'];
                   $_SESSION['codigo_log_sbp'] = $randonCode;

                   if ($row['CuentaTipo'] === "Administrador") {
                       $url =  SERVERURL . "/home";
                   } else {
                       $url = SERVERURL . "/catalog";
                   }

                   return $urlLocation = '<script>
                                            window.location="'. $url .'";
                                         </script>';
               } else {
                   $alert = [
                       "Alert" => "simple",
                       "Title" => "Ocorreu um erro inesperado!",
                       "Text" => "Não é possível iniciar a sessão, Por problemas técnicos, por favor tente novamente!",
                       "Type" => "error",
                   ];
                   return MainModel::sweetAlert($alert); 
               }
            } else {
                $alert = [
                    "Alert" => "simple",
                    "Title" => "Ocorreu um erro inesperado!",
                    "Text" => "Usuário ou Senha incorreto, Tente novamente!",
                    "Type" => "error",
                ];

                return MainModel::sweetAlert($alert);
            }
            
        }

        //login
        public function destroySessionControllerLogin(){
            session_start(['name' => 'SBP']);
            $token = MainModel::descryption($_GET['Token']);
            $Your = date("h:i:s a");
            $data = [
                "User" => $_SESSION['usuario_sbp'],
                "Token_S" => $_SESSION['token_sbp'],
                "Token" => $token,
                "Code" => $_SESSION['codigo_log_sbp'],
                "Your" => $Your,
            ];

            return LoginModel::destroySessionModelLogin($data);
        }


        //Terminate session 
        public function destroySessionController() {
            session_start(['name' => 'SBP']);
            session_destroy();
            return header("Location: " . SERVERURL . "/login" );
        }
    }
