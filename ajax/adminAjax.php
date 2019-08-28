<?php 
    $requestAjax = true;
    require_once("../core/configGeral.php");

    if (isset($_POST['dni-reg'])) {
       require_once("../controllers/adminController.php");
       $AdminController = new AdminController();

       if(isset($_POST['dni-reg']) && isset($_POST['nombre-reg']) && isset($_POST['apellido-reg']) && isset($_POST['usuario-reg'])) {
            echo $AdminController->insertAdminController();
       }
    } else {
        session_start();
        session_destroy();
        echo '<script> window.location.href=" '. SERVERURL .'/login/"</script>';
    }
    
?>