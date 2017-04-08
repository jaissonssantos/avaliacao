angular.module('app').service('aplicacaoService', ['$rootScope','$timeout','$http', 
  function ($rootScope, $timeout, $http) {    

  	var self = this;
    this.set = function (aplicacao) {
      self.aplicacao = aplicacao
      $rootScope.$broadcast('aplicacao', aplicacao)
    }
	
	this.checkLogin = function () {
      $rootScope.$broadcast('aplicacao:checklogin:loading', true)
      $http.post('/controller/gestor/aplicacao/checklogin', self.aplicacao)
        .success(function (response) {
          $rootScope.$broadcast('aplicacao:checklogin:loading', false)
          $rootScope.$broadcast('aplicacao:checklogin', response)
        })
        .error(function (response) {
          $rootScope.$broadcast('aplicacao:checklogin:loading', false)
          $rootScope.$broadcast('aplicacao:checklogin', response)
        })
    };

    this.login = function(email,password){
      $rootScope.$broadcast('aplicacao:loading', true)
      $http.post('/controller/gestor/aplicacao/login', {email:email, password:password})
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
      $http.post('/controller/gestor/aplicacao/logout', self.aplicacao)
      .success(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:logout', response)
      }).error(function(response){
        $rootScope.$broadcast('aplicacao:loading', false)
        $rootScope.$broadcast('aplicacao:logout', response)
      });
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