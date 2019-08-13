<?php 
    require_once("models/viewModels.php");

    class ViewController extends ViewModels {

        //métodos 
        public function getModelController(){
           return require_once("view/modelo.php");
        }

        public function getViewController(){
            if(isset($_GET["view"])){
                $router = explode("/", $_GET["view"]);
                $response =  ViewModels::getViewModels($router[0]);//viewModel
            } else {
                $response = "login";
            }

            return $response;

        }

    }

?>