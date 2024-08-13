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