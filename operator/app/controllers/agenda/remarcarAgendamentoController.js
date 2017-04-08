'use strict'; 

app
	.controller('remarcarAgendamentoController', ['$rootScope', '$scope', '$http', '$routeParams', '$location', '$uibModal', '$timeout', 'agendaService', 'servicoService', 'convenioService', function($rootScope, $scope, $http, $routeParams, $location, $uibModal, $timeout, agendaService, servicoService, convenioService){

		
		/*variable*/
		$scope.agenda = {
			id: $routeParams.id,
			reagenda: {
				data: undefined,
				horario: undefined,
				duracao: undefined
			}
		};
		$scope.results = {};
		$scope.horarios = {};
		$scope.duracao = [];
		$scope.convenios = $scope.convenio = {};
		$scope.total = 0;

		/*set default*/
		$scope.convenio.status = 1;

		$scope.$on("agenda", function(event, agenda){
			if(agenda.error) $location.path('/404');
			$scope.agenda = agenda;
			$scope.somarServico();
		});

		$rootScope.$on('agenda:update', function(event, status) {
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

		$scope.$on("horarios", function(event, horarios){
			$scope.horarios = horarios;
		});

		$scope.$on("convenios", function(event, convenios){
			$scope.convenios = convenios.results;
		});

		$rootScope.$on('agenda:time', function(event, status) {
	          $scope.timestatus = {
	                loading: (status == 'loading'),
	                success: (status == 'success'),
	                error: (status == 'error')
	          };
	    });

		$rootScope.$on('servicos', function(event, servicos) {
			$scope.servico = servicos.results;
		});

  		$scope.load = function(){
  			agendaService.set($scope.agenda);
  			agendaService.load();
  		}

  		$scope.loadConvenios = function(){
  			convenioService.set($scope.convenio);
	      	convenioService.getList();
	    }

  		$scope.loadServicos = function(){
			servicoService.getList();
		};

		/*--duração de atendimento--*/
		$scope.loadDuracao = function(){
		    for(var i = 1; i <= 5; i++ ){
		    	var tempo = parseInt($scope.agenda.duracao);
		    	var tmptotal = tempo * i;
		    	var hours = Math.floor(tmptotal/60);
		    	var minutes = tmptotal % 60;
		    	if(hours < 10)
		    		hours = "0"+hours;
		    	if(minutes < 10)
		    		minutes = "0"+minutes;	
		    	$scope.duracao.push({tempo: tmptotal, hora: hours+":"+minutes});	
		    }
		}

		$scope.changeData = function(){
			if($scope.agenda.reagenda.data!=undefined){
				var data = {
					data: $scope.agenda.reagenda.data,
					professional: $scope.agenda.idprofissional
				};
				$scope.loadDuracao();
				agendaService.getListOfTimes(data);
			}
		}

		$scope.addServico = function(idservico){
      		var exists = 0;
      		$scope.servico.forEach(function(servico){
      			if(servico.id == idservico){
      				$scope.agenda.servicos.forEach(function(selected){
      					if(selected.id == idservico) exists = 1;
      				}); 
      				if(!exists) $scope.agenda.servicos.push(servico);
      				if(!exists) $scope.somarServico();
      			}
      		});
		};

		$scope.delServico = function(idservico){
			$scope.agenda.servicos.forEach(function(selected, index){
				if(selected.id == idservico) $scope.agenda.servicos.splice(index, 1);
				$scope.somarServico();
			});
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

		$scope.update = function(){
			agendaService.set($scope.agenda);
  			agendaService.update();
		}

		$scope.cancelar = function () {
			$timeout(function() {
				$rootScope.$broadcast("agendas:message:success", "");
				$rootScope.$broadcast("agendas:message:error", "");
			}, 1000);
			$location.path('/agenda');
		}

}]);		
	