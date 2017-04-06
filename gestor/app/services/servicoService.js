app.service('servicoService', ['$rootScope', '$timeout', '$http', function($rootScope, $timeout, $http) {

  var self = this;

  this.set = function(servico) {
    self.servico = servico;
    $rootScope.$broadcast("servico", servico);
  };

  this.setIds = function(ids){
    self.ids = ids;
  };

  this.getList = function(){
    $rootScope.$broadcast("servicos:loading", true);
    $http.post('/controller/gestor/servico/list', self.servico)
    .success(function(servicos){
      $rootScope.$broadcast("servicos:loading", false);
      $rootScope.$broadcast("servicos", servicos);
    })
    .error(function(servicos){
      $rootScope.$broadcast("servicos:loading", false);
      $rootScope.$broadcast("servicos", servicos);
    });
  };

  this.load = function(){
    $rootScope.$broadcast("servico:loading", true);
    $http.post('/controller/gestor/servico/get', self.servico)
    .success(function(servico){
      $rootScope.$broadcast("servico:loading", false);
      $rootScope.$broadcast("servico", servico);
    })
    .error(function(servico){
      $rootScope.$broadcast("servico:loading", false);
      $rootScope.$broadcast("servico", servico);
    });
  };

  this.save = function(){
    $rootScope.$broadcast("servico:save", "loading");
    $http.post('/controller/gestor/servico/create', self.servico)
    .success(function(response){
      $rootScope.$broadcast("servico:save", "success");
      $rootScope.$broadcast("servicos:message:success", "Cadastrado com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("servicos:message:success", "");
      }, 3000);
    }).error(function(error){
      $rootScope.$broadcast("servico:save", "error");
    });
  };

  this.update = function(){
    $rootScope.$broadcast("servico:update", "loading");
    $http.post('/controller/gestor/servico/update', self.servico)
    .success(function(response){
      $rootScope.$broadcast("servico:update", "success");
      $rootScope.$broadcast("servicos:message:success", "Atualizado com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("servicos:message:success", "");
      }, 3000);
    }).error(function(error){
      $rootScope.$broadcast("servico:update", "error");
    });
  };

  this.setStatus = function(status){
    var data = {
      servicos: self.ids,
      status: status
    };
    $http.post('/controller/gestor/servico/setstatus', data)
    .success(function(response){
      $rootScope.$broadcast("servicos:move", "success");
      $rootScope.$broadcast("servicos:message:success", "Atualizados com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("servicos:message:success", "");
      }, 3000);
      self.getList();
    }).error(function(error){
      $rootScope.$broadcast("servicos:move", "error");
    });
  };

}]);
