<?php
    require_once("core/configGeral.php");
    require_once("controllers/viewController.php");
    // require_once("controllers/loginController.php");

    $controller = new ViewController();
    $controller->getModelController();
    // $loginController = new LoginController();
?>
