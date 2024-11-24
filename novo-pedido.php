<?php
include 'header.php';
?>

        <!-- MAIN -->
        <main class="wrapper">
            <!-- PEDIDO -->
            <div class="alerts" style="padding-inline:30px; margin-bottom:7px; height:50px">
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
            <div class="orcamento-box">
                <form action="auth2.php" id="orc-simulator" method="POST" class="form-control">
                    <span>- Durante 7 dias a sua solicitação estará disponível para que os profissionais apresentem propostas.</span>
                    <span>- Os valores são considerados, por default, com uso do alpinismo para execução dos trabalhos.</span>
                    <span>- Para estas simulações todos os materiais necessários já estão inclusos nos valores apresentados.</span>
                    <span>- As informações extras não são consideradas na estimativa de valores.</span>
                    <fieldset>
                        <legend>Dados do pedido:</legend>
                        <div class="form-client">
                            <div class="form-group w100">
                                <label for="morada">Morada completa</label>
                                <input type="text" id="morada" name="morada" class="input-field" placeholder="Rua João Pessoa, 53, 5D - Lisboa" required>
                            </div>
                            <div class="form-group">
                                <label for="postal">Código Postal</label>
                                <input type="text" name="cep" id="postal" class="input-field" placeholder="0000-000" required>
                            </div>
                            <div class="form-group">
                                <label for="telemovel">Telemóvel</label>
                                <input type="tel" name="telemovel" id="telemovel"  class="input-field" maxlength="9" placeholder="999999999" required>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset>
                        <legend>Informações do serviço:</legend>
                        <div class="form-services">
                            <div class="form-group">
                                <label for="servico">Serviço</label>
                                <select name="servico" id="servico" class="input-field" required>
                                    <?php
                                    $query = "SELECT * FROM servicos ORDER BY nome ASC";
                                    
                                    $servicos = [];
                                    
                                    $result = mysqli_query($conn, $query);
                                    if(mysqli_num_rows($result)>0){
                                        while($linha = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                            $servicos[] = $linha;
                                        }

                                        echo '<option value="">Selecione o serviço</option>';
                                        foreach($servicos as $servico){
                                        echo '<option value="'.$servico['tag'].'">'.$servico['nome'].'</option>';
                                        }
                                    }else{
                                        echo '<option value="">Nenhum serviço encontrado</option>';
                                    }     
                                    ?>   
                                </select>
                            </div>
                            
                            <div class="option-list" id="campos">
                                <div class="form-group">
                                    <label for="tipo">Tipo de edificação:</label>
                                    <select name="tipo" id="tipo" class="input-field" required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="Casa">Casa</option>
                                    <option value="Apartamento">Apartamento</option>
                                    <option value="Comercial">Comercial</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="area">Área de Pintura (m²):</label>
                                    <input type="number" id="area" name="area" min="0" class="input-field" required>
                                </div>

                                <div class="form-group">
                                    <label for="inicio">Pretensão de iniciar em:</label>
                                    <select name="inicio" id="inicio" class="input-field" required>
                                    <option value="">Quando deseja iniciar os trabalhos?</option>
                                    <option value="ate5dias">Em até 5 dias</option>
                                    <option value="ate3semanas">1 a 3 semanas</option>
                                    <option value="ate3meses">1 a 3 meses</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="estado">Estado atual:</label>
                                    <select name="estado" id="estado" class="input-field" required>
                                    <option value="">Selecione o estado atual:</option>
                                    <option value="pintura">Pintura existente</option>
                                    <option value="manchas">Manchas de humidade</option>
                                    <option value="ceramica">Revestimento cerâmico</option>
                                    <option value="semrevestimento">Sem revestimento</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="acabamento">Tipo de acabamento:</label>
                                    <select name="acabamento" id="acabamento" class="input-field" required>
                                    <option value="">Selecione o acabamento desejado:</option>
                                    <option value="simples">Simples</option>
                                    <option value="texturizado">Texturizado</option>
                                    <option value="premium">Premium</option>
                                    </select>
                                </div>
                            </div>

                            <div class="option-list-col" id="extras">
                                <legend>Itens Adicionais:</legend>
                                <div class="form-group-row">
                                    <label for="teto">Pintura do teto?</label>
                                    <input type="checkbox" id="teto" class="input-check" name="adicionais[]" value="pintura do teto">
                                </div>
                                <div class="form-group-row">  
                                    <label for="rodape">Pintura do rodapé?</label>
                                    <input type="checkbox" id="rodape" class="input-check" name="adicionais[]" value="pintura do rodapé">
                                </div>
                                <div class="form-group-row">
                                    <label for="esquadrias">Pintura de portas/janelas?</label>
                                    <input type="checkbox" id="esquadrias" class="input-check" name="adicionais[]" value="pintura de portas/janelas">
                                </div>
                                <div class="form-group-row">
                                    <label for="papel">Remover papel de parede?</label>
                                    <input type="checkbox" id="papel" class="input-check" name="adicionais[]" value="remover papel de parede">
                                </div>
                                <div class="form-group-row">
                                    <label for="reparo">Reparo e tratamento de superfície?</label>
                                    <input type="checkbox" id="reparo" class="input-check" name="adicionais[]" value="reparo e tratamento de superfície">
                                </div>
                                <div class="form-group-row">
                                    <label for="isolamento">Camada de impermeabilização?</label>
                                    <input type="checkbox" id="isolamento" class="input-check" name="adicionais[]" value="camada de impermeabilização">
                                </div>
                                <div class="form-group-row">
                                    <label for="andaimes">Serviço em andaimes?</label>
                                    <input type="checkbox" id="andaimes" class="input-check" name="adicionais[]" value="serviço em andaimes">
                                </div>
                                <div class="form-group w100">
                                    <label for="obs">Informações extras:</label>
                                    <textarea name="obs" rows="3" id="obs" placeholder="Adicione detalhes e informações relevantes para melhor descrever o seu pedido."></textarea>
                                </div>
                            </div>                                        
                        </div>
                    </fieldset>
                    <div class="total">Valor Estimado <span class="small-info">(S/ IVA):</span> <span id="total"> €0.00 - €0.00</span></div>
                    <div class="button">
                        <input type="hidden" name="totalValue" id="totalValue" value="">
                        <input type="hidden" name="action" value="pedido">
                        <button type="submit" id="botaoPedido" onclick="passValue()" class="botaoPedido">Criar pedido <i class="fa-solid fa-brush"></i></button>
                    </div>     
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