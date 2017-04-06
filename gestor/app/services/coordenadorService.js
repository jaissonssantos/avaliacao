app.service('coordenadorService', ['$rootScope', '$timeout', '$http', function($rootScope, $timeout, $http) {

  var self = this;

  this.set = function(coordenador) {
    self.coordenador = coordenador;
    $rootScope.$broadcast("coordenador", coordenador);
  };

  this.setIds = function(ids){
    self.ids = ids;
  };

  this.getList = function(){
    $rootScope.$broadcast("coordenadors:loading", true);
    $http.post('/controller/gestor/coordenador/list', self.coordenador)
    .success(function(coordenadors){
      $rootScope.$broadcast("coordenadors:loading", false);
      $rootScope.$broadcast("coordenadors", coordenadors);
    })
    .error(function(coordenadors){
      $rootScope.$broadcast("coordenadors:loading", false);
      $rootScope.$broadcast("coordenadors", coordenadors);
    });
  };

  this.load = function(){
    $rootScope.$broadcast("coordenador:loading", true);
    $http.post('/controller/gestor/coordenador/get', self.coordenador)
    .success(function(coordenador){
      $rootScope.$broadcast("coordenador:loading", false);
      $rootScope.$broadcast("coordenador", coordenador);
    })
    .error(function(coordenador){
      $rootScope.$broadcast("coordenador:loading", false);
      $rootScope.$broadcast("coordenador", coordenador);
    });
  };

  this.save = function(){
    $rootScope.$broadcast("coordenador:save", "loading");
    $http.post('/controller/gestor/coordenador/save', self.coordenador)
    .success(function(response){
      $rootScope.$broadcast("coordenador:save", "success");
      $rootScope.$broadcast("coordenadors:message:success", "Cadastrado com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("coordenadors:message:success", "");
      }, 3000);
    }).error(function(error){
      $rootScope.$broadcast("coordenador:save", "error");
    });
  };

  this.update = function(){
    $rootScope.$broadcast("coordenador:update", "loading");
    $http.post('/controller/gestor/coordenador/update', self.coordenador)
    .success(function(response){
      $rootScope.$broadcast("coordenador:update", "success");
      $rootScope.$broadcast("coordenadors:message:success", "Atualizado com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("coordenadors:message:success", "");
      }, 3000);
    }).error(function(error){
      $rootScope.$broadcast("coordenador:update", "error");
    });
  };

  this.delete = function(){
    $rootScope.$broadcast("coordenador:delete", "loading");
    $http.post('/controller/gestor/coordenador/delete', self.coordenador)
    .success(function(response){
      $rootScope.$broadcast("coordenador:delete", "success");
      $rootScope.$broadcast("coordenadors:message:success", "Excluído com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("coordenadors:message:success", "");
      }, 3000);
    }).error(function(error){
      $rootScope.$broadcast("coordenador:delete", "error");
    });
  };

  this.setStatus = function(status){
    var data = {
      servicos: self.ids,
      status: status
    };
    $http.post('/controller/gestor/coordenador/setstatus', data)
    .success(function(response){
      $rootScope.$broadcast("coordenadors:move", "success");
      $rootScope.$broadcast("coordenadors:message:success", "Atualizados com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("coordenadors:message:success", "");
      }, 3000);
      self.getList();
    }).error(function(error){
      $rootScope.$broadcast("coordenadors:move", "error");
    });
  };

}]);
