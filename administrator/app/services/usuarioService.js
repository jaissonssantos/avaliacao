app.service('usuarioService', ['$rootScope', '$timeout', '$http', function($rootScope, $timeout, $http) {

  var self = this;

  this.set = function(usuario) {
    self.usuario = usuario;
    $rootScope.$broadcast("usuario", usuario);
  };

  this.setIds = function(ids){
    self.ids = ids;
  };

  this.getList = function(){
    $http.post('/controller/gestor/usuario/list', self.usuario)
    .success(function(usuarios){
      $rootScope.$broadcast("usuarios", usuarios);
    });
  };

  this.load = function(){
    $rootScope.$broadcast("usuario:loading", true);
    $http.post('/controller/gestor/usuario/get', self.usuario)
    .success(function(usuario){
      $rootScope.$broadcast("usuario:loading", false);
      $rootScope.$broadcast("usuario", usuario);
    })
    .error(function(usuario){
      $rootScope.$broadcast("usuario:loading", false);
      $rootScope.$broadcast("usuario", usuario);
    });
  };

  this.checkEmail = function(usuario){
    $rootScope.$broadcast("usuario:email", "loading");
    $http.post('/controller/gestor/usuario/checkemail', usuario)
    .success(function(response){
      $rootScope.$broadcast("usuario:email", "found");
    })
    .error(function(response){
      if(response && response.error == 'Email não cadastrado'){
        $rootScope.$broadcast("usuario:email", "notfound");
      }
    });
  };

  this.checkLogin = function(usuario){
    $rootScope.$broadcast("usuario:login", "loading");
    $http.post('/controller/gestor/usuario/checklogin', usuario)
    .success(function(response){
      $rootScope.$broadcast("usuario:login", "found");
    })
    .error(function(response){
      if(response && response.error == 'Login não cadastrado'){
        $rootScope.$broadcast("usuario:login", "notfound");
      }
    });
  };

  this.changePassword = function(){
    $rootScope.$broadcast("usuario:changepassword", "loading");
    $http.post('/controller/gestor/usuario/changepassword', self.usuario)
    .success(function(response){
      $rootScope.$broadcast("usuario:changepassword", "success");
      $timeout(function() {
        $rootScope.$broadcast("usuarios:message:success", "Atualizado com sucesso");
        $rootScope.$broadcast("usuarios:message:success", "");
      }, 3000);
    }).error(function(response){
      $rootScope.$broadcast("usuario:changepassword", "error");
    });
  };

  this.save = function(){
    $http.post('/controller/gestor/usuario/create', self.usuario)
    .success(function(response){
      $rootScope.$broadcast("usuario:save", "success");
      $rootScope.$broadcast("usuarios:message:success", "Cadastrado com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("usuarios:message:success", "");
      }, 3000);
    }).error(function(error){
      $rootScope.$broadcast("usuario:save", "error");
    });
  };

  this.update = function(){
    $rootScope.$broadcast("usuario:update", "loading");
    $http.post('/controller/gestor/usuario/update', self.usuario)
    .success(function(response){
      $rootScope.$broadcast("usuario:update", "success");
      $rootScope.$broadcast("usuarios:message:success", "Atualizado com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("usuarios:message:success", "");
      }, 3000);
    }).error(function(error){
      $rootScope.$broadcast("usuario:update", "error");
    });
  };

  this.setStatus = function(status){
    var data = {
      usuarios: self.ids,
      status: status
    };
    $http.post('/controller/gestor/usuario/setstatus', data)
    .success(function(response){
      $rootScope.$broadcast("usuarios:move", "success");
      $rootScope.$broadcast("usuarios:message:success", "Atualizados com sucesso");
      $timeout(function() {
        $rootScope.$broadcast("usuarios:message:success", "");
      }, 3000);
      self.getList();
    }).error(function(error){
      $rootScope.$broadcast("usuarios:move", "error");
    });
  };


}]);
