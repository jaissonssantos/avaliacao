'use strict';

angular.module('app').controller('headerController', ['$rootScope','$scope','$location','usuarioFactory','aplicacaoService',
	function($rootScope,$scope,$location,usuarioFactory,aplicacaoService){

	$scope.users = $rootScope.users;

	$rootScope.$on('aplicacao:logout', function(event, aplicacao) {
		if(aplicacao.success) $location.path('/gestor/entrar');
	});	

	$scope.logout = function(){
		aplicacaoService.logout();
	}

}]);