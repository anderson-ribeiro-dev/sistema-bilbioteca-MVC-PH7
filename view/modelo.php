<!DOCTYPE html>
<html lang="es">

<head>
    <title><?php echo COMPANY ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="<?php echo SERVERURL; ?> /view/css/main.css">
    <?php require_once("view/modulos/scripts.php"); ?>

</head>

<body>
    <?php
    $requestAjax = false;
    // require_once("../controllers/viewController.php");
    require_once("controllers/viewController.php");
    $viewController = new ViewController();
    $viewControllerR = $viewController->getViewController();

    if ($viewControllerR === "login" || $viewControllerR === "404") {
        if ($viewControllerR === "login") {
            // require_once ("../view/conteudo/login-view.php");
            require_once("view/conteudo/login-view.php");
        } else {
            require_once("view/conteudo/404-view.php");
            // require_once("../view/conteudo/404-view.php");
        }
    } else {
        session_start(['name' => 'SBP']);
        require_once("controllers\loginController.php");
        $loginController = new LoginController();
        if (!isset($_SESSION['token_sbp']) || !isset($_SESSION['usuario_sbp'])) {
            $loginController->destroySessionController();
        }

        ?>
    <!-- SideBar -->
    <?php require_once("view/modulos/navlateral.php"); ?>
    <!-- Content page-->
    <section class="full-box dashboard-contentPage">
        <!-- NavBar -->
        <?php require_once("view/modulos/navbar.php"); ?>
        <!-- Content page -->
        <?php require_once $viewControllerR; ?>
    </section>

    <?php

        require_once("view/modulos/logoutScripts.php");
    }
    ?>
    <script>
        $.material.init();
    </script>
</body>

</html>