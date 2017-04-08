app.service('categoriaService', ['$rootScope', '$http', '$timeout', function($rootScope, $http, $timeout) {

  var self = this;

  this.set = function(categoria) {
    self.categoria = categoria;
    $rootScope.$broadcast("categoria", categoria);
  };

  this.setIds = function(ids){
    self.ids = ids;
  };

  this.getList = function(){
    $rootScope.$broadcast("categorias:loading", true);
    $http.post('/controller/gestor/categoria/list', self.categoria)
    .success(function(categorias){
      $rootScope.$broadcast("categorias:loading", false);
      $rootScope.$broadcast("categorias", categorias);
    })
    .error(function(categorias){
      $rootScope.$broadcast("categorias:loading", false);
      $rootScope.$broadcast("categorias", categorias);
    });
  };

  this.load = function(){
    $rootScope.$broadcast("categoria:loading", true);
    $http.post('/controller/gestor/categoria/get', self.categoria)
    .success(function(categoria){
      $rootScope.$broadcast("categoria:loading", false);
      $rootScope.$broadcast("categoria", categoria);
    })
    .error(function(categoria){
      $rootScope.$broadcast("categoria:loading", false);
      $rootScope.$broadcast("categoria", categoria);
    });
  };

  this.save = function(){
    $rootScope.$broadcast("categoria:save", "loading");
    $http.post('/controller/gestor/categoria/save', self.categoria)
    .success(function(response){
      $rootScope.$broadcast("categoria:save", "success");
      $rootScope.$broadcast("categorias:message:success", "Cadastrado com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("categorias:message:success", "");
      }, 3000);
    }).error(function(error){
      $rootScope.$broadcast("categoria:save", "error");
    });
  };

  this.update = function(){
    $rootScope.$broadcast("categoria:update", "loading");
    $http.post('/controller/gestor/categoria/update', self.categoria)
    .success(function(response){
      $rootScope.$broadcast("categoria:update", "success");
      $rootScope.$broadcast("categorias:message:success", "Atualizado com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("categorias:message:success", "");
      }, 3000);
    }).error(function(error){
      $rootScope.$broadcast("categoria:update", "error");
    });
  };

  this.delete = function(){
    $rootScope.$broadcast("categoria:delete", "loading");
    $http.post('/controller/gestor/categoria/delete', self.categoria)
    .success(function(response){
      $rootScope.$broadcast("categoria:delete", "success");
      $rootScope.$broadcast("categorias:message:success", "Excluído com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("categorias:message:success", "");
      }, 3000);
    }).error(function(error){
      $rootScope.$broadcast("categoria:delete", "error");
    });
  };

}]);
