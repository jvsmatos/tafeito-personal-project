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