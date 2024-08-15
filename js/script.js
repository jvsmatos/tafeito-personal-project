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