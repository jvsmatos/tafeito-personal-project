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
                <div class="login-form" style="width:max-content">
                    <div class="tab">
                        <span style="color:#BB0013; font-size: 11px;">ATENÇÃO: Ao deletar um serviço todas as propostas relacionadas ao mesmo também serão deletadas.</span><br>
                        <!-- Cabeçalho com os nomes das colunas -->
                        <ul class="header" style="font-size:14px;">
                            <li>Nome</li>
                            <li>€ Mínimo</li>
                            <li>€ Máximo</li>
                            <li class="tabhide">€ Mín. Serviço</li>
                            <li>Ações</li>
                        </ul>
                        
                        <!-- Contêiner para listar cada usuário -->
                        <div class="user-list">
                            <?php
                            $servicos = getServices($conn); // Obtém todos os usuários
                            //var_dump($servicos);
                            if ($servicos) {
                                foreach ($servicos as $servico) {
                                    echo "<ul class='user-row' style='text-align:center'>";
                                    echo "<li>" . htmlspecialchars($servico['nome']) . "</li>";
                                    echo "<li>" . htmlspecialchars($servico['valorMinimo']) . "</li>";
                                    echo "<li>" . htmlspecialchars($servico['valorMaximo']) . "</li>";
                                    echo "<li class='tabhide'>" . htmlspecialchars($servico['valorMinimoServico']) . "</li>";
                                    echo "<li><a href='edit-servico.php?id=". $servico['id'] ."'><i class='fa-regular fa-pen-to-square'></i></a> <a href='' servico-id=". $servico['id'] ." id='reject' onclick='deleteService(this)'><i class='fa-solid fa-trash-can'></i></a></li>";
                                    echo "</ul>";
                                }
                            }
                            ?>
                        </div>
                        <br>
                        <a class="btn2" style="text-decoration:none" href="novo-servico.php">Novo Serviço [+]</a>
                    </div>
                </div>
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