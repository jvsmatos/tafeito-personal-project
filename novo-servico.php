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
                    ?>
                </div>
                <form action="auth.php" method="POST" class="login-form">
                    <fieldset>
                        <legend>Informações do Serviço</legend>
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="nome" name="nome" id="nome" class="input-field" required>
                        </div>
                        <div class="form-group">
                            <label for="pmin">Preço/m² mínimo (€)</label>
                            <input type="number" name="pmin" id="pmin" step="0.1" class="input-field" required>
                        </div>
                        <div class="form-group">
                            <label for="pmax">Preço/m² máximo (€)</label>
                            <input type="number" name="pmax" id="pmax" step="0.1" class="input-field" required>
                        </div>
                        <div class="form-group">
                            <label for="pminserv">Preço de Serviço Mínimo (€)</label>
                            <input type="number" name="pminserv" step="5" id="pminserv" class="input-field" required>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="action" value="addServico">
                            <input type="submit" class="btn2 login-btn" value="Criar Serviço">
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