
angular.module('app').controller('editarProfissionalController', ['$location', '$rootScope',  '$scope', '$routeParams','usuarioService', 'profissionalService', 'servicoService', function($location, $rootScope, $scope, $routeParams, usuarioService, profissionalService, servicoService){

	$scope.profissional = $scope.email = $scope.login = {};
	$scope.profissional.servicos = [];
	$scope.profissional.diastrabalho = [];

	$scope.profissional.id = $routeParams.id;

	$rootScope.$on('profissional', function(event, profissional) {
    $scope.profissional = profissional;
  });

	$rootScope.$on('servicos', function(event, servicos) {
		$scope.servicos = servicos.results;
	});

	$rootScope.$on('profissional:update', function(event, status) {
    $scope.status = {
      loading: (status == 'loading'),
      success: (status == 'success'),
      error: (status == 'error')
    };

		if($scope.status.success){
			$location.path('/gestor/profissionais');
		}

  });

	$rootScope.$on('profissional:email', function(event, status) {
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

	$scope.load = function() {
		profissionalService.set($scope.profissional);
		profissionalService.load();
	};

	$scope.update = function() {
		profissionalService.set($scope.profissional);
		profissionalService.update();
	};

	$scope.checkEmail = function() {
		profissionalService.checkEmail($scope.profissional);
	};

	$scope.checkLogin = function() {
		usuarioService.checkLogin($scope.profissional.login);
	};

	$scope.addServico = function(idservico){
		var exists = 0;
		$scope.servicos.forEach(function(servico){
			if(servico.id == idservico){
				$scope.profissional.servicos.forEach(function(selected){
					if(selected.id == idservico) exists = 1;
				});
				if(!exists) $scope.profissional.servicos.push(servico);
			}
		});
	};

	$scope.addDiaTrabalho = function(dia){
		$scope.profissional.diastrabalho.push({dia:dia});
	};

	$scope.delDiaTrabalho = function(dia){
		$scope.profissional.diastrabalho.forEach(function(diatrabalho, index){
			if(diatrabalho.dia == dia) $scope.profissional.diastrabalho.splice(index, 1);
		});
	};


	$scope.delServico = function(idservico){
		$scope.profissional.servicos.forEach(function(selected, index){
			if(selected.id == idservico) $scope.profissional.servicos.splice(index, 1);
		});
	};

	$scope.loadServicos = function(){
		servicoService.getList();
	};

}]);
