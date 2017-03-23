'use strict';

angular.module('app').directive('googleEnderecos', ['$location', function($location){
    return {
        link: function(scope, elem, attrs){
            elem.on('click', function(){
                var options = {
                    types: [],
                    componentRestrictions: {}
                };
                var autocomplete = new google.maps.places.Autocomplete($jq("#buscaLocalizacao")[0], options);
                google.maps.event.addListener(autocomplete, 'place_changed', function() {
                    var enderecos = autocomplete.getPlace();
                    var servico = $jq('#buscaServico').val();
                    scope.busca.servico = servico;
                    if(enderecos.formatted_address == undefined){
                        scope.busca.localizacao = $jq('#buscaLocalizacao').val();
                    }else{
                        scope.busca.localizacao = enderecos.formatted_address;
                    }
                    scope.$apply();
                    $location.url('/busca?services='+scope.busca.servico+'&geo='+scope.busca.localizacao);
                });
            });
        }
    }
}]);