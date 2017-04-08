app
	.service('marketplaceEstabelecimentoService', ['$rootScope', '$timeout', '$http', 'Upload', function($rootScope, $timeout, $http, Upload) {

		var self = this;

		this.set = function(estabelecimento){
			self.estabelecimento = estabelecimento;
			$rootScope.$broadcast("estabelecimento", estabelecimento);
		}

		this.load = function(){
			$rootScope.$broadcast("estabelecimento:loading", true);
			$http.post("/controller/plataforma/configuracoes/getestabelecimento")
			.success(function(item){
				$rootScope.$broadcast("estabelecimento:loading", false);
				$rootScope.$broadcast("estabelecimento", item);
			})
			.error(function(item){
				$rootScope.$broadcast("estabelecimento:loading", false);
				$rootScope.$broadcast("estabelecimento", item);
			});
		}

		this.update = function(){
			$rootScope.$broadcast("estabelecimento:update", "loading");
			var $promise = Upload.upload({ url: "/controller/plataforma/configuracoes/updateestabelecimento", data: self.estabelecimento });
			$promise.then(function(item){
				$rootScope.$broadcast("estabelecimento:update", "success");
				$rootScope.$broadcast("estabelecimentos:message:success", item.data.success);
				$timeout(function(){
					$rootScope.$broadcast("estabelecimentos:message:success", "");
				}, 5000);
			}, function(item) {
				$rootScope.$broadcast("estabelecimento:update", "error");
				$rootScope.$broadcast("estabelecimentos:message:error", item.data.error);
				$timeout(function(){
					$rootScope.$broadcast("estabelecimentos:message:error", "");
				}, 5000);
			});
		}

	}]);