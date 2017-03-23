'use strict';

angular.module('app').controller('entrarController', ['$rootScope','$scope','$http','$timeout','$location','clienteFactory','sessionFactory','aplicacaoService', 
	function($rootScope,$scope,$http,$timeout,$location,clienteFactory,sessionFactory,aplicacaoService){

	$scope.cliente = {
		lembrarme: false
	};
	$scope.results = {};
	$scope.redirect = $location.search().redirect || undefined;
	
	$scope.$on('handleBroadcast', function(){
		var clients = clienteFactory.get();
		if(clients.id)
			$location.path('cliente/agendamentos');
	});

	$rootScope.$on('aplicacao:checklogin', function(event, aplicacao) {
		if(aplicacao.results) clienteFactory.set(aplicacao.results);
	});

	$rootScope.$on('aplicacao:login', function(event, aplicacao) {
		if(aplicacao.error){
			$scope.error = aplicacao.error;	
			$timeout(function(){
	          	$scope.error = '';
	      	}, 5000);
		} 
		if(aplicacao.results){
			clienteFactory.set(aplicacao.results);
			if($scope.cliente.lembrarme){
				sessionFactory.set('ang_markday_uid', aplicacao.results.id),
				sessionFactory.set('ang_markday_email', aplicacao.results.email), 
				sessionFactory.set('ang_markday_cpf', aplicacao.results.cpf),
				sessionFactory.set('ang_markday_password', $scope.cliente.senha); 
			}else{
				sessionFactory.destroy('ang_markday_uid'),
				sessionFactory.destroy('ang_markday_email'), 
				sessionFactory.destroy('ang_markday_cpf'),
				sessionFactory.destroy('ang_markday_password'); 
			}
			if($scope.redirect){
				$location.url($scope.redirect);
			}else{
				$location.path('cliente/agendamentos');
			}
		}
	});

	$rootScope.$on("aplicacao:loading", function(event, status){
		$scope.results.loading = status;
	});

	$scope.login = function(){
		aplicacaoService.login($scope.cliente.email, $scope.cliente.senha);
	}

	$scope.getLogin = function(){
		if(sessionFactory.get('ang_markday_email') && sessionFactory.get('ang_markday_password')){
			$scope.cliente.email = sessionFactory.get('ang_markday_email');
			$scope.cliente.senha = sessionFactory.get('ang_markday_password');
			$scope.cliente.lembrarme = true;
		}
	}

	$scope.getCheckLoginActive = function(){
		aplicacaoService.checkLogin();
	}

}]);