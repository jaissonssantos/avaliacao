'use strict'; 

app
    .directive("activeweek", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click", function() {
                    /*inactive others itens*/
                    /*verificated current item*/
                    if( ! elem.hasClass('desabilitado') ){
                        elem.parent().find('li').removeClass('selecionado');
                        elem.parent().find('li').removeClass('selecionadonotday');
                        elem.parent().find('li').removeClass('selecionadosunday');
                        /*active item*/
                        elem.addClass("selecionado");
                    }
                });
            }
        };
    })

    .directive("activetimeweek", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click", function() {
                    /*inactive others itens*/
                    elem.parent().find('li').removeClass('selecionado');
                    /*active item*/
                    elem.addClass("selecionado");
                });
            }
        };
    });