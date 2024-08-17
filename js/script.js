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
