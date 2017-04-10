
angular.module('app').controller('adicionarUsuarioController', ['$location', '$rootScope', '$scope', '$routeParams', 'usuarioService', 'profissionalService', function($location, $rootScope, $scope, $routeParams, usuarioService, profissionalService){

	$scope.usuario = $scope.email = $scope.login = {};
	$scope.usuario.profissionais = [];

	$rootScope.$on('usuario:save', function(event, status) {
    $scope.status = {
      loading: (status == 'loading'),
      success: (status == 'success'),
      error: (status == 'error')
    };

		if($scope.status.success){
			$location.path('/gestor/usuarios');
		}

  });

	$rootScope.$on('usuario:email', function(event, status) {
    $scope.email = {
      found: (status == "found"),
      notfound: (status == "notfound"),
			loading: (status === "loading")
    };
  });

	$rootScope.$on('usuario:login', function(event, status) {
    $scope.login = {
      found: (status == "found"),
      notfound: (status == "notfound"),
			loading: (status === "loading")
    };
  });

	$rootScope.$on('profissionais', function(event, profissionais) {
		$scope.profissionais = profissionais.results;
	});

	$scope.addProfissional = function(idprofissional){
		var exists = 0;
		$scope.profissionais.forEach(function(profissional){
			if(profissional.id == idprofissional){
				$scope.usuario.profissionais.forEach(function(selected){
					if(selected.id == idprofissional) exists = 1;
				});
				if(!exists) $scope.usuario.profissionais.push(profissional);
			}
		});
	};

	$scope.delProfissional = function(idprofissional){
		$scope.usuario.profissionais.forEach(function(selected, index){
			if(selected.id == idprofissional) $scope.usuario.profissionais.splice(index, 1);
		});
	};

	$scope.loadProfissionais = function(){
		profissionalService.getList();
	};

	$scope.save = function() {
		usuarioService.set($scope.usuario);
		usuarioService.save();
	};

	$scope.checkEmail = function() {
		usuarioService.checkEmail($scope.usuario);
	};

	$scope.checkLogin = function() {
		usuarioService.checkLogin($scope.usuario);
	};

}]);