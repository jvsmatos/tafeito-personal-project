<?php
session_start();
if(isset($_SESSION['id'])){
    header("Location: profile.php");
    exit();
}else{
    // Adicionar verificação de tempo de expiração da sessão
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TáFeito - Nunca foi tão fácil conseguir bons profissionais!</title>

    <link rel="stylesheet" href="css/panel.css">
    <script src="https://kit.fontawesome.com/3c8c59d717.js" crossorigin="anonymous"></script>

    <link rel="shortcut icon" href="images/TF.png" type="image/x-icon"> 

</head>
<body>
    
    <div class="container">
    <!-- HEADER -->
        <nav id="header">
            <div class="header-container">
                <div class="nav-logo">
                    <a href="index.php">
                        <p class="nav-name">Tá Feito<span>.</span></p>
                    </a>
                </div>
                <div class="nav-button">
                    <a href="cadastro.php" class="btn">Cadastro<i class="fa-solid fa-user-plus"></i></a>
                </div>
            </div>
        </nav>

        <!-- MAIN -->
        <main class="wrapper">
            <!-- LOGIN FORM -->
            <div class="form-container">
                <div class="alerts">
                    <?php
                    //Se o "alert" não estiver vazio
                    if(!empty($_SESSION['alert'])){
                        //Exibe o alert com a classe e msg correspondente
                        echo '<span id="alerta" class="' . htmlspecialchars($_SESSION['alert']) . '">' . htmlspecialchars($_SESSION['msg']) . '</span>';
                        unset($_SESSION['alert']);
                        unset($_SESSION['msg']);
                    }
                    ?>             
                </div>
                <form action="auth.php" method="POST" class="login-form">
                    <fieldset>
                        <legend>Login</legend>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="text" name="email" id="email" placeholder="email@email.com" class="input-field" required>
                        </div>
                        <div class="form-group">
                            <label for="pass">Senha</label>
                            <input type="password" name="password" id="pass" placeholder="********" class="input-field" required>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="action" value="login">
                            <input type="submit" class="btn2 login-btn" value="Entrar">
                        </div>
                        <p>Esqueceu a senha?  <a href="#">Recupere a password</a></p>
                    </fieldset>
                </form>
            </div>
        </main>
    </div>

    <!-- MAIN JS -->
    <script src="js/main.js"></script>
    <script>
        fadeOutAlert();
    </script>
</body>
</html>