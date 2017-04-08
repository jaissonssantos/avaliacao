'use strict'; 

app
	.controller('finalizarAgendamentoController', ['$rootScope', '$scope', '$http', '$routeParams', '$location', '$uibModal', '$timeout', 'agendaService', 'servicoService', 'convenioService', function($rootScope, $scope, $http, $routeParams, $location, $uibModal, $timeout, agendaService, servicoService, convenioService){

		/*variable*/
		$scope.agenda = {
			id: $routeParams.id,
			idformapagamento: 4 /*atendido ou finalizado*/
		};
		$scope.results = {};
		$scope.horarios = {};
		$scope.duracao = [];
		$scope.convenios = $scope.convenio = {};
		$scope.total = 0;
		$scope.exists = 0;

		$scope.$on("agenda", function(event, agenda){
			if(agenda.error) $location.path('/404');
			$scope.agenda = agenda;
			$scope.somarServico();
			if(!$scope.exists){
				$scope.agenda.valorpgto = $scope.total;
				$scope.agenda.valor = $scope.total;
			}
		});

		$rootScope.$on('agenda:finalize', function(event, status) {
	          $scope.status = {
	                loading: (status == 'loading'),
	                success: (status == 'success'),
	                error: (status == 'error')
	          };
	    });

		$scope.$on("agenda:loading", function(event, status){
			$scope.results.loading = status;
		});

		$rootScope.$on('agendas:message:success', function(event, message) {
			$scope.success = message;
		});

		$rootScope.$on('agendas:message:error', function(event, message) {
			$scope.error = message;
		});

		$scope.load = function(){
  			agendaService.set($scope.agenda);
  			agendaService.load();
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
					}else{
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

		$scope.finalize = function(){
			agendaService.set($scope.agenda);
			agendaService.finalize();
		}

		$scope.receipt = function () {
			$location.path('/agenda/comprovante/'+$routeParams.id);
		}

		$scope.cancel = function () {
			$location.path('/agenda');
		}

}]);		
	