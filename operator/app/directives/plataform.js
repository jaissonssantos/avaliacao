'use strict'; 

app
    .directive("activemenubar", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click toucstart", function() {
                    /*active body*/
                    $('body').toggleClass('menubar-visible');
                });
            }
        };
    })

    .directive("activeovermenubar", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("mouseenter", function() {
                    var offcanvasVisible = $('body').hasClass('offcanvas-left-expanded');
                    var menubarExpanded = $('#menubar').data('expanded');

                    if ((offcanvasVisible === false) && menubarExpanded !== true) {
                        // Add listener to close the menubar
                        $('#content').one('mouseover', function (e) {
                            $('body').removeClass('menubar-visible');
                            $('#menubar').data('expanded', false);
                        });
                        /*active body*/
                        $('body').toggleClass('menubar-visible');
                        $('#menubar').data('expanded', true);
                    }
                });
            }
        };
    })

    .directive("activeoffcanvasright", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click toucstart", function() {
                    /*active offcanvas right*/
                    $('.offcanvas').css('background-color', 'rgba(0,0,0,0.4)');
                    $('.offcanvas').css('right', '0');
                    $('#offcanvas-search').addClass('active');
                    $('#offcanvas-search').css('transform', 'translate(0px, 0px)');
                    var nanoh = $('#offcanvas-search .nano .nano-content').height();
                    $('#offcanvas-search .nano').css('height', nanoh);
                    /*active nano scroller plugin*/
                    $('.nano').nanoScroller();
                });
            }
        };
    })

    .directive("closeoffcanvasright", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click toucstart", function() {
                    /*active offcanvas right*/
                    $('.offcanvas').css('right', 'auto');
                    $('#offcanvas-search').css('transform', 'inherit');
                    $('#offcanvas-search').removeClass('active');
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
                    $('.tab-pane').removeClass('active');
                    $(tab).addClass('active');
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

    .directive("activefilterscard", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click toucstart", function() {
                    /*elem id show*/
                    var card = elem.attr('rel');
                    /*show form*/
                    var hdd = $(card).hasClass('hidden');
                    if( hdd ){
                        $(card).fadeIn('fast', 'swing' );
                        $(card).removeClass('hidden');
                        /*change label*/
                        elem.parent().parent().find('header').html('OCULTAR FILTROS');
                    }else{
                        // $(card).fadeOut('fast', 'swing' );
                        // $(card).addClass('hidden');
                        $(card).slideUp("slow", function(){
                            $(this).delay(100).addClass('hidden').delay(100).fadeIn('slow');
                        });
                        /*change label*/
                        elem.parent().parent().find('header').html('EXIBIR FILTROS');
                    }
                    /*change icon*/
                    var icon=elem.find('i').hasClass('fa-angle-down');
                    if( icon ){
                        elem.find('i').removeClass('fa-angle-down');
                        elem.find('i').addClass('fa-angle-up');
                    }else{
                        elem.find('i').addClass('fa-angle-down');
                        elem.find('i').removeClass('fa-angle-up');
                    }
                });
            }
        };
    })

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

    .directive("navtabs", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click", function() {
                    /*inactive others itens*/
                    var nav = elem.attr('data-rel');
                    $('.tab-pane').removeClass('in active');
                    $(nav).addClass('in active');
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
    })