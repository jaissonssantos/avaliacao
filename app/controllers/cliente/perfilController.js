'use strict';

angular.module('app').controller('perfilController', ['$rootScope','$scope','$http','$location','$routeParams','$window','aplicacaoService','clienteService','clienteFactory','agendamentoService',
	function($rootScope,$scope,$http,$location,$routeParams,$window,aplicacaoService,clienteService,clienteFactory,agendamentoService){

		$scope.clients = {};
		$scope.clientes = $scope.cliente = {};
		$scope.results = {
			agenda: {},
			favorito: {},
			avaliacao: {}
		};
		$scope.agendamentos = {};
		$scope.favoritos = {};
		$scope.avaliacoes = {};
		$scope.cpf = {};
		$scope.tab = $routeParams.tab == 'perfil' || $routeParams.tab == 'senha' || $routeParams.tab == 'agendamentos' || $routeParams.tab == 'favoritos' || $routeParams.tab == 'avaliacoes' ? $routeParams.tab : undefined;
		$scope.totalItems 	= 0;
		$scope.currentPage 	= 1;
		$scope.numPerPage 	= 10;
		$scope.entryLimit 	= 5;

		$scope.$on('handleBroadcast', function(){
			$scope.clients = clienteFactory.get();
			if(!$scope.clients.id)
				$location.path('/entrar');
		});	

		$rootScope.$on('cliente:cpf', function(event, status) {
		    $scope.cpf = {
		      	found: (status == "found"),
		      	notfound: (status == "notfound"),
				loading: (status === "loading")
		    };
		});

		$rootScope.$on('cliente:update', function(event, status) {
			$scope.status = {
			  loading: (status == 'loading'),
			  success: (status == 'success'),
			  error: (status == 'error')
			};
		});

		$rootScope.$on('cliente:password', function(event, status) {
			$scope.status = {
			  loading_password: (status == 'loading'),
			  success_password: (status == 'success'),
			  error_password: (status == 'error')
			};
			if($scope.status.success_password){
				$scope.cleanFormPassword();
			}
		});

		$rootScope.$on('clientes:message:success', function(event, message) {
			$scope.success = message;
		});

		$rootScope.$on('clientes:message:error', function(event, message) {
			$scope.error = message;
		});

		$rootScope.$on('clientes:password:message:success', function(event, message) {
			$scope.success_password = message;
		});

		$rootScope.$on('clientes:password:message:error', function(event, message) {
			$scope.error_password = message;
		});

		$rootScope.$on('cliente', function(event, cliente){
			if(cliente.results!=undefined)
				$scope.cliente = cliente.results;
		});

		$rootScope.$on("cliente:loading", function(event, status){
			$scope.results.loading = status;
		});

		$rootScope.$on('cliente:agenda', function(event, agendamentos){
			$scope.agendamentos = agendamentos;
			if(agendamentos.results!=undefined){
				$scope.totalItems = agendamentos.count.results;
			}
		});

		$rootScope.$on("cliente:agenda:loading", function(event, status){
			$scope.results.agenda.loading = status;
		});

		$rootScope.$on('cliente:favorito', function(event, favoritos){
			$scope.favoritos = favoritos;
			// if(favoritos.results!=undefined){
			// 	$scope.totalItems = favoritos.count.results;
			// }
		});

		$rootScope.$on("cliente:favorito:loading", function(event, status){
			$scope.results.favorito.loading = status;
		});

		$rootScope.$on('cliente:avaliacao', function(event, avaliacoes){
			$scope.avaliacoes = avaliacoes;
		});

		$rootScope.$on("cliente:avaliacao:loading", function(event, status){
			$scope.results.avaliacao.loading = status;
		});

		$rootScope.$on('agendamento:cancel', function(event, status) {
			$scope.status = {
			  loading_promise: (status == 'loading'),
			  success_promise: (status == 'success'),
			  error_promise: (status == 'error')
			};
		});

		$rootScope.$on('agendamentos:cancel:message:success', function(event, message) {
			$scope.success_promise = message;
		});

		$rootScope.$on('agendamentos:cancel:message:error', function(event, message) {
			$scope.error_promise = message;
		});

		$scope.load = function(){
			clienteService.set($scope.cliente);
			clienteService.load();
		}

		$scope.schedule = function(filter){
			$scope.currentPage = 1;
			$scope.cliente.offset = 0;
			$scope.cliente.limit = $scope.numPerPage;
			$scope.cliente.filter = parseInt(filter);
			clienteService.set($scope.cliente);
			clienteService.loadSchedule();
		}

		$scope.favorite = function(){
			clienteService.loadFavorite();
		}

		$scope.rating = function(){
			clienteService.loadRating();
		}

		$scope.update = function(){
			clienteService.set($scope.cliente);
			clienteService.update();
		}

		$scope.updatepassword = function(){
			clienteService.set($scope.cliente);
			clienteService.password();
		}

		$scope.checkCpf = function() {
			clienteService.checkCpf($scope.cliente.cpf);
		};

		$scope.cleanFormPassword = function(){
			$scope.cliente.senhaatual = $scope.cliente.novasenha =  $scope.cliente.confirmanovasenha = undefined;
		}

		$scope.cancel = function(agendamento,filter){
			var confirm = $window.confirm('Deseja realmente cancelar seu agendamento de ' + agendamento.servico + '?');
			if(confirm){
				agendamentoService.set(agendamento);
				agendamentoService.cancel();

				$scope.schedule(filter);
			}
		}

		$scope.changePaginate = function(filter){
			$scope.cliente.offset = ($scope.currentPage - 1) * $scope.numPerPage;
			$scope.cliente.limit = $scope.numPerPage;
			$scope.cliente.filter = parseInt(filter);
			clienteService.set($scope.cliente);
			clienteService.loadSchedule();
		};
		
		if(!$scope.agendamentos.length && $scope.tab=='agendamentos')
				$scope.schedule(2);

		if(!$scope.favoritos.length && $scope.tab=='favoritos')
				$scope.favorite();

		if(!$scope.avaliacoes.length && $scope.tab=='avaliacoes')
				$scope.rating();

	}]);