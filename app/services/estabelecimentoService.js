angular.module('app').service('estabelecimentoService', ['$rootScope', '$timeout', '$http' , function ($rootScope, $timeout, $http) {
  var self = this

  this.set = function (estabelecimento) {
    self.estabelecimento = estabelecimento
    $rootScope.$broadcast('estabelecimento', estabelecimento)
  }

  this.load = function () {
    $rootScope.$broadcast('estabelecimento:loading', true)
    $http.post('/controller/marketplace/estabelecimento/get', self.estabelecimento)
      .success(function (response) {
        $rootScope.$broadcast('estabelecimento:loading', false)
        $rootScope.$broadcast('estabelecimento', response)
      })
      .error(function (response) {
        $rootScope.$broadcast('estabelecimento:loading', false)
        $rootScope.$broadcast('estabelecimento', response)
      })
  }

  this.getList = function(){
    $http.post('/controller/marketplace/estabelecimento/list', self.estabelecimento)
    .success(function(estabelecimentos){
      $rootScope.$broadcast("estabelecimentos", estabelecimentos);
    });
  };

  this.loadSegmentos = function(){
    $http.get('/controller/marketplace/estabelecimento/segmentos')
    .success(function(segmentos){
      $rootScope.$broadcast("segmentos", segmentos);
    });
  };

  this.checkCnpjcpf = function(cnpjcpf){
    if(!cnpjcpf || cnpjcpf.length < 11){
      return;
    }
    $rootScope.$broadcast("estabelecimento:cnpjcpf", "loading");
    $http.post('/controller/marketplace/estabelecimento/checkcnpjcpf', {cnpjcpf: cnpjcpf})
    .success(function(response){
      $rootScope.$broadcast("estabelecimento:cnpjcpf", "found");
    })
    .error(function(response){
      if(response.error == 'CNPJ/CPF não cadastrados'){
        $rootScope.$broadcast("estabelecimento:cnpjcpf", "notfound");
      }
    });
  };

  this.checkEmail = function(email){
    $rootScope.$broadcast("estabelecimento:email", "loading");
    $http.post('/controller/marketplace/estabelecimento/checkemail', {email: email})
    .success(function(response){
      $rootScope.$broadcast("estabelecimento:email", "found");
    })
    .error(function(response){
      if(response && response.error == 'Email não cadastrado'){
        $rootScope.$broadcast("estabelecimento:email", "notfound");
      }
    });
  };

  this.checkLogin = function(login){
    $rootScope.$broadcast("estabelecimento:login", "loading");
    $http.post('/controller/marketplace/estabelecimento/checkemail', {login: login})
    .success(function(response){
      $rootScope.$broadcast("estabelecimento:login", "found");
    })
    .error(function(response){
      if(response && response.error == 'Email não cadastrado'){
        $rootScope.$broadcast("estabelecimento:login", "notfound");
      }
    });
  };

  this.favorite = function(estabelecimento){
    $rootScope.$broadcast("estabelecimento:favorite", "loading");
    $http.post('/controller/marketplace/estabelecimento/favorite', {estabelecimento: estabelecimento})
    .success(function(response){
      $rootScope.$broadcast("estabelecimento:favorite", "success");
      $rootScope.$broadcast("estabelecimentos:favorite:message:success", response);
    }).error(function(response){
      $rootScope.$broadcast("estabelecimento:favorite", "error");
      $rootScope.$broadcast("estabelecimentos:favorite:message:error", response);
      $timeout(function(){
          $rootScope.$broadcast("estabelecimentos:favorite:message:error", "");
      }, 5000);
    });
  };

  this.send = function(name,email,sender,subject,message){
    $rootScope.$broadcast("estabelecimento:send", "loading");
    $http.post('/controller/marketplace/estabelecimento/send', {name: name, email: email, sender: sender, subject: subject, message: message})
    .success(function(response){
      $rootScope.$broadcast("estabelecimento:send", "success");
      $rootScope.$broadcast("estabelecimentos:send:message:success", response);
      $timeout(function(){
          $rootScope.$broadcast("estabelecimentos:send:message:success", "");
      }, 10000);
    }).error(function(response){
      $rootScope.$broadcast("estabelecimento:send", "error");
      $rootScope.$broadcast("estabelecimentos:send:message:error", response);
      $timeout(function(){
          $rootScope.$broadcast("estabelecimentos:send:message:error", "");
      }, 5000);
    });
  };

  this.save = function(){
    $http.post('/controller/marketplace/estabelecimento/create', self.estabelecimento)
    .success(function(response){
      $rootScope.$broadcast("estabelecimento:save", "success");
    }).error(function(error){
      $rootScope.$broadcast("estabelecimento:save", "error");
    });
  };

}]);
