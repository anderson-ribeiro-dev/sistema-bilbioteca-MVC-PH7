<!DOCTYPE html>
<html lang="es">

<head>
    <title><?php echo COMPANY ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="<?php echo SERVERURL; ?> /view/css/main.css">
</head>

<body>
    <?php 
        require_once("controllers/viewController.php");
        $viewController = new ViewController();
        $viewControllerR = $viewController->getViewController();
        
        if($viewControllerR === "login"){
            require_once ("view/conteudo/login-view.php");
        } else {
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
        }
    ?>       
    <!--====== Scripts -->
    <?php require_once("view/modulos/scripts.php"); ?>
</body>

</html>