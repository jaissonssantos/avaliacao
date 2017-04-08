app.service('aplicacaoService', ['$rootScope','$timeout','$http', 
  function ($rootScope, $timeout, $http) {    
    
    var self = this;
    this.set = function (aplicacao) {
      self.aplicacao = aplicacao
      $rootScope.$broadcast('aplicacao', aplicacao)
    }

    this.getDate = function () {
      $rootScope.$broadcast('aplicacao:loading', true)
      $http.post('/controller/plataforma/aplicacao/getdate', self.aplicacao)
        .success(function (response) {
          $rootScope.$broadcast('aplicacao:loading', false)
          $rootScope.$broadcast('aplicacao', response)
        })
        .error(function (response) {
          $rootScope.$broadcast('aplicacao:loading', false)
          $rootScope.$broadcast('aplicacao', response)
        })
    };

    this.login = function(email,password){
      $rootScope.$broadcast('aplicacao:loading', true)
      $http.post('/controller/plataforma/aplicacao/login', {email:email, password:password})
      .success(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:login', response)
      }).error(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:login', response)
      });
    };

    this.logout = function(){
      $rootScope.$broadcast('aplicacao:loading', true)
      $http.post('/controller/plataforma/aplicacao/logout', self.aplicacao)
      .success(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:logout', response)
      }).error(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:logout', response)
      });
    };

    this.checkLogin = function () {
      $rootScope.$broadcast('aplicacao:checklogin:loading', true)
      $http.post('/controller/plataforma/aplicacao/checklogin', self.aplicacao)
        .success(function (response) {
          $rootScope.$broadcast('aplicacao:checklogin:loading', false)
          $rootScope.$broadcast('aplicacao:checklogin', response)
        })
        .error(function (response) {
          $rootScope.$broadcast('aplicacao:checklogin:loading', false)
          $rootScope.$broadcast('aplicacao:checklogin', response)
        })
    };

    this.password = function(email){
      $rootScope.$broadcast('aplicacao:loading', true)
      $http.post('/controller/plataforma/aplicacao/password', {email:email})
      .success(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:password', response)
      }).error(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:password', response)
      });
    };

    this.updatepassword = function(token,password){
      $rootScope.$broadcast('aplicacao:loading', true)
      $http.post('/controller/plataforma/aplicacao/updatepassword', {token:token, password:password})
      .success(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:updatepassword', response)
      }).error(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:updatepassword', response)
      });
    };

    this.checkToken = function (token) {
      $rootScope.$broadcast('aplicacao:checktoken:loading', true)
      $http.post('/controller/plataforma/aplicacao/checktoken', {token:token})
        .success(function (response) {
          $rootScope.$broadcast('aplicacao:checktoken:loading', false)
          $rootScope.$broadcast('aplicacao:checktoken', response)
        })
        .error(function (response) {
          $rootScope.$broadcast('aplicacao:checktoken:loading', false)
          $rootScope.$broadcast('aplicacao:checktoken', response)
        })
    };
}])


.factory('usuarioFactory', ['$rootScope',function($rootScope){
    var usuario = [];
    usuario.get = function(){
      return this.usuario;
    }
    usuario.set = function(usuario){
      if(usuario.results){
        this.usuario = usuario.results;
      }else{
        this.usuario = usuario;
      }
      this.broadcastCliente();
    }
    usuario.broadcastCliente = function(){
      $rootScope.$broadcast('handleBroadcast');
    }
    return usuario;
}])

.factory('sessionFactory', ['$window',function($window){
  return {
    set: function(key,value){
      return $window.sessionStorage.setItem(key,value);
    },
    get: function(key){
      return $window.sessionStorage.getItem(key);
    },
    destroy: function(key){
      return $window.sessionStorage.removeItem(key);
    }
  };
}])
