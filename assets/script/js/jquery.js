$(document).ready(
        function(){
            //Ajouter la variable
            if(document.querySelector('.search-input')) searchInput = document.querySelector('.search-input input');
            
            $(document).click(function (e) {
                if (typeof searchInput !== 'undefined') {
                    if (window.innerWidth < 650 && searchInput.style.flexGrow !== '0'){
                        if (!$(e.target).closest('.search-input').length) {
                            hideSearch();
                        }
                    }
                }

                if (typeof sidebar !== 'undefined') {
                    if (sidebar.classList.contains('active')) {
                        if (!$(sidebar).is(e.target) && $(sidebar).has(e.target).length === 0) {
                            sidebar.classList.remove('active')
                        }
                    }
                    else{
                        if ($(e.target).closest('#sideBar-toggler').length) {
                            sidebar.classList.toggle('active')
                        }
                    }
                }
            });

            //Donner les propriétés nécessaires aux variables
            if (typeof searchInput !== 'undefined') {
                checkSize();


            //Ouverture de la saisie de recherche lorsque la fenêtre est inférieur à 650px

            $(".search-input").click(function(){
                document.querySelector('.search-input').setAttribute('class', this.getAttribute('class')+" dark")
                // if (window.innerWidth < 650){
                    if(searchInput.style.flexGrow == '0'){
                        inputToogle();
                    // }
                }
            })

            
            const previousWidth = window.innerWidth
            //Changement des propriétés sur changement de taille de la fenêtre
            $(window).resize(function(){
                // alert(window.innerWidth+"  "+window.innerWidth)
                currentWidth = window.screen.width
                if (previousWidth) {
                        if(previousWidth != currentWidth){
                            // alert(window.screen.width)
                            checkSize()
                            previousWidth = currentWidth
                        }
                    }
                })  
            }  
        }
    );
    function dateFormat(d){
        const date = new Date(d);
        const customFormat = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
        return customFormat // "2024-12-27"
    }
    function inputToogle() {
            if (searchInput.style.flexGrow !== '0')  hideSearch()
            else showSearch()
    }
    function checkSize() {
        if (window.innerWidth < 650) hideSearch() 
            else showSearch()  
    }
    function hideSearch() {
        $('.search-input input').css({'width':'0px','flex-grow': '0','padding':'0px','opacity':'0'});
        $('.search-input i').css({'margin-right':'0px'});
        $('.search-input').css({'width':'52px','flex-grow': '0'});
    }
    function showSearch() {
        $('.search-input input').css({'flex-grow': '1','padding':'0px 10px','opacity':'1'});
        $('.search-input i').css({'margin-right':'15px'});

        if (window.innerWidth < 450){ 
            $('.search-input').css({'width':'80vw','flex-grow': '0'});
        }
        else $('.search-input').css({'width':'auto','flex-grow': '1'});
        $('.search-input input').focus()
    }
    alerts = document.querySelectorAll('.alert')
    function message(duree) {
        window.setTimeout( function (){
            for (let index = 0; index < alerts.length; index++) {
                // $(alerts[index]).sli
            }
        },duree)
    }

    function selectForm(selectElement, value){
        materielJS = value;
        if (typeof selectElement !== "undefined") { 
            options = selectElement.options;
            for (let index = 0; index < options.length; index++) {
                if (options[index].value == value) {
                    options[index].setAttribute("selected", true);
                    break;
                }
            }
        }
    }