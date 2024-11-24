<?php
include 'header.php';
?>

        <!-- MAIN -->
        <main class="wrapper">
            <!-- PEDIDOS -->
            <div class="orders-container">
                <ul class="orders-list">
                <?php
                $userid = $_SESSION['id'];
                $pedidos = readOrders($conn,null,"pr.usuarioid = $userid");
                if (empty($pedidos)){
                    echo '<p>No momento não existe nenhum pedido disponível.</p>';
                }else{ 
                    foreach($pedidos as $pedido){ 
                ?>
                    <li>
                        <a href="servico.php?id=<?php echo $pedido['id']; ?>">
                            <div class="order-id">
                                <span class="order-num">Serviço: <?php echo str_pad($pedido['id'], 4, '0', STR_PAD_LEFT); ?></span>
                                <span class="status <?php echo $pedido['propstatus']; ?>"><?php echo getStatusMessage($pedido['propstatus']); ?></span>
                            </div>
                            <div class="order-info">
                                <p style="font-weight: 500;"><?php echo ucwords($pedido['tipo_edif']); ?> - <?php echo $pedido['tipo_servico']; ?></p>
                                <p>Morada: <?php echo $pedido['morada']; ?></p>
                                <p>Criado em <?php echo $pedido['data']; ?></p>
                                <p style="margin-block: 10px; font-weight: 500;"><?php echo $pedido['num_propostas']; ?> propostas</p>
                                <p>Descrição:<br>A área de intervenção é de cerca de <?php echo $pedido['area']; ?> m², podendo ser confirmada no local, caso prefira. Desejo que os trabalhos iniciem em <?php echo $pedido['qndocomeca']; ?>.<br> As zonas em que pretendo intervenção apresentam atualmente acabamento <?php echo $pedido['situacao']; ?>, mas pretendo que tenha um acabamento <?php echo $pedido['acabamento']; ?>. Pretendo ainda ter como serviços adicionais <?php echo $pedido['adicionais']; ?>.</p>
                            </div>
                        </a>
                    </li>
                    <?php } ?>
                <?php } ?>
                </ul>
            </div>
        </main>
    </div>

    <!-- MAIN JS -->
    <script src="js/main.js"></script>
</body>
</html>