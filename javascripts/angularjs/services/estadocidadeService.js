angular.module('app').service('estadocidadeService', ['$rootScope', '$http', function($rootScope, $http) {

  var self = this;

  this.loadEstados = function(){
    $http.get('/controller/guest/estadocidade/getestado')
    .then(function(response){
        $rootScope.$broadcast("estados", response.data.results);
    });
  };

  this.loadCidades = function(estado){
    $http.post('/controller/guest/estadocidade/getcidade', {estado: estado})
    .then(function(response){
        $rootScope.$broadcast("cidades", response.data.results);
    });
  };

}]);
