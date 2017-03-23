'use strict'; 

angular.module('app').controller('paginaEstabelecimentosController', ['$rootScope','$scope','$http','$routeParams','$timeout','$location','estabelecimentoService','clienteFactory','aplicacaoService',
	function($rootScope,$scope,$http,$routeParams,$timeout,$location,estabelecimentoService,clienteFactory,aplicacaoService){
		
		$scope.estabelecimento = {
			hash: $routeParams.hash || undefined
		};
		$scope.contato = {};
		$scope.results = {};
		$scope.clients = {};

		$scope.$on('handleBroadcast', function(){
            $scope.clients = clienteFactory.get();
        });

		$rootScope.$on('estabelecimento', function(event, estabelecimento){
			$scope.estabelecimento = estabelecimento.results;
			if($routeParams.hash==undefined)
				$location.path('/404');
		});

		$rootScope.$on("estabelecimento:loading", function(event, status){
			$scope.results.loading = status;
		});

		$rootScope.$on('estabelecimentos:send:message:success', function(event, message) {
			$scope.success = message.success;
			$scope.contato = {
				nome: '',
				email: '',
				assunto: '',
				mensagem: ''
			};
		});

		$rootScope.$on('estabelecimentos:send:message:error', function(event, message) {
			$scope.error = message.error;
		});

		$rootScope.$on('estabelecimento:send', function(event, status) {
			$scope.status = {
			  loading: (status == 'loading'),
			  success: (status == 'success'),
			  error: (status == 'error')
			};
		});

		$scope.load = function(){
			estabelecimentoService.set($scope.estabelecimento);
			estabelecimentoService.load();
		}

		$scope.send = function(){
			estabelecimentoService.send(
				$scope.contato.nome,
				$scope.contato.email,
				$scope.estabelecimento.email,
				$scope.contato.assunto,
				$scope.contato.mensagem
			);
		}

	}])