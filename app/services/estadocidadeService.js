angular.module('app').service('estadocidadeService', ['$rootScope', '$http', function($rootScope, $http) {

  var self = this;

  this.loadEstados = function(){
    $http.get('/controller/marketplace/estadocidade/getestado')
    .then(function(response){
        $rootScope.$broadcast("estados", response.data);
    });
  };

  this.loadCidades = function(estado){
    $http.post('/controller/marketplace/estadocidade/getcidade', {estado: estado})
    .then(function(response){
        $rootScope.$broadcast("cidades", response.data);
    });
  };

}]);
