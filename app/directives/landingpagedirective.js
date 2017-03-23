'use strict'; 

angular.module('app').directive("lpnavmenu", function() {
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
    });