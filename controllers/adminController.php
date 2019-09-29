<?php
    if ($requestAjax) {
        require_once("../models/adminModels.php");
    } else {
        require_once("./models/adminModels.php");
    }

    class AdminController extends AdminModels {
        
        public function insertAdminController(){
           $dni = MainModel::cleanLineForm($_POST["dni-reg"]); 
           $nombre = MainModel::cleanLineForm($_POST["nombre-reg"]);
           $apellido = MainModel::cleanLineForm($_POST["apellido-reg"]);
           $telefono = MainModel::cleanLineForm($_POST["telefono-reg"]);
           $direccion = MainModel::cleanLineForm($_POST["direccion-reg"]);

           $usuario = MainModel::cleanLineForm($_POST["usuario-reg"]);
           $password1 = MainModel::cleanLineForm($_POST["password1-reg"]);
           $password2 = MainModel::cleanLineForm($_POST["password2-reg"]);
           $email = MainModel::cleanLineForm($_POST["email-reg"]);
           $genero = MainModel::cleanLineForm($_POST["optionsGenero"]);
           $privilegio = MainModel::cleanLineForm($_POST["optionsPrivilegio"]);

           //avatar
           if ($genero === "Masculino") {
               $foto = "Male3Avatar.png";
           } else {
               $foto = "Female3Avatar.png";
           }

           //confirm password
           if($password1 !== $password2) {
                $alert = [
                    "Alert" => "simple",
                    "title" => "Ocorreu um erro inesperado!",
                    "text" => "Senhas não são iguais!, Por favor tente novamente!",
                    "type" => "error",
                ];
                
           } else {
                $searchDNI = MainModel::execQuerySimple("SELECT AdminDNI FROM admin WHERE AdminDNI = '$dni'");
                if ($searchDNI->rowCount() >= 1) {
                    $alert = [
                        "Alert" => "simple",
                        "Title" => "Ocorreu um erro inesperado!",
                        "Text" => "DNI já foi cadastrado, por favor tente outro!",
                        "Type" => "error",
                    ];
                   
                } else {
                    if ($email !== "") {
                        $searchEmail = MainModel::execQuerySimple("SELECT CuentaEmail FROM cuenta WHERE CuentaEmail = '$email'");
                        $emailAccount = $searchEmail->rowCount();
                    } else {
                       $emailAccount = 0;
                    }
                    if ($emailAccount >= 1) {
                        $alert = [
                            "Alert" => "simple",
                            "Title" => "Ocorreu um erro inesperado!",
                            "Text" => "Email já foi cadastrado, por favor tente outro!",
                            "Type" => "error",
                        ];
                        
                    } else {
                       $searchUsuario = MainModel::execQuerySimple("SELECT CuentaUsuario FROM cuenta WHERE CuentaUsuario = '$usuario'");
                       if ($searchUsuario->rowCount() >= 1) {
                            $alert = [
                                "Alert" => "simple",
                                "Title" => "Ocorreu um erro inesperado!",
                                "Text" => "Usuário já foi cadastrado, por favor tente outro!",
                                "Type" => "error",
                            ];
                           
                       } else {
                          $searchId = MainModel::execQuerySimple("SELECT id FROM cuenta");
                          $amountId = ($searchId->rowCount()) + 1;
                          $code = MainModel::generateRandomCode("AC", 7, $amountId);
                          $key = MainModel::encryption($password1);

                          $dadosAccount = [
                            "Codigo" => $code,
                            "Privilegio" => $privilegio,
                            "Usuario" => $usuario,
                            "Clave" => $key,
                            "Email" => $email, 
                            "Estado" => "Activo",
                            "Tipo" => "Administrador",
                            "Genero" => $genero,
                            "Foto" => $foto,
                          ];

                          $saveAccount = MainModel::insertAccount($dadosAccount
                        );

                        if ($saveAccount->rowCount() >= 1) {
                            $dataAdministrator = [
                               "DNI" => $dni,
                               "Nombre" => $nombre,
                               "Apellido" => $apellido,
                               "Telefono" => $telefono,
                               "Direccion" => $direccion,
                               "Codigo" => $code,
                            ];

                            $saveAdmin = AdminModels::insertAdminModel($dataAdministrator);

                            if ($saveAdmin->rowCount() >= 1) {
                                $alert = [
                                    "Alert" => "clean",
                                    "Title" => "Administrador Registrado!",
                                    "Text" => "Administrador Registrado com sucesso!",
                                    "Type" => "success",
                                ];
                               
                            } else {
                                MainModel::deleteAccount($code);
                                $alert = [
                                    "Alert" => "simple",
                                    "Title" => "Ocorreu um erro inesperado!",
                                    "Text" => "Não foi possível registrar o administrador!",
                                    "Type" => "error",
                                ];
                                
                            }
                            
                        } else {
                            $alert = [
                                "Alert" => "simple",
                                "Title" => "Ocorreu um erro inesperado!",
                                "Text" => "Não foi possível registrar o administrador!",
                                "Type" => "error",
                            ];
                           
                        }
                        
                       }
                       
                    }
                }    
           }

           return MainModel::sweetAlert($alert);
        }  
        
        //paginate administrador
        public function paginateAdministratorController($page, $register, $privilege, $code){
            $page = MainModel::cleanLineForm($page);
            $register = MainModel::CleanLineForm($register);
            $privilege = MainModel::CleanLineForm($privilege);
            $code = MainModel::CleanLineForm($code);
            $table = "";

            $page = (isset($page) && $page > 0) ? (int)$page : 1;
            $initial = ($page > 0) ? (($page * $register) - $register) : 0;

            $conn = MainModel::connect();
            $datas = $conn->query("
                SELECT SQL_CACL_FOUND_ROWS * FROM admin WHERE CuentaCodigo != '$code' AND id != '1' ORDER BY AdminNombre ASC LIMIT $initial, $register
            ");

            $data = $datas->fetchAll(); //array query

            $total = $conn->query("SELECT FOUND_ROWS()");
            $total = (int) $total->fetchColumn();

            $numberPage = ceil($total / $register); //int ceil()

            $table .= '
            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">DNI</th>
                            <th class="text-center">NOMBRES</th>
                            <th class="text-center">APELLIDOS</th>
                            <th class="text-center">TELÉFONO</th>
                            <th class="text-center">A. CUENTA</th>
                            <th class="text-center">A. DATOS</th>
                            <th class="text-center">ELIMINAR</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            if ($total >= 1 && $page <= $numberPage) {
                $counter = $initial + 1;
                foreach($datas as $rows){
                    $table .= '
                        <tr>
                            <td>'. $counter .'</td>
                            <td>' .rows['AdminDNI']. '</td>
                            <td>' . rows['AdminNombre'] . '</td>
                            <td>' . rows['AdminApellido'] . '</td>
                            <td>' . rows['AdminTelefono'] . '</td>
                            <td>
                                <a href="#!" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-refresh"></i>
                                </a>
                            </td>
                            <td>
                                <a href="#!" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-refresh"></i>
                                </a>
                            </td>
                            <td>
                                <form>
                                    <button type="submit" class="btn btn-danger btn-raised btn-xs">
                                        <i class="zmdi zmdi-delete"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    ';
                    $counter ++;
                }
            } else {
                $table .= '
                    <tr>
                        <td colspan="5">Não existe registro no sistema</td>
                    </tr>
                ';
            }
            
            $table .= '</tbody></table></div>';


            return $table;
        }

    }
