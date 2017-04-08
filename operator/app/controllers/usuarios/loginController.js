angular.module('app').controller('loginController', ['$scope', '$http', '$location', 'usuarioService', function($scope, $http, $locationloginService, usuarioService) {

  $scope.user = [];

  $scope.login = function(user) {
    if ($scope.formLogin.$valid) usuarioService.login(user, $scope);
  };

  $scope.logout = function() {
    usuarioService.logout();
  };

  $scope.islogged = function() {
    var $promise = usuarioService.confirmLogin();
    $promise.then(function(item) {
      if(item.data.status == 'success') {
        usuarioService.setUserLogged(true, item.data.ang_session_name);
      }else if(item.data.status == 'error') {
        usuarioService.setUserLogged(false, undefined);
      }
    });
  };

}]);
