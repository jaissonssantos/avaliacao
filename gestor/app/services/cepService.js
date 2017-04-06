app.service('cepService', ['$rootScope', '$http', function($rootScope, $http) {
  this.searchCep = function(cep) {
    if(cep && cep.length < 7){
      return;
    }
    $rootScope.$broadcast('endereco:cep', 'loading');
    $http({
      url: 'http://api.postmon.com.br/v1/cep/' + cep,
    }).success(function(response) {
      $rootScope.$broadcast('endereco:cep', 'loaded');
      var endereco = {
        cep: response.cep,
        estado: response.estado,
        cidade: response.cidade,
        bairro: response.bairro,
        logradouro: response.logradouro
      };
      $rootScope.$broadcast('endereco', endereco);
    }).error(function(error) {
      $rootScope.$broadcast('endereco:cep', 'error');
    });
  };
}]);
