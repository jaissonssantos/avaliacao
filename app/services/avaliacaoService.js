angular.module('app').service('avaliacaoService', ['$rootScope', '$timeout', '$http' , function ($rootScope, $timeout, $http) {
  var self = this

  this.set = function (avaliacao) {
    self.avaliacao = avaliacao
    $rootScope.$broadcast('avaliacao', avaliacao)
  }

  this.estabelecimentoRating = function(){
    $rootScope.$broadcast("avaliacao:rating", "loading");
    $http.post('/controller/marketplace/estabelecimento/rating', self.avaliacao)
    .success(function(response){
      $rootScope.$broadcast("avaliacao:rating", "success");
      $rootScope.$broadcast("avaliacoes:message:success", response.success);
    }).error(function(response){
      $rootScope.$broadcast("avaliacao:rating", "error");
      $rootScope.$broadcast("avaliacoes:message:error", response.error);
    });
  };

}])
