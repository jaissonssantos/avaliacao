'use strict'; 

angular.module('app').directive("activetabpanel", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click", function() {
                    /*inactive others itens*/
                    var tabpane = elem.attr('data-rel');
                    $jq('.tab-panel').removeClass('in active');
                    $jq(tabpane).addClass('in active');
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
                    $jq('.tab-agendamento').removeClass('in active');
                    $jq(tabpane).removeClass('fade');
                    $jq(tabpane).addClass('active');
                    /*remove all a tab selected*/
                    elem.parent().find('a').removeClass('active');
                    /*selected item tab*/
                    elem.addClass('active');
                });
            }
        };
    })