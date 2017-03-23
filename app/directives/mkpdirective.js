'use strict'; 

angular.module('app').directive("actmkpmenumobile", function() {
        return {
            link: function(scope, elem, attrs) {
                elem.on("click", function() {

                    var sidebar     = $jq('[data-sidebar]');
                    var button      = $jq('[data-sidebar-button]');
                    var overlay     = $jq('[data-sidebar-overlay]');

                    // add height to content area
                    overlay.parent().css('min-height', 'inherit');

                    // hide sidebar on load
                    sidebar.css('margin-left', sidebar.width() * -1 + 'px');

                    sidebar.show(0, function() {
                        sidebar.css('transition', 'all 0.5s ease');
                    });

                    // show sidebar and overlay
                    function showSidebar() {
                        sidebar.css('margin-left', '0');

                        overlay.show(0, function() {
                            overlay.fadeTo('500', 0.5);
                        });   
                    }

                    // hide sidebar and overlay
                    function hideSidebar() {
                        sidebar.css('margin-left', sidebar.width() * -1 + 'px');

                        overlay.fadeTo('500', 0, function() {
                            overlay.hide();
                        });
                    }

                    // hide sidebar on overlay click
                    overlay.click(function() {
                        hideSidebar();
                    });

                    if (overlay.is(':visible')) {
                        hideSidebar();
                    } else {
                        showSidebar();
                    }


                });
            }
        };
    });
    