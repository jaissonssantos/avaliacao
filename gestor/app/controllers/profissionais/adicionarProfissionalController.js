
angular.module('app').controller('adicionarProfissionalController', ['$location', '$rootScope', '$scope', '$routeParams','usuarioService', 'profissionalService', 'servicoService', function($location, $rootScope, $scope, $routeParams, usuarioService, profissionalService, servicoService){

	$scope.profissional = $scope.email = $scope.login = {};
	$scope.profissional.servicos = [];
	$scope.profissional.diastrabalho = [];


	$rootScope.$on('profissional:save', function(event, status) {
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

	$rootScope.$on('servicos', function(event, servicos) {
		$scope.servicos = servicos.results;
	});

	$scope.save = function() {
		profissionalService.set($scope.profissional);
		profissionalService.save();
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
		var isset = $scope.profissional.diastrabalho.filter(function(diatrabalho){
			return diatrabalho.dia === dia;
		});
		if(isset.length)
			return;
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
