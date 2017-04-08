
angular.module('app').controller('adicionarClienteController', ['$location', '$rootScope', '$scope', 'cepService', 'estadocidadeService', 'usuarioService', 'clienteService', function($location, $scope, $rootScope, cepService, estadocidadeService, usuarioService, clienteService){

	$scope.cliente = {
	  idestado: null,
	  idcidade: null,
	  nome: null,
	  datanascimento: null,
	  email: null,
	  cpf: null,
	  telefonecelular: null,
	  telefonecomercial: null,
	  cep: null,
	  bairro: null,
	  logradouro: null,
	  numero: null,
	  complemento: null
	};
	$scope.email = $scope.login = $scope.endereco = $scope.cpf = {};

	$rootScope.$on('cliente:save', function(event, status) {
		$scope.status = {
			loading: (status == 'loading'),
			success: (status == 'success'),
			error: (status == 'error')
		};

		if($scope.status.success){
			$location.path('/gestor/clientes');
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

	$scope.save = function() {
		clienteService.set($scope.cliente);
		clienteService.save();
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
