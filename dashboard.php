<?php
include 'header.php';
?>

        <!-- MAIN -->
        <main class="wrapper">
            <div class="dashboard-container">
                <div class="dashboard-box">
                    <?php
                        $orderStats     = getOrderStats($conn);
                        $userStats      = getUserStats($conn);
                        $proposalStats  = getProposalStats($conn);
                        $serviceStats   = countServiceStats($conn);
                    ?>
                    <p>Pedidos</p>
                    <ul>
                        <li>Aprovados: <?php echo $orderStats['aprovados']; ?></li>
                        <li>Aguardando propostas: <?php echo $orderStats['aguardando_propostas']; ?></li>
                        <li>Cancelados: <?php echo $orderStats['cancelados']; ?></li>
                    </ul>
                </div>
                <div class="dashboard-box">
                    <p>Usuários</p>
                    <ul>
                        <li>Clientes: <?php echo $userStats['clientes']; ?></li>
                        <li>Empresas: <?php echo $userStats['empresas']; ?></li>
                        <li>Profissionais: <?php echo $userStats['profissionais']; ?></li>
                        <li>Admin: <?php echo $userStats['admins']; ?></li>
                    </ul>
                </div>
                <div class="dashboard-box">
                    <p>Propostas</p>
                    <ul>
                        <li>Enviadas: <?php echo $proposalStats['enviadas']; ?></li>
                        <li>Aprovadas: <?php echo $proposalStats['aprovadas']; ?></li>
                        <li>Rejeitadas: <?php echo $proposalStats['rejeitadas']; ?></li>
                    </ul>
                </div>
                <div class="dashboard-box">
                    <p>Serviços</p>
                    <ul>
                        <li style="font-size:20px; text-align:center; font-weight:bold;"><?php echo $serviceStats['total_servicos']; ?></li>
                    </ul>
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