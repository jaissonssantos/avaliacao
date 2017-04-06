'use strict'; 

app
    .directive("activemenubar", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click toucstart", function() {
                    /*active body*/
                    $jq('body').toggleClass('menubar-visible');
                });
            }
        };
    })

    .directive("activeovermenubar", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("mouseenter", function() {
                    /*active body*/
                    $jq('body').toggleClass('menubar-visible');
                });
            }
        };
    })

    .directive("activeoffcanvasright", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click toucstart", function() {
                    /*active offcanvas right*/
                    $jq('.offcanvas').css('right', '0');
                    $jq('#offcanvas-search').addClass('active');
                    $jq('#offcanvas-search').css('transform', 'translate(0px, 0px)');
                    var nanoh = $jq('#offcanvas-search .nano .nano-content').height();
                    $jq('#offcanvas-search .nano').css('height', nanoh);
                    /*active nano scroller plugin*/
                    $jq('.nano').nanoScroller();
                });
            }
        };
    })

    .directive("closeoffcanvasright", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click toucstart", function() {
                    /*active offcanvas right*/
                    $jq('.offcanvas').css('right', 'auto');
                    $jq('#offcanvas-search').css('transform', 'inherit');
                    $jq('#offcanvas-search').removeClass('active');
                });
            }
        };
    })

    .directive("tablerowlink", function( $window ) {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click toucstart", function() {
                    /*active click row table*/
                    var link = elem.attr('data-rel');
                    $window.location.href = link;
                });
            }
        };
    })

    .directive("tabtablelist", function( $window ) {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click toucstart", function() {
                    /*active click row table*/
                    var tab = elem.attr('rel');
                    $jq('.tab-pane').removeClass('active');
                    $jq(tab).addClass('active');
                    /*remove all a tab selected*/
                    elem.parent().parent().find('li').removeClass('active');
                    /*selected item tab*/
                    elem.parent().addClass('active');
                });
            }
        };
    })

    .directive("inputfilegetname", function( $window ) {
        return {
            link: function(scope, elem, attrs) {
                elem.on("change", function() {
                    var input = elem[0].files[0].name;
                    var faux = elem.parent().parent();
                    faux.find('input').attr('value', input);
                });
            }
        };
    })