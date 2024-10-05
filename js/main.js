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
    const valoresServicos = {
        'pintura-interna':      { valorMinimo: 9, valorMaximo: 13, valorMinimoServico: 150 },
        'pintura-externa':      { valorMinimo: 12, valorMaximo: 18, valorMinimoServico: 250 },
        'pequenos-retoques':    { valorMinimo: 13, valorMaximo: 16, valorMinimoServico: 100 }
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
        const estadoValue       = parseFloat(estadoField.value) || 1;
        const acabamentoValue   = parseFloat(acabamentoField.value) || 1;
        const inicioValue       = parseFloat(inicioField.value) || 1;

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

/* ------- NUMERO PARA EURO -------*/
    function numeroParaEuro() {
        let numero = parseFloat(document.getElementById("valor").value);
        
        if (!isNaN(numero)) { // Verifica se é um número válido
            let valorFormatado = numero.toFixed(2).replace('.', ',');
            document.getElementById("valor").value = valorFormatado; // Atualiza o campo com o valor formatado
        } else {
            alert("Por favor, insira um valor numérico válido.");
        }
    }