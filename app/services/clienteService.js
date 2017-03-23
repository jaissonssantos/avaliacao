angular.module('app').service('clienteService', ['$rootScope', '$timeout', '$http', 'Upload' , function ($rootScope, $timeout, $http, Upload) {
  var self = this

  this.set = function (cliente) {
    self.cliente = cliente
    $rootScope.$broadcast('cliente', cliente)
  }

  this.load = function () {
    $rootScope.$broadcast('cliente:loading', true)
    $http.post('/controller/marketplace/cliente/get', self.cliente)
      .success(function (response) {
        $rootScope.$broadcast('cliente:loading', false)
        $rootScope.$broadcast('cliente', response)
      })
      .error(function (response) {
        $rootScope.$broadcast('cliente:loading', false)
        $rootScope.$broadcast('cliente', response)
      })
  }

  this.loadSchedule = function () {
    $rootScope.$broadcast('cliente:agenda:loading', true)
    $http.post('/controller/marketplace/cliente/getschedule', self.cliente)
      .success(function (response) {
        $rootScope.$broadcast('cliente:agenda:loading', false)
        $rootScope.$broadcast('cliente:agenda', response)
      })
      .error(function (response) {
        $rootScope.$broadcast('cliente:agenda:loading', false)
        $rootScope.$broadcast('cliente:agenda', response)
      })
  }

  this.loadFavorite = function () {
    $rootScope.$broadcast('cliente:favorito:loading', true)
    $http.post('/controller/marketplace/cliente/getfavorite', self.cliente)
      .success(function (response) {
        $rootScope.$broadcast('cliente:favorito:loading', false)
        $rootScope.$broadcast('cliente:favorito', response)
      })
      .error(function (response) {
        $rootScope.$broadcast('cliente:favorito:loading', false)
        $rootScope.$broadcast('cliente:favorito', response)
      })
  }

  this.loadRating = function () {
    $rootScope.$broadcast('cliente:avaliacao:loading', true)
    $http.post('/controller/marketplace/cliente/getrating', self.cliente)
      .success(function (response) {
        $rootScope.$broadcast('cliente:avaliacao:loading', false)
        $rootScope.$broadcast('cliente:avaliacao', response)
      })
      .error(function (response) {
        $rootScope.$broadcast('cliente:avaliacao:loading', false)
        $rootScope.$broadcast('cliente:avaliacao', response)
      })
  }

  this.checkCpf = function(cpf){
    if(!cpf || cpf.length < 11){
      return;
    }
    $rootScope.$broadcast("cliente:cpf", "loading");
    $http.post('/controller/marketplace/cliente/checkcpf', {cpf: cpf})
    .success(function(response){
      $rootScope.$broadcast("cliente:cpf", "found");
    })
    .error(function(response){
      if(response.error == 'CPF não cadastrados'){
        $rootScope.$broadcast("cliente:cpf", "notfound");
      }
    });
  };

  this.update = function(){
    $rootScope.$broadcast("cliente:update", "loading");
    var $promise = Upload.upload({url:"/controller/marketplace/cliente/update",data:self.cliente});
      $promise.then(function(item){
        $rootScope.$broadcast("cliente:update", "success");
        $rootScope.$broadcast("clientes:message:success", item.data.success);
        $timeout(function(){
          $rootScope.$broadcast("clientes:message:success", "");
        }, 5000);
      }, function(item) {
        $rootScope.$broadcast("cliente:update", "error");
        $rootScope.$broadcast("clientes:message:error", item.data.error);
        $timeout(function(){
          $rootScope.$broadcast("clientes:message:error", "");
        }, 5000);
      });
  };

  this.password = function(){
    $rootScope.$broadcast("cliente:password", "loading");
    $http.post('/controller/marketplace/cliente/password', self.cliente)
    .success(function(response){
      $rootScope.$broadcast("cliente:password", "success");
      $rootScope.$broadcast("clientes:password:message:success", response);
      $timeout(function(){
          $rootScope.$broadcast("clientes:password:message:success", "");
      }, 5000);
    }).error(function(response){
      $rootScope.$broadcast("cliente:password", "error");
      $rootScope.$broadcast("clientes:password:message:error", response);
      $timeout(function(){
          $rootScope.$broadcast("clientes:password:message:error", "");
      }, 5000);
    });
  };

}]);
