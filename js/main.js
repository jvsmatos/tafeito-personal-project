//window.onload = setTimeout(() => alert('Bem vindo!'), 5000)

/* ----- NAVIGATION RESPONSIVE MENU ----- */
    function menuFunction(){
        var menuBtn = document.getElementById("myMenu");

        if(menuBtn.className === "nav-menu"){
            menuBtn.className += " responsive";
        }else{
            menuBtn.className = "nav-menu";
        }

        var menuBtn2 = document.getElementById("mobile-menu");

        if(menuBtn2.className === "fa-solid fa-bars"){
            menuBtn2.className = "fa-solid fa-xmark";
        }else{
            menuBtn2.className = "fa-solid fa-bars";
        }
    }

/* ----- SHADOW ON NAVIGATION WHEN SCROLLING ----- */
    window.onscroll = function(){
        headerShadow();
    }

    function headerShadow(){
        const navHeader = document.getElementById("header");
        const navMenu   = document.getElementsByClassName("nav-menu")[0];

        if(document.body.scrollTop > 50 || document.documentElement.scrollTop > 50){

            navHeader.style.boxShadow   = "0 2px 6px rgba(0, 0, 0, 0.2)";
            navHeader.style.height      = "70px";
            navHeader.style.lineHeight  = "70px";
           // navMenu.style.top           = "70px";
           // navMenu.style.height        = "92vh";
        }
    }

/* ----- PRICE SIMULATOR ----- */
    // Valores mínimos e máximos para cálculo
    /* const valoresServicos = {
        'pintura-interna':      { valorMinimo: 9, valorMaximo: 13, valorMinimoServico: 250 },
        'pintura-externa':      { valorMinimo: 12, valorMaximo: 18, valorMinimoServico: 350 },
        'pequenos-retoques':    { valorMinimo: 13, valorMaximo: 16, valorMinimoServico: 175 }
    }; */

    let valoresServicos = {}; // Inicializa como vazio

    // Função para carregar os serviços dinamicamente via AJAX
    function carregarValoresServicos() {
        return $.ajax({
            url: 'api.php', 
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                //console.log('Dados carregados:', data);
                valoresServicos = data;
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar os dados:', error);
                console.error('Status:', status);
                console.error('Resposta do servidor:', xhr.responseText);
            }
        });
    }

    carregarValoresServicos().then(function() {
        //console.log('Valores dos serviços:', valoresServicos);
    
    }).catch(function(error) {
        //console.error('Erro ao carregar os dados:', error);
    });


    //Maping dos selects
    const estadoMap = {
        'pintura': 1.0,
        'manchas': 1.3,
        'ceramica': 1.5,
        'semrevestimento': 1.22
    };
    
    const acabamentoMap = {
        'simples': 1.0,
        'texturizado': 1.3,
        'premium': 1.65
    };
    
    const inicioMap = {
        'ate5dias': 1.35,
        'ate3semanas': 1.18,
        'ate3meses': 1.08
    };

    // Preços fixos para serviços adicionais
    const precosAdicionais = {
        teto: 170,
        rodape: 100,
        esquadrias: 100,
        papel: 200,
        reparo: 200,
        isolamento: 150,
        andaimes: 3000
    };

    // Referências aos elementos do DOM
    const servicoField      = document.getElementById('servico');
    const tipoField         = document.getElementById('tipo');
    const areaField         = document.getElementById('area');
    const estadoField       = document.getElementById('estado');
    const acabamentoField   = document.getElementById('acabamento');
    const inicioField       = document.getElementById('inicio');
    const totalField        = document.getElementById('total');
    const campos            = document.getElementById('campos');
    const extras            = document.getElementById('extras');
    const totalSection      = document.querySelector('.total');
    const botaoPedido       = document.getElementById('botaoPedido');
    const nomeCliente       = document.getElementById('nome');
    const apelidoCliente    = document.getElementById('apelido');
    const telCliente        = document.getElementById('telemovel');

    // Função para calcular o total estimado
    function calcularTotal() {
        const area              = parseFloat(areaField.value) || 0;
        const estadoValue       = parseFloat(estadoMap[estadoField.value]) || 1;
        const acabamentoValue   = acabamentoMap[acabamentoField.value] || 1;
        const inicioValue       = inicioMap[inicioField.value] || 1;

        // Identificar o tipo de serviço selecionado
        const tipoServico   = servicoField.value;
        const valores       = valoresServicos[tipoServico];

        let precoMinimo = valores.valorMinimo * area;
        let precoMaximo = valores.valorMaximo * area;

        precoMinimo *= estadoValue * acabamentoValue * inicioValue;
        precoMaximo *= estadoValue * acabamentoValue * inicioValue;

        // Aplicar valor entre o calculado e o mínimo para nunca ficar menor que o mínimo
        precoMinimo = Math.max(precoMinimo, valores.valorMinimoServico);
        precoMaximo = Math.max(precoMaximo, valores.valorMinimoServico);

        // Acrescentar valores extras para cada item do looping que é verificado com checked
        const selectedExtras = document.querySelectorAll('.input-check:checked');
        selectedExtras.forEach(extra => {
            const key = extra.id; // Obter o id do checkbox, que corresponde à chave do preço fixo
            precoMinimo += precosAdicionais[key] || 0;
            precoMaximo += precosAdicionais[key] || 0;
        });

        totalField.textContent = `€${precoMinimo.toFixed(2)} - €${precoMaximo.toFixed(2)}`;
    }

    // Função para exibir todos os campos após a seleção do serviço
    function showFields() {
        campos.style.display        = 'flex';
        extras.style.display        = 'flex';
        totalSection.style.display  = 'block';
    }

    // Função para ocultar todos os campos
    function hideFields(){
        campos.style.display        = 'none';
        extras.style.display        = 'none';
        totalSection.style.display  = 'none';
    }

    // Função para validar campos e aplicar classes
    function validarCampos() {
        let allFieldsValid = true;

        function validarCampo(campo, valid) {
            if (valid) {
                campo.classList.add('ok');
                campo.classList.remove('error');
            } else {
                campo.classList.add('error');
                campo.classList.remove('ok');
                allFieldsValid = false;
            }
        }

        // Função para validar número de telefone no formato XXXXXXXXX
        function validarTelefone(telefone) {
            const telefoneRegex = /^\d{3}\d{3}\d{3}$/;
            return telefoneRegex.test(telefone);
        }

        // Validar todos os campos obrigatórios
        validarCampo(nomeCliente, nomeCliente.value !== '');
        validarCampo(apelidoCliente, apelidoCliente.value !== '');
        validarCampo(telCliente, telCliente.value !== '' && validarTelefone(telCliente.value));
        validarCampo(tipoField, tipoField.value !== '');
        validarCampo(servicoField, servicoField.value !== '');
        validarCampo(areaField, areaField.value !== '' && !isNaN(areaField.value) && parseFloat(areaField.value) > 0);
        validarCampo(estadoField, estadoField.value !== '');
        validarCampo(acabamentoField, acabamentoField.value !== '');
        validarCampo(inicioField, inicioField.value !== '');

        return allFieldsValid;
    }

    // Função para solicitar um profissional
    function createOrder() {
        //Chamada da função para verificar se os campos estão válidos antes de submeter o pedido
        const camposValidos = validarCampos();

        //Caso os campos estejam válidos o pedido será submetido, caso contrário alertará o usuário para preencher/corrigir os campos obrigatórios
        if (camposValidos) {
            alert('Pedido submetido com sucesso!\n\nAssim que o seu pedido for analisado por algum profissional o mesmo entrará em contato consigo.');
        } else {
            alert('Por favor, preencha todos os campos obrigatórios corretamente.');
        }
    }

    // Adicionar event listeners para atualizar o total dos respectivos campos sempre que houver alterações
    const camposParaCalculo = [
        areaField,
        estadoField,
        acabamentoField,
        inicioField
    ];

    camposParaCalculo.forEach(campo => {
        campo.addEventListener('input', () => {
            calcularTotal();
        });
    });

    // Event listener para mostrar campos e recalcular total ao mudar o serviço
    servicoField.addEventListener('change', () => {
        
        if (servicoField.value === "") { // Se não for selecionado nenhum serviço irá esconder os campos
            hideFields();
        } else {
            showFields();
            resetForm();
            //calcularTotal(); // Recalcular total quando o serviço muda
        }
    });

    // Event listener para recalcular total ao selecionar opções adicionais
    document.querySelectorAll('.input-check').forEach(check => {
        check.addEventListener('change', () => {
            calcularTotal();
        });
    });

    // Função para resetar o formulário ao trocar de serviço
    function resetForm() {
        // Limpar todos os campos de input
        areaField.value = '';
        estadoField.value = '';
        acabamentoField.value = '';
        inicioField.value = '';

        // Desmarcar todas as checkboxes dentro do elemento 'extras'
        const checkboxesExtras = extras.querySelectorAll('.input-check');
        checkboxesExtras.forEach(checkbox => {
            checkbox.checked = false;
        });

        // Limpar total estimado
        totalField.textContent = `€0.00 - €0.00`;

        // Remover classes de validação
        document.querySelectorAll('.ok, .error').forEach(campo => {
            campo.classList.remove('ok', 'error');
        });
    }

/* ------- FORMATAR MOEDA -------*/
    function formatarMoeda() {
        var elemento = document.getElementById('valor');
        var valor = elemento.value;
        
        // Remove qualquer outro caractere que não seja número ou vírgula/ponto
        valor = valor.replace(/[\D]+/g, '');
    
        if (isNaN(valor) || valor === '') {
            return;  // Sai da função se o valor não for um número válido
        }
        
        // Separa os últimos dois dígitos com um ponto (para os centavos)
        valor = valor.replace(/([0-9]{2})$/, ".$1");

        // Remove pontos extras que são usados como separadores de milhar
        valor = valor.replace(/\./g, '');
    
        // Coloca o ponto como separador decimal
        valor = valor.replace(/([0-9]{2})$/, ".$1");
        
        elemento.value = valor;
    }

/* ------- FUNÇÕES INTERNAS ------ */
    // Passar o valor simulado como input no form
    function passValue(){
        var totalValue = document.getElementById('total').innerText;
        document.getElementById('totalValue').value = totalValue;
        console.log(totalValue);
    };

    // Retorno da aceitação de proposta pelo cliente
    function acceptProposal(element) {
        var proposalId  = $(element).attr('proposal-id');
        var orderId     = $(element).attr('order-id');

        $.ajax({
            url: 'auth2.php', // Arquivo PHP onde a função será executada
            type: 'POST',
            data: { action: 'accept', proposal_id: proposalId, order_id: orderId },
            success: function(response) {
                location.reload();
            }
        });

        //console.log("Proposta aceita com ID " + proposalId + " e Pedido com ID " + orderId);
    };

    // Retorno da rejeição de proposta pelo cliente
    function rejectProposal(element) {
        var proposalId  = $(element).attr('proposal-id');
        
        $.ajax({
            url: 'auth2.php', // Arquivo PHP onde a função será executada
            type: 'POST',
            data: { action: 'reject', proposal_id: proposalId },
            success: function(response) {
                location.reload();
            }
        });

        //console.log("Proposta aceita com ID " + proposalId);
    };

    // Retorno do cancelamento de pedido pelo cliente
    function cancelOrder(element) {
        var orderId  = $(element).attr('order-id');
        
        $.ajax({
            url: 'auth2.php', // Arquivo PHP onde a função será executada
            type: 'POST',
            data: { action: 'cancel', order_id: orderId },
            success: function(response) {
                location.reload();
            }
        });

        //console.log("Proposta aceita com ID " + proposalId);
    };

    // Retorno da remoção de usuário pelo adm
    function deleteUser(element) {
        var userId  = $(element).attr('user-id');
        
        $.ajax({
            url: 'auth2.php', // Arquivo PHP onde a função será executada
            type: 'POST',
            data: { action: 'delete', user_id: userId },
            /* success: function(response) {
                location.reload();
            } */
        });
    };

    // Retorno da remoção de usuário pelo adm
    function deleteService(element) {
        var serviceId  = $(element).attr('servico-id');
        
        $.ajax({
            url: 'auth2.php', // Arquivo PHP onde a função será executada
            type: 'POST',
            data: { action: 'delete', servico_id: serviceId },
            /* success: function(response) {
                location.reload();
            } */
        });
    };

    function fadeOutAlert() {
        const alertMessage = document.getElementById('alerta');
        //console.log("Alerta identificado");
        if (alertMessage) {
            setTimeout(() => {
                alertMessage.style.transition = "opacity 0.5s ease";
                alertMessage.style.opacity = "0";
                setTimeout(() => alertMessage.remove(), 500);
                //console.log("Alerta removido");
            }, 3500); // Tempo de exibição (3 segundos)
        }
    }

  