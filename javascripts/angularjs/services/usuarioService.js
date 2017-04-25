angular.module('app').service('usuarioService', ['$rootScope', '$timeout', '$http' , function ($rootScope, $timeout, $http) {
  var self = this

  this.set = function (usuario) {
    self.usuario = usuario
    $rootScope.$broadcast('usuario', usuario)
  }

  this.checkEmail = function(email){
    $rootScope.$broadcast("usuario:email", "loading");
    $http.post('/controller/guest/usuario/checkemail', {email: email})
      .then(function(response){
          $rootScope.$broadcast("usuario:email", "found");
      },
      function(response){
        if(response && response.data.error){
          $rootScope.$broadcast("usuario:email", "notfound");
        }
      });
  };

}]);
