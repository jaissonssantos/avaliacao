'use strict'; 

app
	.controller('cancelarAgendamentoController', ['$rootScope' ,'$scope', '$http', '$uibModalInstance', '$timeout', 'agenda', 'agendaService', function($rootScope, $scope, $http, $uibModalInstance, $timeout, agenda, agendaService){

		/*variable*/
		$scope.agenda = {
			id: agenda.id,
			status: 5 /*cancelar*/,
			data: moment(agenda.time).format("DD/MM/YYYY"),
            horariom: moment(agenda.time).format("hh"),
            horarios: moment(agenda.time).format("mm"),
            cliente: agenda.cliente
		};

		$rootScope.$on('agenda:move', function(event, status) {
			$scope.status = {
				loading: (status == 'loading'),
				success: (status == 'success'),
				error: (status == 'error')
			};
		});

		$rootScope.$on('agendas:message:success', function(event, message) {
			$rootScope.success = message;
			$timeout(function() {
		        $rootScope.$broadcast("agendas:message:success", "");
		        $uibModalInstance.dismiss('cancel');
		    }, 5000);
		});

		$rootScope.$on('agendas:message:error', function(event, message) {
			$rootScope.error = message;
			$timeout(function() {
		        $rootScope.$broadcast("agendas:message:error", "");
		        $uibModalInstance.dismiss('cancel');
		    }, 5000);
		});

		$scope.ok = function(){
			agendaService.set($scope.agenda);
			agendaService.setStatus();
		}

		$scope.cancel = function () {
		    $uibModalInstance.dismiss('cancel');
		};

}]);		
	