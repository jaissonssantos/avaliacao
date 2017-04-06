
angular.module('app').controller('adicionarServicoController', ['$location', '$rootScope', '$scope', 'servicoService', 'profissionalService', 'categoriaService', function($location, $rootScope, $scope, servicoService, profissionalService, categoriaService){

	$scope.servico = {
		nome: null,
		descricao: null,
		valor: null,
		valorpromocao: null,
		idusuario: null,
		idservico_categoria: null,
		promocao: 0
	};

	$rootScope.$on('servico:save', function(event, status) {
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

	$scope.save = function() {
		servicoService.set($scope.servico);
		servicoService.save();
	};

}]);
