'use strict';

angular.module('app').controller('redefinirSenhaController', ['$rootScope','$scope','$http','$timeout','$location','clienteFactory','aplicacaoService', 
	function($rootScope,$scope,$http,$timeout,$location,clienteFactory,aplicacaoService){

	$scope.cliente = {};
	$scope.results = {};
	
	$scope.$on('handleBroadcast', function(){
		var clients = clienteFactory.get();
		if(clients.id)
			$location.path('cliente/agendamentos');
	});	

	$rootScope.$on('aplicacao:checklogin', function(event, aplicacao) {
		if(aplicacao.results) clienteFactory.set(aplicacao.results);
	});

	$rootScope.$on('aplicacao:password', function(event, aplicacao) {
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

	$scope.password = function(){
		aplicacaoService.password($scope.cliente.email);
	}

	$scope.getCheckLoginActive = function(){
		aplicacaoService.checkLogin();
	}

}]);