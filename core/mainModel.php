<?php 
    if ($requestAjax) {
        require_once("core/configApp.php");
    } else {
        require_once("core/configApp.php");
    }
    

    class MainModel {
        protected function connect(){
            $bd = new PDO(SGBD, USER, PASS);
            return $bd;
        }

        protected function execQuerySimple($query){
            $response = self::connect()->prepare($query);
            $response->execute();
            return $response;
        }

        //password
        public function encryption($string){
            $output = FALSE;
            $key = has('sha256', SECRET_KEY);
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
        protected function sweetAlert($dados){
            if($dados['Alert'] === "simple") {
                $alert =  "
                    <script>
                        Swal.fire(
                            '". $dados["Title"] ."',
                            '". $dados["Text"] ."',
                            '". $dados["Type"] ."'
                        );
                    </script>
                ";
            } elseif ($dados['Alert'] === "reload") {
                $alert =  "
                    <script>
                        Swal.fire({
                            title: '". $dados["Title"]."',
                            text: '". $dados["Text"] . "',
                            type: '". $dados["Type"] . "',
                            confirmButtonText: 'Aceitar',
                        }).then((result) => {
                            if (result.value) {
                               location.reload(); 
                            }
                        })   
                    </script>
                ";
            } elseif($dados['Alert'] === "clean") {
                $alert =  "
                    <script>
                        Swal.fire({
                            title: '" . $dados["Title"] . "',
                            text: '" . $dados["Text"] . "',
                            type: '" . $dados["Type"] . "',
                            confirmButtonText: 'Limpar',
                        }).then((result) => {
                            if (result.value) {
                                $(.formClean)[0].reset();
                            }
                        })   
                    </script>
                ";
            } 
            return $alert;
        }


    }

?>