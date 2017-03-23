'use strict'; 

angular.module('app').controller('headerController', ['$rootScope','$scope','$http','$location','clienteFactory','aplicacaoService',
	function($rootScope,$scope,$http,$location,clienteFactory,aplicacaoService){

	$scope.clients = {};

	$scope.$on('handleBroadcast', function(){
		$scope.clients = clienteFactory.get();
	});	

	$rootScope.$on('aplicacao:checklogin', function(event, aplicacao) {
		if(aplicacao) clienteFactory.set(aplicacao);
	});		

	$rootScope.$on('aplicacao:logout', function(event, aplicacao) {
		if(aplicacao.success) $location.path('/entrar');
	});	

	$scope.getCheckLoginActive = function(){
		aplicacaoService.checkLogin();
	}

	$scope.logout = function(){
		aplicacaoService.logout();
	}
	$scope.search = function () {
		if (!$scope.querySearch) {
			return
		}
		$location.url('/busca?services=' + $scope.querySearch)
	}
}])