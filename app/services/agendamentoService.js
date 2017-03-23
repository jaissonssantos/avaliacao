angular.module('app').service('agendamentoService', ['$rootScope', '$timeout', '$http' , function ($rootScope, $timeout, $http) {
  var self = this

  this.set = function (agendamento) {
    self.agendamento = agendamento
    $rootScope.$broadcast('agendamento', agendamento)
  }

  this.load = function () {
    $rootScope.$broadcast('agendamento:loading', true)
    $http.post('/controller/marketplace/agendamento/get', self.agendamento)
      .success(function (response) {
        $rootScope.$broadcast('agendamento:loading', false)
        $rootScope.$broadcast('agendamento', response)
      })
      .error(function (response) {
        $rootScope.$broadcast('agendamento:loading', false)
        $rootScope.$broadcast('agendamento', response)
      })
  }

  this.getBusyTime = function () {
    $rootScope.$broadcast('agendamento:horario:ocupados:loading', true)
    $http.post('/controller/marketplace/agendamento/getbusytime', self.agendamento)
      .success(function (response) {
        $rootScope.$broadcast('agendamento:horario:ocupados:loading', false)
        $rootScope.$broadcast('agendamento:horario:ocupados', response)
      })
      .error(function (response) {
        $rootScope.$broadcast('agendamento:horario:ocupados:loading', false)
        $rootScope.$broadcast('agendamento:horario:ocupados', response)
      })
  }

  this.save = function(){
    $rootScope.$broadcast("agendamento:save", "loading");
    $http.post('/controller/marketplace/agendamento/create', {
				idEstabelecimento: self.agendamento.idestabelecimento,
				idProfissional: self.agendamento.profissional ? self.agendamento.profissional.id : 0,
				idServico: self.agendamento.servico ? self.agendamento.servico.id : 0,
				formaPagamento: self.agendamento.pay ?  self.agendamento.pay.method : 1,
				horario: self.agendamento.reserve
    })
    .success(function(response){
      $rootScope.$broadcast("agendamento:save", "success");
      $rootScope.$broadcast("agendamentos:message:success", response.success);
    }).error(function(response){
      $rootScope.$broadcast("agendamento:save", "error");
      $rootScope.$broadcast("agendamentos:message:error", response.error);
    });
  };

  this.cancel = function(){
    $rootScope.$broadcast("agendamento:cancel", "loading");
    $http.post('/controller/marketplace/agendamento/cancel', self.agendamento)
    .success(function(response){
      $rootScope.$broadcast("agendamento:cancel", "success");
      $rootScope.$broadcast("agendamentos:cancel:message:success", response.success);
      $timeout(function(){
          $rootScope.$broadcast("agendamentos:cancel:message:success", "");
      }, 10000);
    }).error(function(response){
      $rootScope.$broadcast("agendamento:save", "error");
      $rootScope.$broadcast("agendamentos:cancel:message:error", response.error);
      $timeout(function(){
          $rootScope.$broadcast("agendamentos:cancel:message:error", "");
      }, 10000);
    });
  };

}])
