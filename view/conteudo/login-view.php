<div class="full-box login-container cover">
    <form action="" method="POST" autocomplete="off" class="logInForm">
        <p class="text-center text-muted"><i class="zmdi zmdi-account-circle zmdi-hc-5x"></i></p>
        <p class="text-center text-muted text-uppercase">Entrar Na Sua Conta </p>
        <div class="form-group label-floating">
            <label class="control-label" for="UserName">Usuario</label>
            <input class="form-control input-login" id="UserName" name="UserName" type="text" required>
            <p class="help-block">Digite o nome do usuário</p>
        </div>
        <div class="form-group label-floating">
            <label class="control-label" for="UserPass">Senha</label>
            <input class="form-control input-login" id="UserPass" name="UserPass" type="password" required>
            <p class="help-block">Digite a sua senha</p>
        </div>
        <div class="form-group text-center">
            <input type="submit" value="Entrar" class="btn btn-info" style="color: black; background-color:white; ">
        </div>
    </form>
</div>

<?php
if (isset($_POST['UserName']) && isset($_POST['UserPass'])) {
    require_once("controllers/loginController.php");
    $login = new LoginController();
    echo $login->startSessionController();
}
?>