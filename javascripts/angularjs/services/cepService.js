angular.module('app').service('cepService', ['$rootScope', '$http', function($rootScope, $http) {
  this.searchCep = function(cep) {
    if(cep && cep.length < 7){
      return;
    }
    $rootScope.$broadcast('endereco:cep', 'loading');
    $http({url: 'http://api.postmon.com.br/v1/cep/' + cep})
      .then(function(response) {
        $rootScope.$broadcast('endereco:cep', 'loaded');
        var endereco = {
          cep: response.data.cep,
          estado: response.data.estado,
          cidade: response.data.cidade,
          bairro: response.data.bairro,
          logradouro: response.data.logradouro
        };
        $rootScope.$broadcast('endereco', endereco);
      },
      function(response){
          $rootScope.$broadcast('endereco:cep', 'error');
      });
  };
}]);
