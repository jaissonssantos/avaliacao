'use strict'; 

angular.module('app').directive("activeweek", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click", function() {
                    /*inactive others itens*/
                    /*verificated current item*/
                    if( elem.hasClass('desabilitado') ){
                        // elem.next('li').addClass("selecionado");
                        // current.addClass('selecionado');
                    }else{
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
    })

    .directive("scroolstep", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click", function() {
                    var target = elem.attr('rel');
                    if( jQuery(target).length ){
                        $jq('html, body').animate({
                            scrollTop: $jq(target).offset().top
                        }, 1000);
                    }/*end if*/
                });
            }
        };
    })

    .directive("actnextweek", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click toucstart", function() {
                    var $totaldays = $jq('ul.dias li').length;
                    if( scope.countWeeknext >= 0 && scope.countWeeknext <= 3)
                        scope.countWeeknext++;
                    else if(scope.countWeeknext <= 3)
                        scope.countWeeknext = 0;

                    if( scope.positioncurrent == 0 ) 
                         scope.positioncurrent = 6 
                     else 
                         scope.positioncurrent = scope.positioncurrent; 

                     if( (scope.positioncurrent +6) <= $totaldays ){
                         $jq('.dias li').each(function(i){
                             if( i <= scope.positioncurrent ){
                                 $jq(this).delay(100).fadeOut(180);
                             }
                         });
                     }

                     scope.positioncurrent += 6;

                    if( scope.positioncurrent > 6 )
                        $jq('.seta-esq').removeClass('desabilitado');

                    if( scope.positioncurrent > $totaldays )
                     $jq(this).addClass('desabilitado');

                    switch( scope.countWeeknext ){
                     case 0:
                         scope.changeDescWeek('esta semana');
                     break;
                     case 1:
                         scope.changeDescWeek('semana que vem');
                     break;
                     case 2:
                         scope.changeDescWeek('em 2 semanas');
                     break;
                     case 3:
                         scope.changeDescWeek('em 3 semanas');
                     break;
                     case 4:
                         scope.changeDescWeek('em 4 semanas');
                     break;
                    }

                });
            }
        };
    })

    .directive("actprevweek", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click toucstart", function() {
                    var $totaldays = $jq('ul.dias li').length;
                    var $aux = 0;

                    if( scope.countWeeknext > 0  && scope.countWeeknext <= 4)
                        scope.countWeeknext--;
                    else if(scope.countWeeknext <= 4)
                        scope.countWeeknext = 0;

                    if( scope.positioncurrent == 0 ) 
                            scope.positioncurrent = 6 
                        else 
                            scope.positioncurrent = scope.positioncurrent; 

                    if( (scope.positioncurrent -6) <= $totaldays && (scope.positioncurrent -6) >= 0){

                        scope.positionprev = scope.positioncurrent - 6;

                        $aux = scope.positionprev - 6;

                        if( $aux >= 0 ){
                            for( var i = $aux; i <= scope.positionprev; i++ ){
                                $jq('ul.dias li:nth-child('+(i+1)+')').fadeIn(180);
                            }
                        }
                    }

                    scope.positioncurrent -= 6;

                    if( scope.positioncurrent <= 6 )
                        $jq('.seta-esq').addClass('desabilitado')
                        $jq('.seta-dir').removeClass('desabilitado');

                    switch( scope.countWeeknext ){
                        case 0:
                            scope.changeDescWeek('esta semana');
                        break;
                        case 1:
                            scope.changeDescWeek('semana que vem');
                        break;
                        case 2:
                            scope.changeDescWeek('em 2 semanas');
                        break;
                        case 3:
                            scope.changeDescWeek('em 3 semanas');
                        break;
                        case 4:
                            scope.changeDescWeek('em 4 semanas');
                        break;
                    }

                });
            }
        };
    });