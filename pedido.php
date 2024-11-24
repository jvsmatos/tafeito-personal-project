<?php
include 'header.php';
?>

        <!-- MAIN -->
        <main class="wrapper">
            <!-- PEDIDOS -->
            <div class="orders-container">
                <ul class="orders-list">
                <?php
                if($_SESSION['id'] == $_GET['userid']){
                    $pedidos = readOrders($conn, null, "p.id = $_GET[id]");
                    if (empty($pedidos)){
                ?>
                    <p>Pedido não encontrado.</p>
                <?php }else{ $pedido = $pedidos[0]; ?>
                    <li>
                        <div class="order-id">
                            <span><strong>Pedido: <?php echo str_pad($pedido['id'], 4, '0', STR_PAD_LEFT); ?></strong></span>
                            <span class="status <?php echo $pedido['status']; ?>"><?php echo getStatusMessage($pedido['status']); ?></span>
                        </div>
                        <div class="order-info">
                            <p style="font-weight: 500;"><?php echo ucwords($pedido['tipo_edif']); ?> - <?php echo $pedido['tipo_servico']; ?></p>
                            <p>Morada: <?php echo $pedido['morada']; ?></p>
                            <p>Criado em <?php echo $pedido['data']; ?></p>
                            <p style="margin-block: 10px; font-weight: 500;"><?php echo $pedido['num_propostas']; ?> propostas</p>
                            <p>Descrição:<br>A área de intervenção é de cerca de <?php echo $pedido['area']; ?> m², podendo ser confirmada no local, caso prefira. Desejo que os trabalhos iniciem em <?php echo $pedido['qndocomeca']; ?>.<br>
                                As zonas em que pretendo intervenção apresentam atualmente acabamento <?php echo $pedido['situacao']; ?>, mas pretendo que tenha um acabamento <?php echo $pedido['acabamento']; ?>.
                                Pretendo ainda ter como serviços adicionais <?php echo $pedido['adicionais']; ?>.</p><br>
                            <p><?php echo $pedido['infoextra']; ?></p><br>
                            <p><strong>Valor estimado: <?php echo $pedido['pestimado_min']; ?> € a <?php echo $pedido['pestimado_max']; ?> € + IVA</strong></p>
                            <span>¹ Ao aceitar qualquer uma das propostas automaticamente as demais serão rejeitadas.</span>
                            <span>² Ao cancelar o pedido automaticamente todas as propostas existentes serão rejeitadas.</span>
                            <?php if($pedido['status'] == 'waiting'){?>
                            <button style="margin-left:auto; margin-top:5px;" order-id="<?php echo $pedido['id']; ?>" id="cancel" onclick="cancelOrder(this)" class="btn2">Cancelar Pedido</button>
                            <?php } ?>
                        </div>
                    </li>
                </ul>

                <ul class="proposals-list">
                    <p>Propostas</p>
                    <?php
                    $proposals = getProposalsByOrder($conn, $_GET['id']);
                    if(empty($proposals)){
                        echo '<p>Ainda não há nenhuma proposta para este pedido.</p>';
                    }else{
                        $i=0;
                        foreach($proposals as $proposal){
                            $i++;
                    ?>
                    <li>
                        <div class="company-info">
                            <p>Empresa: <strong><?php echo $proposal['nome']; ?></strong></p>
                            <p>NIF: <?php echo $proposal['nif']; ?></p>
                            <p>Data: <?php echo $proposal['data']; ?></p>
                            <span class="canceled"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT);?></span><br>
                            <p><strong>Informações extras:</strong><br><?php echo $proposal['descricao']; ?></p>
                        </div>
                        <div class="proposal-info">
                            <p>Valor proposto: <strong><?php echo $proposal['pproposto']+$proposal['fee']; ?> € + IVA</strong></p>
                            <div class="proposal-options">
                                <?php if($proposal['status'] == 'sent'): ?>
                                <button proposal-id="<?php echo $proposal['id']; ?>" order-id="<?php echo $_GET['id']; ?>" id="accept" onclick="acceptProposal(this)" class="btn2">Aceitar proposta</button>
                                <button proposal-id="<?php echo $proposal['id']; ?>" id="reject" onclick="rejectProposal(this)" class="btn">Recusar proposta</button>
                                <?php endif; ?>
                                <?php 
                                if($proposal['status'] == 'approved'){
                                    echo '<p style="color: green; font-weight:500">Proposta aceita</p>';
                                }elseif($proposal['status'] == 'rejected'){
                                    echo '<p style="color: red; font-weight:500">Proposta rejeitada</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </li>
                    <?php } }?>
                </ul>
                <?php } } ?>
            </div>
        </main>
    </div>

    <!-- MAIN JS -->
    <script src="js/main.js"></script>
</body>
</html>