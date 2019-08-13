<?php 
    require_once("core/configGeral.php");
    require_once("controllers/viewController.php");

    $models = new ViewController();
    $models->getModelController();
?>