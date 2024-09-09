window.onload = setTimeout(() => alert('Bem vindo!'), 5000)

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

        if(document.body.scrollTop > 50 || document.documentElement.scrollTop > 50){

            navHeader.style.boxShadow   = "0 2px 6px rgba(0, 0, 0, 0.2)";
            navHeader.style.height      = "70px";
            navHeader.style.lineHeight  = "70px";
        }else{
            navHeader.style.boxShadow   = "none";
            navHeader.style.height      = "90px";
            navHeader.style.lineHeight  = "90px";
        }
    }

/* ----- TYPING EFFECT ----- */
    var typingEffect = new Typed('#typed',{
        stringsElement: '.typedText',
        loop: true,
        typeSpeed : 65,
        backSpeed : 40,
        backDelay : 2500
    });

/* ----- SLIDESHOW ----- */
    let slideIndex = 0;
    let slides = document.getElementsByClassName("slide");
    let currentOpacity = 0;
    let interval;

    function showSlides() {
        // Hide all slides initially
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.opacity = 0;
        }
        
        // Increment slideIndex to show the next slide
        slideIndex++;
        
        // If slideIndex exceeds the number of slides, reset it to 1
        if (slideIndex > slides.length) {
            slideIndex = 1;
        }
        
        // Start fading in the current slide
        fadeInSlide(slides[slideIndex - 1]);
    }

    function fadeInSlide(slide) {
        currentOpacity = 0; // Reset opacity
        clearInterval(interval);
        
        // Gradually increase the opacity
        interval = setInterval(function() {
            if (currentOpacity >= 1) {
                clearInterval(interval); // Stop once fully visible
            }
            slide.style.opacity = currentOpacity;
            currentOpacity += 0.05; // Increase opacity by 0.02 every 50ms
        }, 50);
    }

    // Initial call to start the slideshow
    showSlides();

    // Change slide every 5 seconds
    setInterval(showSlides, 5000);

/* ----- TAB EFFECT HIDE/SHOW ----- */
    var tabClientes = document.getElementById('tabClientes');
    var tabProf = document.getElementById('tabProf');
    var clientes = document.getElementById('clientes');
    var profissionais = document.getElementById('profissionais');

    function mostrarClientes() {
        clientes.style.display = 'block';
        profissionais.style.display = 'none';
        tabClientes.classList.add('active');
        tabProf.classList.remove('active');
    }

    function mostrarProfissionais() {
        clientes.style.display = 'none';
        profissionais.style.display = 'block';
        tabClientes.classList.remove('active');
        tabProf.classList.add('active');
    }

    tabClientes.addEventListener('click', mostrarClientes);
    tabProf.addEventListener('click', mostrarProfissionais);


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

/*------GALLERY SLIDER------*/
    document.querySelectorAll('.gallery-box img').forEach(image => {
        // Função para abrir modal com imagem expandida
        image.onclick = () =>{
            document.querySelector('.image-box').style.display = 'block';
            document.querySelector('.image-box img').src = image.getAttribute('src');
        }
    });
    
    // Função fechar modal
    document.querySelector('.image-box span').onclick = () =>{
        document.querySelector('.image-box').style.display = 'none';
    }

/*------MAP SCRIPT------*/
    // Inicializando o mapa com Leaflet e OpenStreetMap
    const map = L.map('map').setView([38.733556, -9.14114], 16); // MasterD Lisboa

    // Camada do mapa usando OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // Adicionando marcador no ponto de destino (MasterD Lisboa)
    const destination = [38.733556, -9.14114]; // Coordenadas da MasterD Lisboa
    const marker = L.marker(destination).addTo(map).bindPopup("MasterD Lisboa").openPopup();

    // Função para obter as direções
    function getDirections() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const currentLocation = [position.coords.latitude, position.coords.longitude];

                // Chamada para a API do OpenRouteService
                const url = `https://api.openrouteservice.org/v2/directions/driving-car?api_key=5b3ce3597851110001cf6248a0add5b4d4e74aff89ac88018d1a1ec6&start=${currentLocation[1]},${currentLocation[0]}&end=${destination[1]},${destination[0]}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        const route = data.routes[0].geometry.coordinates.map(coord => [coord[1], coord[0]]);
                        L.polyline(route, { color: 'blue' }).addTo(map);
                    })
                    .catch(error => console.error('Erro ao buscar direções:', error));
            }, 
            () => alert("Erro ao obter a localização."));
        } else {
            alert("Navegador não suporta Geolocalização.");
        }
    }

/*------RSS FEED SHOW/HIDE------*/
    const btnFeed = document.getElementById('rss-button'),
        feed = document.getElementById('rss')
    
        btnFeed.addEventListener('click',() =>{
            feed.classList.toggle('view-rss');
    });

    const sourceRSS = 'https://api.rss2json.com/v1/api.json?rss_url=https://feeds.feedburner.com/publicoRSS'
    const loadRSS = (feedSrc) => {
        const src = feedSrc
        const xhttp = new XMLHttpRequest();
        xhttp.responseType = 'json'
        xhttp.open("get", src, true);
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                showRSS(this)
            }
        };
        xhttp.send();
    }

    function showRSS(json) {
        const ul = document.querySelector('.rss-list'),
              objJson = json.response;
        let resultado = '';
        //console.log("Estrutura JSON retornada:", objJson);


        objJson.items.forEach((item) => {
            //console.log(`Item ${index + 1}:`, item);
            let published = item.pubDate;
            let link = item.link;
            let title = item.title;
            let summary = item.description;
            let category = item.categories.length ? item.categories[0] : 'Sem Categoria';
            
            //console.log(`Título: ${title}, Categoria: ${category}, Publicado em: ${published}`);

            // Formatar a data
            let date = new Date(published);
            let day = date.getDate();
            let month = date.getMonth() + 1;
            let year = date.getFullYear();
            let hour = date.getHours();
            let minute = date.getMinutes();
    
            resultado += `
                <li class="rss-item">
                    <h3 class="item-title">
                        <a href="${link}" class="rss-link" target="_blank">
                            ${title}
                        </a>
                    </h3>
                    <div class="item-info">
                        <p class="rss-category"><strong>Category:</strong> ${category}</p>
                        <p class="rss-date"><strong>Date:</strong> ${day}/${month}/${year} - ${hour}:${minute}</p>
                    </div>
                    <div class="item-description">
                        <p>${summary}</p>
                    </div>
                </li>
            `;
        });

    ul.innerHTML = resultado;
    };

    window.onload = loadRSS(sourceRSS)