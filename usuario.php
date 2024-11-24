<?php
include 'header.php';
?>

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
                    $usuario = readuser($conn, $_GET['id']);
                    
                    $user = $usuario[0];
                    ?>
                </div>
                <form action="auth.php" method="POST" class="login-form">
                    <fieldset>
                        <legend>Perfil do Usuário</legend>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" value="<?php echo $user['email'];?>" class="input-field" required>
                        </div>
                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <select name="tipo" class="input-field" id="tipo" required>
                                <option value=""></option>
                                <option value="cliente" <?php if ($user['tipo'] == 'cliente') echo 'selected'; ?>>Cliente</option>
                                <option value="profissional" <?php if ($user['tipo'] == 'profissional') echo 'selected'; ?>>Profissional Independente</option>
                                <option value="empresa" <?php if ($user['tipo'] == 'empresa') echo 'selected'; ?>>Empresa</option>
                                <option value="admin" <?php if ($user['tipo'] == 'admin') echo 'selected'; ?>>Administrador</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" class="input-field" disabled>
                        </div>
                        <div class="form-group">
                            <label for="nif">NIF</label>
                            <input type="number" id="nif" name="nif" value="<?php echo htmlspecialchars($user['nif']); ?>" class="input-field">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="action" value="atualizar">
                            <input type="hidden" name="clienteid" value="<?php echo $user['id'];?>">
                            <input type="submit" class="btn2 login-btn" value="Atualizar Dados">
                        </div>
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