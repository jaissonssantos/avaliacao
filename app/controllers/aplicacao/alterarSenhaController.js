'use strict';

angular.module('app').controller('alterarSenhaController', ['$rootScope','$scope','$routeParams','$location','clienteFactory','aplicacaoService', 
	function($rootScope,$scope,$routeParams,$location,clienteFactory,aplicacaoService){
		
	$scope.cliente = {
		token: $routeParams.token || undefined
	};
	$scope.results = {
		checktoken: {}
	};

	$scope.$on('handleBroadcast', function(){
		var clients = clienteFactory.get();
		if(clients.id)
			$location.path('cliente/agendamentos');
	});	

	$rootScope.$on('aplicacao:checklogin', function(event, aplicacao) {
		if(aplicacao.results) clienteFactory.set(aplicacao.results);
	});

	$rootScope.$on('aplicacao:updatepassword', function(event, aplicacao) {
		if(aplicacao.error){
			$scope.error = aplicacao.error;	
			$timeout(function(){
	          	$scope.error = '';
	      	}, 5000);
		}
		if(aplicacao.success) $scope.success = aplicacao.success;
	});

	$rootScope.$on("aplicacao:loading", function(event, status){
		$scope.results.loading = status;
	});

	$rootScope.$on('aplicacao:checktoken', function(event, aplicacao) {
		if(aplicacao.error) $scope.error = aplicacao.error;
	});

	$rootScope.$on("aplicacao:checktoken:loading", function(event, status){
		$scope.results.checktoken.loading = status;
	});

	$scope.update = function(){
		aplicacaoService.updatepassword($scope.cliente.token,$scope.cliente.senha);
	}

	$scope.checkToken = function(){
		aplicacaoService.checkToken($scope.cliente.token);
	}

	$scope.getCheckLoginActive = function(){
		aplicacaoService.checkLogin();
	}

}]);