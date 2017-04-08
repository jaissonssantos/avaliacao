'use strict'; 

app
  .controller('adicionarAgendamentoController', ['$rootScope','$scope','$http','$uibModalInstance','agendamento','clienteService','profissionalService','agendaService',
      function($rootScope,$scope,$http,$uibModalInstance,agendamento,clienteService,profissionalService,agendaService){
  
    $scope.agenda = {
          data: moment(agendamento.start).format("DD/MM/YYYY"),
          horario: moment(agendamento.start).format("HH:mm"),
          duracao: parseInt(Math.abs((agendamento.start.getTime() - agendamento.end.getTime()) / 60000)),
          duracao_final: moment(agendamento.end).format("HH[h]mm[m]"),
          profissional: agendamento.profissional,
          dtainicio: moment(agendamento.start).format("YYYY-MM-DD HH:mm:ss"),
          dtafim: moment(agendamento.end).format("YYYY-MM-DD HH:mm:ss")
    };
		$scope.servico = {}; 
		$scope.servicos = [];
		$scope.clientes = $scope.cliente = {};
		$scope.results = {
      profissional: {}
    };
		$scope.duracao = [];

    /*set default params search*/
    $scope.currentSearch = 1; 

    $rootScope.$on('agenda:save', function(event, status) {
          $scope.status = {
                loading: (status == 'loading'),
                success: (status == 'success'),
                error: (status == 'error')
          };
    });

		$scope.$on("cliente:nome", function(event, clientes){
		  $scope.clientes = clientes;
	  });

		$scope.$on("clientes:loading", function(event, status){
			$scope.results.loading = status;
		});

    $scope.$on("profissional:loading", function(event, status){
      $scope.results.profissional.loading = status;
    });
		
		$scope.$on("profissional", function(event, profissional){
			$scope.servico = profissional.servicos;
		});

		$rootScope.$on('agendas:message:success', function(event, message){
			$scope.success = message.success;
      $scope.agenda.results = message.results;
		});

		$rootScope.$on('agendas:message:error', function(event, message){
			$scope.error = message;
		});

    $scope.loadServicos = function(){
      profissionalService.set({id: agendamento.profissional});
      profissionalService.load();
    }

    $scope.durationTime = function(){
      var start = agendamento.start;
      var duration = new Date((start.getTime()+($scope.agenda.duracao*60*1000)));  
      agendamento.end = duration;
      $scope.agenda.dtafim = moment(duration).format("YYYY-MM-DD HH:mm:ss");
      $scope.agenda.duracao_final = moment(duration).format("HH[h]mm[m]");
    }

		$scope.addServico = function(idservico){
      		var exists = 0;
      		$scope.servico.forEach(function(servico){
      			if(servico.id == idservico){
      				$scope.servicos.forEach(function(selected){
      					if(selected.id == idservico) exists = 1;
      				}); 
      				if(!exists) $scope.servicos.push(servico);
      			}
      		});
		};

		$scope.delServico = function(idservico){
			$scope.servicos.forEach(function(selected, index){
				if(selected.id == idservico) $scope.servicos.splice(index, 1);
			});
		}

		$scope.search = function(event){
			if($scope.cliente.nome != undefined && $scope.cliente.nome.length >= 3){
          if(event.keyCode!=40&&event.keyCode!=38&&event.keyCode!=37&&event.keyCode!=39&&event.keyCode!=13){
                clienteService.set($scope.cliente);
                clienteService.checkCliente();
          }
          //keyPress down
          if(event.keyCode==40 && ($scope.currentSearch+1)<= $scope.clientes.results.length){
                $scope.setCurrentSearch($scope.currentSearch+1);
          }
          //keyPress up
          if(event.keyCode==38 && ($scope.currentSearch-1) >= 1){
                $scope.setCurrentSearch($scope.currentSearch-1);
          }
          //KeyPress enter
          if(event.keyCode==13) {
                $scope.setCliente($scope.clientes.results[$scope.currentSearch-1]);
          }
			}else{
				$scope.clientes.results = undefined;
        $scope.cliente.id = undefined;
        $scope.cliente.cpf = undefined;
			}
		}

    $scope.isCurrentSearch = function(index) {
      if(index === $scope.currentSearch) {
        return true;
      }else{
        return false;
      }
    }
    $scope.setCurrentSearch = function(index){
       $scope.currentSearch = index;
    }

		$scope.setCliente = function(cliente){
			$scope.cliente = cliente;
			$scope.clientes.results = undefined;
      $scope.currentSearch=1;
		}

    $scope.loadDuracao = function(){
      for(var i=1;i<33;i++){
        var tempo = 15;
        var tmptotal = tempo * i;
        var hours = Math.floor(tmptotal/60);
        var minutes = tmptotal % 60;
        if(hours < 10)
          hours = hours;
        if(minutes < 10)
          minutes = "0"+minutes;  
        $scope.duracao.push({tempo: tmptotal, hora: hours+"h"+minutes+'m'});  
      }
    }

		$scope.save = function(){
      $scope.agenda.cliente = $scope.cliente;
      $scope.agenda.profissional = $scope.agenda.profissional;
      $scope.agenda.servicos = $scope.servicos;
      agendaService.set($scope.agenda);
      agendaService.save();
		};

		$scope.cancel = function () {
		  $uibModalInstance.dismiss($scope.agenda.results);
		};

	}]);		
	