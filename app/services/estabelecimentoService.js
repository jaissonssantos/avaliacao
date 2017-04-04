angular.module('app').service('estabelecimentoService', ['$rootScope', '$timeout', '$http' , function ($rootScope, $timeout, $http) {
  var self = this

  this.set = function (estabelecimento) {
    self.estabelecimento = estabelecimento
    $rootScope.$broadcast('estabelecimento', estabelecimento)
  }

  this.save = function(){
    $http.post('/controller/marketplace/estabelecimento/create', self.estabelecimento)
    .then(function(response){
      $rootScope.$broadcast("estabelecimento:save", response.data);
    }, function(response){
      $rootScope.$broadcast("estabelecimento:save", response.data);
    });
  };

}]);
