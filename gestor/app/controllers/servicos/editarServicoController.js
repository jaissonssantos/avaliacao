
angular.module('app').controller('editarServicoController', ['$location', '$rootScope', '$scope', '$routeParams', 'servicoService', 'profissionalService', 'categoriaService', function($location, $rootScope, $scope, $routeParams, servicoService, profissionalService, categoriaService){

	$scope.servico = {
		id: $routeParams.id
	};

	$rootScope.$on('servico', function(event, servico) {
    $scope.servico = servico;
  });

	$rootScope.$on('servico:update', function(event, status) {
    $scope.status = {
      loading: (status == 'loading'),
      success: (status == 'success'),
      error: (status == 'error')
    };

		if($scope.status.success){
			$location.path('/gestor/servicos');
		}

  });

	$rootScope.$on('profissionais', function(event, profissionais) {
		$scope.profissionais = profissionais.results;
		$scope.servico.idusuario = $scope.profissionais[0].idusuario;
	});

	$rootScope.$on('categorias', function(event, categorias) {
		$scope.categorias = categorias.results;
		$scope.servico.idservico_categoria = $scope.categorias[0].id;
	});

	$scope.loadProfissionais = function(){
		profissionalService.getList();
	};

	$scope.loadCategorias = function(){
		categoriaService.getList();
	};

	$scope.load = function() {
		servicoService.set($scope.servico);
		servicoService.load();
	};

	$scope.update = function() {
		servicoService.set($scope.servico);
		servicoService.update();
	};

}]);
