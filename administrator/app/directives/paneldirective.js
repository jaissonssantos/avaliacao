'use strict'; 

app
    .directive("activetabpanel", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click", function() {
                    /*inactive others itens*/
                    var tabpane = elem.attr('data-rel');
                    $('.tab-panel').removeClass('in active');
                    $(tabpane).addClass('in active');
                    /*remove all li tab selected*/
                    elem.parent().parent().find('li').removeClass('active');
                    /*selected item tab*/
                    elem.parent('li').addClass('active');
                });
            }
        };
    })


    .directive("activetabscheduling", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click", function() {
                    /*inactive others itens*/
                    var tabpane = elem.attr('data-rel');
                    $('.tab-agendamento').removeClass('in active');
                    $(tabpane).removeClass('fade');
                    $(tabpane).addClass('active');
                    /*remove all a tab selected*/
                    elem.parent().find('a').removeClass('active');
                    /*selected item tab*/
                    elem.addClass('active');
                });
            }
        };
    })