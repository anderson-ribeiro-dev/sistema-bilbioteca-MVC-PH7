<?php 
    if ($requestAjax = true) {
        require_once("configApp.php");
    } else {
        require_once("configApp.php");
    }

    class MainModel  {
        protected function connect(){
            $bd = new PDO(SGBD, USER, PASS);
            return $bd;
        }

        protected function execQuerySimple($query){
            $response = self::connect()->prepare($query);
            $response->execute();
            return $response;
        }


        //insert bd
        protected function insertAccount($data) {
            $sql = self::connect()->prepare("INSERT INTO cuenta(CuentaCodigo, CuentaPrivilegio, CuentaUsuario, CuentaClave, CuentaEmail, CuentaEstado, CuentaTipo, CuentaGenero, CuentaFoto) VALUES (:Codigo,:Privilegio, :Usuario, :Clave, :Email, :Estado, :Tipo, :Genero, :Foto)");
            $sql->bindParam(":Codigo", $data["Codigo"]);
            $sql->bindParam(":Privilegio", $data["Privilegio"]);
            $sql->bindParam(":Usuario", $data["Usuario"]);
            $sql->bindParam(":Clave", $data["Clave"]);
            $sql->bindParam(":Email", $data["Email"]);
            $sql->bindParam(":Estado", $data["Estado"]);
            $sql->bindParam(":Tipo", $data["Tipo"]);
            $sql->bindParam(":Genero", $data["Genero"]);
            $sql->bindParam(":Foto", $data["Foto"]);
            $sql->execute();
            return $sql;
        }

        protected function deleteAccount($codigo){
            $sql = self::connect()->prepare("DELETE FROM cuenta WHERE CuentaCodigo = :Codigo");
            $sql->bindParam(":Codigo", $codigo);
            $sql->execute();
            return $sql;
        }

        //log
        protected function saveLog($data){
           $sql = self::connect()->prepare("INSERT INTO bitacora(BitacoraCodigo,BitacoraFecha, BitacoraHoraInicio, BitacoraHoraFinal, BitacoraTipo,BitacoraYear, CuentaCodigo) VALUES (:Codigo, :Fecha, :HoraInicio, :HoraFinal, :Tipo, :BYear, :CuentaCodigo )") ;
           $sql->bindParam(":Codigo", $data['Codigo']);
           $sql->bindParam(":Fecha", $data['Fecha']);
           $sql->bindParam(":HoraInicio", $data['HoraInicio']);
           $sql->bindParam(":HoraFinal", $data['HoraFinal']);
           $sql->bindParam(":Tipo", $data['Tipo']);
           $sql->bindParam(":BYear", $data['Year']);
           $sql->bindParam(":CuentaCodigo", $data['CuentaCodigo']);
           $sql->execute();
           return $sql;
        }

        protected function updateLog($code, $hour){
            $sql = self::connect()->prepare("UPDATE bitacora SET BitacoraHoraFinal = :Hora WHERE BitacoraCodigo = :Codigo ");
            $sql->bindParam(":Hora", $hour);
            $sql->bindParam(":Codigo", $code);
            $sql->execute();
            return $sql;
        }

        
        protected function deleteLog($code){
            $sql = self::connect()->prepare("DELETE FROM bitacora WHERE CuentaCodigo = :Codigo");
            $sql->bindParam(":Codigo", $code);
            $sql->execute();
            return $sql;
        }


        //password
        public function encryption($string){
            $output = FALSE;
            $key = hash('sha256', SECRET_KEY);
            $iv = substr(hash('sha256', SECRET_IV), 0, 16);
            $output = openssl_encrypt($string, METHOD, $key, 0, $iv);
            $output = base64_encode($output);
            return $output;
        }

        public function descryption($string) {
            $key = hash('sha256', SECRET_KEY);
            $iv = substr(hash('sha256', SECRET_IV), 0, 16);
            $output = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
            return $output;
        }

        
        protected function generateRandomCode($letter, $longitude, $num){
            for($i = 1; $i <= $longitude; $i++) {
                $number = rand(0, 9);
                $letter .= $number;
            }

            return $letter.$num;
        }

        protected function cleanLineForm($stringClean){
           $stringClean = trim($stringClean); 
           $stringClean = stripslashes($stringClean);
           $stringClean = str_ireplace("<script>", "", $stringClean);
           $stringClean = str_ireplace("</script>", "", $stringClean);
           $stringClean = str_ireplace("</script src>", "", $stringClean);
           $stringClean = str_ireplace("</script type=", "", $stringClean);
           $stringClean = str_ireplace("SELECT * FROM", "", $stringClean);
           $stringClean = str_ireplace("DELETE FROM", "", $stringClean);
           $stringClean = str_ireplace("INSERT INTO", "", $stringClean);
           $stringClean = str_ireplace("--", "", $stringClean);
           $stringClean = str_ireplace("^", "", $stringClean);
           $stringClean = str_ireplace("[", "", $stringClean);
           $stringClean = str_ireplace("]", "", $stringClean);
           $stringClean = str_ireplace("==", "", $stringClean);
           $stringClean = str_ireplace(";", "", $stringClean);

           return $stringClean;
        }

        //https://sweetalert2.github.io/
        protected function sweetAlert($data){
            if($data['Alert'] === "simple") {
                $alert =  "
                    <script>
                        swal(
                            '". $data["Title"] ."',
                            '". $data["Text"] ."',
                            '". $data["Type"] ."'
                        );
                    </script>
                ";
            } elseif ($data['Alert'] === "reload") {
                $alert =  "
                    <script>
                        swal({
                            title: '". $data["Title"]."',
                            text: '". $data["Text"] . "',
                            type: '". $data["Type"] . "',
                            confirmButtonText: 'Aceitar',
                        }).then((result) => {
                            if(result.value){
                                location.reload(); 
                            }
                        })   
                    </script>
                ";
            } elseif($data['Alert'] === "clean") {
                $alert =  "
                    <script>
                        swal({
                            title: '" . $data["Title"] . "',
                            text: '" . $data["Text"] . "',
                            type: '" . $data["Type"] . "',
                            confirmButtonText: 'Aceitar',
                        }).then((result) => {
                            if(result.value){
                                $(.formClean)[0].reset();
                            }
                        });   
                    </script>
                ";
            } 
            return $alert;
        }


    }

?>