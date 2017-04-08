app.service('clienteService', ['$rootScope', '$timeout', '$http', function($rootScope, $timeout, $http) {

  var self = this;

  this.set = function(cliente) {
    self.cliente = cliente;
    $rootScope.$broadcast("cliente", cliente);
  };

  this.setIds = function(ids){
    self.ids = ids;
  };

  this.getList = function(){
    $rootScope.$broadcast("clientes:loading", true);
    $http.post('/controller/plataforma/cliente/list', self.cliente)
    .success(function(clientes){
      $rootScope.$broadcast("clientes:loading", false);
      $rootScope.$broadcast("clientes", clientes);
    })
    .error(function(clientes){
      $rootScope.$broadcast("clientes:loading", false);
      $rootScope.$broadcast("clientes", clientes);
    });
  };

  this.load = function(){
    $rootScope.$broadcast("cliente:loading", true);
    $http.post('/controller/plataforma/cliente/get', self.cliente)
    .success(function(cliente){
      $rootScope.$broadcast("cliente:loading", false);
      $rootScope.$broadcast("cliente", cliente);
    })
    .error(function(cliente){
      $rootScope.$broadcast("cliente:loading", false);
      $rootScope.$broadcast("cliente", cliente);
    });
  };

  this.checkCliente = function(){
    $rootScope.$broadcast("clientes:loading", true);
    $http.post('/controller/plataforma/cliente/checkcliente', self.cliente)
    .success(function(response){
      $rootScope.$broadcast("clientes:loading", false);
      $rootScope.$broadcast("cliente:nome", response);
    }).error(function(response){
      $rootScope.$broadcast("clientes:loading", false);
      $rootScope.$broadcast("cliente:nome", response);
    });
  };

  this.checkCpf = function(cliente){
    if(!cliente.cpf || cliente.cpf.length < 11){
      return;
    }
    data = {
      cpf: cliente.cpf,
      id: cliente.id || null
    };
    $rootScope.$broadcast("cliente:cpf", "loading");
    $http.post('/controller/plataforma/cliente/checkcpf', data)
    .success(function(response){
      $rootScope.$broadcast("cliente:cpf", "found");
    })
    .error(function(response){
      if(response.error == 'CPF não cadastrado'){
        $rootScope.$broadcast("cliente:cpf", "notfound");
      }
    });
  };

  this.checkEmail = function(cliente){
    data = {
      email: cliente.email,
      id: cliente.id || null
    };
    $rootScope.$broadcast("cliente:email", "loading");
    $http.post('/controller/plataforma/cliente/checkemail', data)
    .success(function(response){
      $rootScope.$broadcast("cliente:email", "found");
    })
    .error(function(response){
      if(response && response.error && response.error == 'Email não cadastrado'){
        $rootScope.$broadcast("cliente:email", "notfound");
      }
    });
  };

  this.checkLogin = function(login){
    $rootScope.$broadcast("cliente:login", "loading");
    $http.post('/controller/plataforma/cliente/checkemail', {login: login})
    .success(function(response){
      $rootScope.$broadcast("cliente:login", "found");
    })
    .error(function(response){
      if(response && response.error == 'Login não cadastrado'){
        $rootScope.$broadcast("cliente:login", "notfound");
      }
    });
  };

  this.save = function(){
    $rootScope.$broadcast("cliente:save", "loading");
    $http.post('/controller/plataforma/cliente/create', self.cliente)
    .success(function(response){
      $rootScope.$broadcast("cliente:save", "success");
      $rootScope.$broadcast("clientes:message:success", response.success);
      $timeout(function() {
        $rootScope.$broadcast("clientes:message:success", "");
      }, 5000);
    }).error(function(error){
      $rootScope.$broadcast("cliente:save", "error");
      $rootScope.$broadcast("clientes:message:error", response.error);
    });
  };

  this.update = function(){
    $rootScope.$broadcast("cliente:update", "loading");
    $http.post('/controller/plataforma/cliente/update', self.cliente)
    .success(function(response){
      $rootScope.$broadcast("cliente:update", "success");
      $rootScope.$broadcast("clientes:message:success", response.success);
      $timeout(function() {
        $rootScope.$broadcast("clientes:message:success", "");
      }, 5000);
    }).error(function(error){
      $rootScope.$broadcast("cliente:update", "error");
      $rootScope.$broadcast("clientes:message:error", response.error);
    });
  };

  this.setStatus = function(status){
    var data = {
      clientes: self.ids,
      status: status
    };
    $http.post('/controller/plataforma/cliente/setstatus', data)
    .success(function(response){
      $rootScope.$broadcast("clientes:move", "success");
      $rootScope.$broadcast("clientes:message:success", "Atualizados com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("clientes:message:success", "");
      }, 3000);
      self.getList();
    }).error(function(error){
      $rootScope.$broadcast("clientes:move", "error");
    });
  };

}]);
