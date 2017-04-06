'use strict';

angular.module('app').controller('entrarController', ['$rootScope','$scope','$http','$timeout','$location','usuarioFactory','sessionFactory','aplicacaoService', 
	function($rootScope,$scope,$http,$timeout,$location,usuarioFactory,sessionFactory,aplicacaoService){

	$scope.usuario = {};
	$scope.results = {};

	$rootScope.$on('aplicacao:login', function(event, aplicacao) {
		if(aplicacao.error){
			$scope.error = aplicacao.error;	
			$timeout(function(){
	          	$scope.error = '';
	      	}, 5000);
		} 
		if(aplicacao.results){
			usuarioFactory.set(aplicacao.results);
			sessionFactory.set('ang_gestor_uid', aplicacao.results.id),
			sessionFactory.set('ang_gestor_name', aplicacao.results.name),
			sessionFactory.set('ang_gestor_login', aplicacao.results.login),
			sessionFactory.set('ang_gestor_email', aplicacao.results.email);
			$location.path('/gestor/dashboard');
		}
	});

	$rootScope.$on("aplicacao:loading", function(event, status){
		$scope.results.loading = status;
	});

	$scope.login = function(){
		aplicacaoService.login($scope.usuario.email, $scope.usuario.senha);
	}

}]);