<?php
include 'header.php';
?>

        <!-- MAIN -->
        <main class="wrapper">
            <!-- PEDIDOS -->
            <div class="orders-container">
                <ul class="orders-list">
                <?php
                    $pedidos = readOrders($conn, null, "p.id = $_GET[id]");
                    if(sizeof($pedidos) == 0){
                ?>
                    <p>Pedido não encontrado.</p>
                <?php }else{ $pedido = $pedidos[0]; ?>
                    <li>
                        <div class="order-id">
                            <span><strong>Serviço: <?php echo str_pad($pedido['id'], 4, '0', STR_PAD_LEFT); ?></strong></span>
                            <span class="status <?php echo $pedido['status']; ?>"><?php echo getStatusMessage($pedido['status']); ?></span>
                        </div>
                        <div class="order-info">
                        <p style="font-weight: 500;"><?php echo ucwords($pedido['tipo_edif']); ?> - <?php echo $pedido['tipo_servico']; ?></p>
                        <p>Morada: <?php echo $pedido['morada']; ?></p>
                        <p>Criado em <?php echo $pedido['data']; ?></p>
                        <p style="margin-block: 10px; font-weight: 500;"><?php echo $pedido['num_propostas']; ?> propostas</p>
                        <p>Descrição:<br>A área de intervenção é de cerca de <?php echo $pedido['area']; ?> m², podendo ser confirmada no local, caso prefira. Desejo que os trabalhos iniciem em <?php echo $pedido['qndocomeca']; ?>.<br> As zonas em que pretendo intervenção apresentam atualmente acabamento <?php echo $pedido['situacao']; ?>, mas pretendo que tenha um acabamento <?php echo $pedido['acabamento']; ?>. Pretendo ainda ter como serviços adicionais <?php echo $pedido['adicionais']; ?>.</p><br>
                        <p><?php echo $pedido['infoextra']; ?></p><br>
                        <p><strong>Valor estimado: <?php echo $pedido['pestimado_min']; ?> € a <?php echo $pedido['pestimado_max']; ?> € + IVA</strong></p>
                        </div>
                    </li>
                </ul>

                <ul class="proposals-list">
                    <p>Propostas</p>
                    <?php
                    $proposals = getProposalsByOrder($conn, $_GET['id'], $_SESSION['id']);
                    if(empty($proposals)){
                    ?>
                        <li>
                        <form action="auth2.php" method="POST" class="send-proposal">
                            <div class="proposal-group">
                                <label for="proposta">Se tiver interesse neste serviço, apresente a sua proposta o quanto antes!</label>
                                <textarea name="proposta" rows="5" id="proposta" placeholder="Aproveite este espaço para melhor apresentar a si ou a sua empresa ao cliente que vai receber a sua proposta. Apresente suas condições, fale sobre trabalhos semelhantes já realizados, qualidades e vantagens do seu trabalho ou da sua empresa. É interessante também incluir informações sobre os materiais e procedimentos propostos."></textarea>
                            </div>
                            <div class="proposal-group">
                                <label for="valor">Valor da sua proposta:</label>
                                <input type="number" min="0.00" step="0.01" name="valor" onkeyup="formatarMoeda()" placeholder="2000.00" id="valor">
                                <p>O valor inserido ainda será acrescido do IVA em vigor.</p>
                            </div>
                            <div class="proposal-group">
                                <input type="hidden" name="action" value="proposta">
                                <input type="hidden" name="orderid" value="<?php echo $_GET['id']; ?>">
                                <input type="submit" class="btn2" value="Enviar proposta">
                            </div>
                        </form>
                        </li>
                    <?php
                    }else{
                        $proposal = $proposals[0];
                    ?>
                        <li>
                            <div class="company-info">
                                <p>Empresa: <strong><?php echo $proposal['nome']; ?></strong></p>
                                <p>NIF: <strong><?php echo $proposal['nif']; ?></strong></p>
                                <p>Data: <?php echo $proposal['data']; ?></p>
                                <span class="canceled">01</span><br>
                                <p><strong>Informações extras:</strong><br><?php echo $proposal['descricao']; ?></p>
                            </div>
                            <div class="proposal-info">
                                <p>Valor proposto: <strong><?php echo $proposal['pproposto']; ?> € + IVA</strong>
                                <?php if($proposal['status'] == 'approved'):
                                    echo '<br>Valor aceite: <strong>'. $proposal['pproposto']+$proposal['fee'].' € + IVA</strong> <i class="fa-solid fa-circle-check fa-lg" style="color: green"></i></i></p>'; 
                                    endif;
                                ?>
                                </p>
                                <div class="proposal-options">
                                    <?php if($proposal['status'] == 'approved'){
                                        echo '<p style="color: green; font-weight:500">Proposta aceita</p>';
                                    }elseif($proposal['status'] == 'rejected'){
                                        echo '<p style="color: red; font-weight:500">Proposta rejeitada</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
                <?php } ?>
            </div>
        </main>
    </div>

    <!-- MAIN JS -->
    <script src="js/main.js"></script>
</body>
</html>