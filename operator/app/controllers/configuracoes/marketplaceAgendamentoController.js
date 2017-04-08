'use strict'; 

app
	.controller('marketplaceAgendamentoController', ['$scope', '$rootScope', '$http', 'Upload', '$timeout', 'marketplaceAgendamentoService', function($scope, $rootScope, $http, Upload, $timeout, marketplaceAgendamentoService){

		/*variables*/
		$scope.agendamento 		= {};

		$rootScope.$on('agendamento', function(event, agendamento){
			$scope.agendamento = agendamento;
		});

		$rootScope.$on('agendamento:update', function(event, status){
			$scope.status = {
				loading: (status == 'loading'),
				success: (status == 'success'),
				error: (status == 'error')
			}
		});

		$rootScope.$on('agendamentos:message:success', function(event, message){
			$rootScope.success = message;
		});

		$rootScope.$on('agendamentos:message:error', function(event, message){
			$rootScope.error = message;
		});

		$scope.load = function(){
			marketplaceAgendamentoService.load();
		}

		$scope.update = function(){
			marketplaceAgendamentoService.set($scope.agendamento);
			marketplaceAgendamentoService.update();
		}

	}]);