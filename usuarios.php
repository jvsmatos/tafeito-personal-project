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
                        <!-- Cabeçalho com os nomes das colunas -->
                        <ul class="header">
                            <li>Nome</li>
                            <li class="tabhide">E-mail</li>
                            <li>Tipo</li>
                            <li>Ações</li>
                        </ul>
                        
                        <!-- Contêiner para listar cada usuário -->
                        <div class="user-list">
                            <?php
                            $usuarios = readuser($conn); // Obtém todos os usuários

                            if ($usuarios) {
                                foreach ($usuarios as $usuario) {
                                    echo "<ul class='user-row'>";
                                    echo "<li>" . htmlspecialchars($usuario['nome']) . "</li>";
                                    echo "<li class='tabhide'>" . htmlspecialchars($usuario['email']) . "</li>";
                                    echo "<li>" . htmlspecialchars($usuario['tipo']) . "</li>";
                                    echo "<li><a href='usuario.php?id=". $usuario['id'] ."'><i class='fa-regular fa-pen-to-square'></i></a> <a href='' user-id=". $usuario['id'] ." id='reject' onclick='deleteUser(this)'><i class='fa-solid fa-trash-can'></i></a></li>";
                                    echo "</ul>";
                                }
                            }
                            ?>
                        </div>
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