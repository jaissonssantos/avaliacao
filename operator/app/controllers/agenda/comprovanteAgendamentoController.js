'use strict'; 

app
	.controller('comprovanteAgendamentoController', ['$rootScope', '$scope', '$http', '$routeParams', '$location', '$uibModal', '$timeout', 'agendaService', 'servicoService', 'convenioService', function($rootScope, $scope, $http, $routeParams, $location, $uibModal, $timeout, agendaService, servicoService, convenioService){

		/*variable*/
		$scope.agenda = {
			id: $routeParams.id
		};
		$scope.results = {};
		$scope.total = 0;
		$scope.exists = 0;

		$scope.$on("agenda", function(event, agenda){
			if(agenda.error) $location.path('/404');
			$scope.agenda = agenda;
		});

		$scope.$on("agenda:loading", function(event, status){
			$scope.results.loading = status;
		});

		$scope.load = function(){
  			agendaService.set($scope.agenda);
  			agendaService.receipt();
  		}

  		$scope.calculoConvenio = function(){
  			if($scope.agenda.convenio!=undefined && $scope.exists){
  				
  			}else{
  				$scope.agenda.valor = $scope.agenda.valorconvenio;
  			}
  		}

  		$scope.calculoServicoValorPago = function(idservico){
  			var valor = 0;
  			/*sum total*/
			$scope.agenda.servicos.forEach(function(servico){
				if(servico.valorpororcamento==1){
					if(servico.id == idservico){
						valor += parseFloat(servico.valorpgto);
					}
				}else if(servico.valorpororcamento==0){
					if(servico.promocao==0){
						valor += parseFloat(servico.valor);
					}else if(servico.promocao==1){
						valor += parseFloat(servico.valorpromocao);
					}
				}
			});
  			$scope.agenda.valor = $scope.total = valor;
  		}

		$scope.somarServico = function(){
			/*clear total*/
			$scope.total = 0;
			/*sum total*/
			angular.forEach($scope.agenda.servicos, function(servico){
				$scope.total += parseFloat(servico.valor);
			});
		}

}]);		
	