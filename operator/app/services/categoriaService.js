app.service('categoriaService', ['$rootScope', '$http', function($rootScope, $http) {

  var self = this;

  this.set = function(categoria) {
    self.categoria = categoria;
    $rootScope.$broadcast("categoria", categoria);
  };

  this.getList = function(){
    $rootScope.$broadcast("categorias:loading", true);
    $http.post('/controller/plataforma/categoria/list', self.categoria)
    .success(function(categorias){
      $rootScope.$broadcast("categorias:loading", false);
      $rootScope.$broadcast("categorias", categorias);
    })
    .error(function(categorias){
      $rootScope.$broadcast("categorias:loading", false);
      $rootScope.$broadcast("categorias", categorias);
    });
  };

}]);
