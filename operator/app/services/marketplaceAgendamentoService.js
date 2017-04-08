app
	.service('marketplaceAgendamentoService', ['$rootScope', '$timeout', '$http', function($rootScope, $timeout, $http) {

		var self = this;

		this.set = function(agendamento){
			self.agendamento = agendamento;
			$rootScope.$broadcast("agendamento", agendamento);
		}

		this.load = function(){
			$rootScope.$broadcast("agendamento:loading", true);
			$http.post("/controller/plataforma/configuracoes/getagendamento")
			.success(function(item){
				$rootScope.$broadcast("agendamento:loading", false);
				$rootScope.$broadcast("agendamento", item);
			})
			.error(function(item){
				$rootScope.$broadcast("agendamento:loading", false);
				$rootScope.$broadcast("agendamento", item);
			});
		}

		this.update = function(){
			$rootScope.$broadcast("agendamento:update", "loading");
			$http.post("/controller/plataforma/configuracoes/updateagendamento", self.agendamento)
			.success(function(item){
				$rootScope.$broadcast("agendamento:update", "success");
				$rootScope.$broadcast("agendamentos:message:success", item.success);
				$timeout(function(){
					$rootScope.$broadcast("agendamentos:message:success", "");
				}, 5000);
			})
			.error(function(item){
				$rootScope.$broadcast("agendamento:update", "error");
				$rootScope.$broadcast("agendamentos:message:error", item.error);
				$timeout(function(){
					$rootScope.$broadcast("agendamentos:message:error", "");
				}, 5000);
			});
		}

	}]);