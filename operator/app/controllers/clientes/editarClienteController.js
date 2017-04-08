
angular.module('app').controller('editarClienteController', ['$location', '$rootScope', '$scope', '$routeParams', 'cepService', 'estadocidadeService', 'usuarioService', 'clienteService', function($location, $scope, $rootScope, $routeParams, cepService, estadocidadeService, usuarioService, clienteService){

	$scope.cliente = {
	  id: $routeParams.id
	};
	$scope.results = {};
	$scope.email = $scope.login = $scope.endereco = $scope.cpf = {};

	$scope.$on("cliente", function(event, cliente){
		$scope.cliente = cliente;
		if($scope.cliente.error != undefined){
			$location.path('/404');
		}
	});

	$scope.$on("cliente:loading", function(event, status){
		$scope.results.loading = status;
	});

	$rootScope.$on('cliente:update', function(event, status) {
		$scope.status = {
			loading: (status == 'loading'),
			success: (status == 'success'),
			error: (status == 'error')
		};

		if($scope.status.success){
			$location.path('/clientes');
		}
	});

	$rootScope.$on('cliente:email', function(event, status) {
		$scope.email = {
			found: (status == "found"),
			notfound: (status == "notfound"),
			loading: (status === "loading")
		};
	});

	$rootScope.$on('cliente:cpf', function(event, status) {
		$scope.cpf = {
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

	$rootScope.$on('endereco:cep', function(event, status) {
		$scope.endereco = {
			loading: (status == 'loading'),
			loaded: (status == 'loaded'),
			error: (status == 'error')
		};
	});


	$rootScope.$on('estados', function(event, estados) {
		$scope.estados = estados;
		$scope.cliente.idestado = estados[0].idestado;
		estadocidadeService.loadCidades(estados[0].idestado);
	});

	$rootScope.$on('cidades', function(event, cidades) {
		$scope.cidades = cidades;
		$scope.cliente.idcidade = cidades[0].idcidade;
	});

	$rootScope.$on('endereco', function(event, endereco) {
		$scope.cliente.bairro = endereco.bairro;
		$scope.cliente.logradouro = endereco.logradouro;
	});

	$scope.load = function(){
		clienteService.set($scope.cliente);
		clienteService.load();
	}

	$scope.update = function() {
		clienteService.set($scope.cliente);
		clienteService.update();
	};

	$scope.searchCep = function() {
		cepService.searchCep($scope.cliente.cep);
	};

	$scope.loadCidades = function(){
		estadocidadeService.loadCidades($scope.cliente.idestado);
	};

	$scope.loadEstados = function(){
		estadocidadeService.loadEstados();
	};

	$scope.checkCpf = function() {
		clienteService.checkCpf($scope.cliente);
	};

	$scope.checkEmail = function() {
		clienteService.checkEmail($scope.cliente);
	};

	$scope.checkLogin = function() {
		usuarioService.checkLogin($scope.cliente.login);
	};

}]);
