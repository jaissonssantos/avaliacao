app.service('relatorioService', ['$rootScope', '$timeout', '$http', function($rootScope, $timeout, $http) {

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
    $http.post('controller/relatorio/list', self.agendamento)
    .success(function(agendamentos){
      $rootScope.$broadcast("agendamentos:loading", false);
      $rootScope.$broadcast("agendamentos", agendamentos);
    })
    .error(function(agendamentos){
      $rootScope.$broadcast("agendamentos:loading", false);
      $rootScope.$broadcast("agendamentos", agendamentos);
    });
  };

}]);
