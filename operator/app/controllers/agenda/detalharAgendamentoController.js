'use strict'; 

app
	.controller('detalharAgendamentoController', ['$rootScope' ,'$scope', '$http', '$uibModalInstance', 'agenda', 'agendaService', function($rootScope, $scope, $http, $uibModalInstance, agenda, agendaService){

		/*variable*/
		$scope.agenda = {
			id: agenda.id
		};
		$scope.results = {};
		$scope.total = 0;

		$scope.somarServico = function(){
			/*clear total*/
			$scope.total = 0;
			/*sum total*/
			angular.forEach($scope.agenda.servicos, function(value, key){
				if(value.valorpororcamento==1) $scope.exists++;
				if(value.valorpororcamento==1){
					$scope.total += parseFloat(0.00);
				}else if(value.valorpororcamento==0){
					if(value.promocao==0){
						$scope.total += parseFloat(value.valor);
					}else if(value.promocao==1){
						$scope.total += parseFloat(value.valorpromocao);
					}
				}
			});
		}

		$scope.$on("agenda", function(event, agenda){
			$scope.agenda = agenda;
			$scope.somarServico();
		});

		$scope.$on("agenda:loading", function(event, status){
			$scope.results.loading = status;
		});

		/*loads*/
  		agendaService.set($scope.agenda);
  		agendaService.load();

		$scope.cancel = function () {
		    $uibModalInstance.dismiss('cancel');
		};

}]);		
	