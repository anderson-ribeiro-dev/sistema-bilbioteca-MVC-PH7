<?php
    $requestAjax = true;
    require_once("../core/configGeral.php");

    if (isset($_GET['Token'])) {
        require_once("../controllers/loginController.php");
        $logout = new LoginController();
        echo $logout->destroySessionControllerLogin();
    } else {
        session_start();
        session_destroy();
        echo '<script> window.location.href=" ' . SERVERURL . '/login"</script>';
    }

?>