app.service('estadocidadeService', ['$rootScope', '$http', function($rootScope, $http) {

  var self = this;

  this.loadEstados = function(){
    $http.get('/controller/gestor/estadocidade/getestado')
    .success(function(response){
        $rootScope.$broadcast("estados", response);
    });
  };

  this.loadCidades = function(estado){
    $http.post('/controller/gestor/estadocidade/getcidade', {estado: estado})
    .success(function(response){
        $rootScope.$broadcast("cidades", response);
    });
  };

}]);
