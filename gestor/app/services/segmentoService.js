app.service('segmentoService', ['$rootScope', '$timeout', '$http' , function ($rootScope, $timeout, $http) {
  var self = this

  this.set = function (segmento) {
    self.segmento = segmento
    $rootScope.$broadcast('segmento', segmento)
  }


  this.getList = function(){
    $rootScope.$broadcast("segmentos:loading", true);
    $http.post('/controller/gestor/segmento/list', self.segmento)
    .success(function(segmentos){
      $rootScope.$broadcast("segmentos:loading", false);
      $rootScope.$broadcast("segmentos", segmentos);
    })
    .error(function(segmentos){
      $rootScope.$broadcast("segmentos:loading", false);
      $rootScope.$broadcast("segmentos", segmentos);
    });
  };

}])
