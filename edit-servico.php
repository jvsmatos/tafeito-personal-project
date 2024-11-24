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
                    $servicos = getServices($conn, $_GET['id']);
                    
                    $service = $servicos[0];
                    ?>
                </div>
                <form action="auth.php" method="POST" class="login-form">
                    <fieldset>
                        <legend>Informações do Serviço</legend>
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="nome" name="nome" id="nome" value="<?php echo $service['nome'];?>" class="input-field" required>
                        </div>
                        <div class="form-group">
                            <label for="pmin">Preço/m² mínimo (€)</label>
                            <input type="number" name="pmin" id="pmin" step="0.1" value="<?php echo htmlspecialchars($service['valorMinimo']); ?>" class="input-field" required>
                        </div>
                        <div class="form-group">
                            <label for="pmax">Preço/m² máximo (€)</label>
                            <input type="number" name="pmax" id="pmax" step="0.1" value="<?php echo htmlspecialchars($service['valorMaximo']); ?>" class="input-field" required>
                        </div>
                        <div class="form-group">
                            <label for="pminserv">Preço de Serviço Mínimo (€)</label>
                            <input type="number" name="pminserv" step="5" id="pminserv" value="<?php echo htmlspecialchars($service['valorMinimoServico']); ?>" class="input-field" required>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="action" value="atualizarServico">
                            <input type="hidden" name="servicoid" value="<?php echo $service['id'];?>">
                            <input type="submit" class="btn2 login-btn" value="Atualizar Serviço">
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