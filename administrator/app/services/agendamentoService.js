app.service('agendamentoService', ['$rootScope', '$timeout', '$http', function($rootScope, $timeout, $http) {

  var self = this;

  this.set = function(agendamento) {
    self.agendamento = agendamento;
    $rootScope.$broadcast("agendamento", agendamento);
  };

  this.setIds = function(ids){
    self.ids = ids;
  };

  this.getList = function(){
    $rootScope.$broadcast("agendamentos:loading", true);
    $http.post('/controller/gestor/agendamento/list', self.agendamento)
    .success(function(agendamentos){
      $rootScope.$broadcast("agendamentos:loading", false);
      $rootScope.$broadcast("agendamentos", agendamentos);
    })
    .error(function(agendamentos){
      $rootScope.$broadcast("agendamentos:loading", false);
      $rootScope.$broadcast("agendamentos", agendamentos);
    });
  };

  this.load = function(){
    $rootScope.$broadcast("agendamento:loading", true);
    $http.post('/controller/gestor/agendamento/get', self.agendamento)
    .success(function(agendamento){
      $rootScope.$broadcast("agendamento:loading", false);
      $rootScope.$broadcast("agendamento", agendamento);
    })
    .error(function(agendamento){
      $rootScope.$broadcast("agendamento:loading", false);
      $rootScope.$broadcast("agendamento", agendamento);
    });
  };

  this.save = function(){
    $rootScope.$broadcast("agendamento:save", "loading");
    $http.post('/controller/gestor/agendamento/create', self.agendamento)
    .success(function(response){
      $rootScope.$broadcast("agendamento:save", "success");
      $rootScope.$broadcast("agendamentos:message:success", "Cadastrado com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("agendamentos:message:success", "");
      }, 3000);
    }).error(function(error){
      $rootScope.$broadcast("agendamento:save", "error");
    });
  };

  this.update = function(){
    $rootScope.$broadcast("agendamento:update", "loading");
    $http.post('/controller/gestor/agendamento/update', self.agendamento)
    .success(function(response){
      $rootScope.$broadcast("agendamento:update", "success");
      $rootScope.$broadcast("agendamentos:message:success", "Atualizado com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("agendamentos:message:success", "");
      }, 3000);
    }).error(function(error){
      $rootScope.$broadcast("agendamento:update", "error");
    });
  };

  this.setStatus = function(status){
    var data = {
      agendamentos: self.ids,
      status: status
    };
    $http.post('/controller/gestor/agendamento/setstatus', data)
    .success(function(response){
      $rootScope.$broadcast("agendamentos:move", "success");
      $rootScope.$broadcast("agendamentos:message:success", "Atualizados com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("agendamentos:message:success", "");
      }, 3000);
      self.getList();
    }).error(function(error){
      $rootScope.$broadcast("agendamentos:move", "error");
    });
  };

}]);
