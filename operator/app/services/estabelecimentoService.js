app.service('estabelecimentoService', ['$rootScope', '$http', function($rootScope, $http) {

  var self = this;

  this.set = function(estabelecimento) {
    self.estabelecimento = estabelecimento;
    $rootScope.$broadcast("estabelecimento", estabelecimento);
  };

  this.getList = function(){
    $http.get('/controller/plataforma/estabelecimento/list')
    .success(function(estabelecimentos){
      $rootScope.$broadcast("estabelecimentos", estabelecimentos);
    });
  };

  this.loadEstabelecimento = function(hash){
    $http.post('/controller/plataforma/estabelecimento/get', {hash: hash})
    .success(function(estabelecimento){
      $rootScope.$broadcast("estabelecimento", estabelecimento);
    });
  };

  this.loadSegmentos = function(){
    $http.get('/controller/plataforma/estabelecimento/segmentos')
    .success(function(segmentos){
      $rootScope.$broadcast("segmentos", segmentos);
    });
  };

  this.loadPlanos = function(){
    $http.get('/controller/plataforma/estabelecimento/planos')
    .success(function(planos){
      $rootScope.$broadcast("planos", planos);
    });
  };

  this.checkCnpjcpf = function(cnpjcpf){
    if(!cnpjcpf || cnpjcpf.length < 11){
      return;
    }
    $rootScope.$broadcast("estabelecimento:cnpjcpf", "loading");
    $http.post('/controller/plataforma/estabelecimento/checkcnpjcpf', {cnpjcpf: cnpjcpf})
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
    $http.post('/controller/plataforma/estabelecimento/checkemail', {email: email})
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
    $http.post('/controller/plataforma/estabelecimento/checkemail', {login: login})
    .success(function(response){
      $rootScope.$broadcast("estabelecimento:login", "found");
    })
    .error(function(response){
      if(response && response.error == 'Email não cadastrado'){
        $rootScope.$broadcast("estabelecimento:login", "notfound");
      }
    });
  };

  this.save = function(){
    $http.post('/controller/plataforma/estabelecimento/create', self.estabelecimento)
    .success(function(response){
      $rootScope.$broadcast("estabelecimento:save", "success");
    }).error(function(error){
      $rootScope.$broadcast("estabelecimento:save", "error");
    });
  };

}]);
