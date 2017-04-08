
angular.module('app').controller('editarEstabelecimentoController', ['$location', '$rootScope',  '$scope', '$routeParams', 'estabelecimentoService', 'segmentoService','estadocidadeService', function($location, $rootScope, $scope, $routeParams, estabelecimentoService, segmentoService, estadocidadeService){
	$scope.publicidade = {
    id: $routeParams.id
  }
	$scope.estabelecimento = {}

	$scope.estabelecimento.id = $routeParams.id;

	$rootScope.$on('estabelecimento', function(event, estabelecimento) {
    $scope.estabelecimento = estabelecimento;
		estadocidadeService.loadCidades(estabelecimento.idestado);
  });

	$rootScope.$on('servicos', function(event, servicos) {
		$scope.servicos = servicos.results;
	});
	
	$rootScope.$on('segmentos', function(event, segmentos) {
    $scope.segmentos = segmentos;
		$scope.estabelecimento.idsegmento = segmentos[0].id;
  });

	$rootScope.$on('planos', function(event, planos) {
    $scope.planos = planos;
		$scope.estabelecimento.idplano = planos[0].id;
  });

	$rootScope.$on('estados', function(event, estados) {
    $scope.estados = estados;
  });

	$rootScope.$on('cidades', function(event, cidades) {
    $scope.cidades = cidades;
  });

	$rootScope.$on('formaspagamento', function(event, formaspagamento) {
    $scope.formaspagamento = formaspagamento;
  });

	$rootScope.$on('estabelecimento:update', function(event, status) {
    $scope.status = {
      loading: (status == 'loading'),
      success: (status == 'success'),
      error: (status == 'error')
    };
		if($scope.status.success){
			$location.path('/gestor/estabelecimentos');
		}
  });

	$scope.load = function() {
		estabelecimentoService.set($scope.estabelecimento);
		estabelecimentoService.load();
	};

	$scope.update = function() {
		estabelecimentoService.set($scope.estabelecimento);
		estabelecimentoService.update();
	};

	$scope.searchCep = function() {
    cepService.searchCep($scope.estabelecimento.cep);
  };

	$scope.loadSegmentos = function(){
		segmentoService.getList();
	};

	$scope.loadPlanos = function(){
		estabelecimentoService.loadPlanos();
	};

	$scope.loadCidades = function(){
		if(!$scope.estabelecimento.idestado){
			return
		}
		estadocidadeService.loadCidades($scope.estabelecimento.idestado);
	};

	$scope.loadEstados = function(){
		estadocidadeService.loadEstados();
	};

	$scope.loadFormasPagamento = function() {
		formaPagamentoService.getList();
	};
	$scope.update = function () {
    estabelecimentoService.set($scope.estabelecimento)
    estabelecimentoService.update()
  }

}]);
