app.service('estabelecimentoService', ['$rootScope', '$http', '$timeout', 'Upload', function($rootScope, $http, $timeout, Upload) {

  var self = this;

  this.set = function(estabelecimento) {
    self.estabelecimento = estabelecimento;
    $rootScope.$broadcast("estabelecimento", estabelecimento);
  };

  this.setIds = function (ids) {
    self.ids = ids
  }

  this.getList = function(){
    $http.post('/controller/gestor/estabelecimento/list', self.estabelecimento)
    .success(function(estabelecimentos){
      $rootScope.$broadcast("estabelecimentos", estabelecimentos);
    });
  };

  this.loadEstabelecimento = function(hash){
    $http.post('/controller/gestor/estabelecimento/get', {hash: hash})
    .success(function(estabelecimento){
      $rootScope.$broadcast("estabelecimento", estabelecimento);
    });
  };

  this.loadSegmentos = function(){
    $http.get('/controller/gestor/estabelecimento/segmentos')
    .success(function(segmentos){
      $rootScope.$broadcast("segmentos", segmentos);
    });
  };

  this.loadPlanos = function(){
    $http.get('/controller/gestor/estabelecimento/planos')
    .success(function(planos){
      $rootScope.$broadcast("planos", planos);
    });
  };

  this.checkCnpjcpf = function(cnpjcpf){
    if(!cnpjcpf || cnpjcpf.length < 11){
      return;
    }
    $rootScope.$broadcast("estabelecimento:cnpjcpf", "loading");
    $http.post('/controller/gestor/estabelecimento/checkcnpjcpf', {cnpjcpf: cnpjcpf})
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
    $http.post('/controller/gestor/estabelecimento/checkemail', {email: email})
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
    $http.post('/controller/gestor/estabelecimento/checkemail', {login: login})
    .success(function(response){
      $rootScope.$broadcast("estabelecimento:login", "found");
    })
    .error(function(response){
      if(response && response.error == 'Email não cadastrado'){
        $rootScope.$broadcast("estabelecimento:login", "notfound");
      }
    });
  };

  this.load = function () {
    $rootScope.$broadcast('estabelecimento:loading', true)
    $http.post('/controller/gestor/estabelecimento/get', self.estabelecimento)
      .success(function (estabelecimento) {
        $rootScope.$broadcast('estabelecimento:loading', false)
        $rootScope.$broadcast('estabelecimento', estabelecimento)
      })
      .error(function (estabelecimento) {
        $rootScope.$broadcast('estabelecimento:loading', false)
        $rootScope.$broadcast('estabelecimento', estabelecimento)
      })
  }

  this.save = function(){
    $http.post('/controller/gestor/estabelecimento/create', self.estabelecimento)
    .success(function(response){
      $rootScope.$broadcast("estabelecimento:save", "success");
    }).error(function(error){
      $rootScope.$broadcast("estabelecimento:save", "error");
    });
  };

  this.update = function(){
    Upload.upload({ url: '/controller/gestor/estabelecimento/update', data: self.estabelecimento })
    .then(function(response){
      $rootScope.$broadcast("estabelecimento:update", "success");
    }).catch(function(error){
      $rootScope.$broadcast("estabelecimento:update", "error");
    });
  };

  this.setStatus = function (status) {
    var data = {
      estabelecimentos: self.ids,
      status: status
    }
    $http.post('/controller/gestor/estabelecimento/setstatus', data)
      .success(function (response) {
        $rootScope.$broadcast('estabelecimentos:move', 'success')
        self.getList()
        $rootScope.$broadcast('estabelecimentos:message:success', 'Atualizados com sucesso')
        $timeout(function () {
          $rootScope.$broadcast('estabelecimentos:message:success', '')
        }, 3000)
        self.getList()
      }).error(function (error) {
      $rootScope.$broadcast('estabelecimentos:move', 'error')
    })
  }

}]);
