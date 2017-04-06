app
	.service('marketplaceTagsService', ['$rootScope', '$timeout', '$http', function($rootScope, $timeout, $http) {

		var self = this;

		this.set = function(parametro){
			self.parametro = parametro;
			$rootScope.$broadcast("parametro", parametro);
		}

		this.load = function(){
			$rootScope.$broadcast("parametro:loading", true);
			$http.post("/controller/gestor/configuracoes/gettags")
			.success(function(item){
				$rootScope.$broadcast("parametro:loading", false);
				$rootScope.$broadcast("parametro", item);
			})
			.error(function(item){
				$rootScope.$broadcast("parametro:loading", false);
				$rootScope.$broadcast("parametro", item);
			});
		}

		this.update = function(){
			$rootScope.$broadcast("parametro:update", "loading");
			$http.post("/controller/gestor/configuracoes/updatetags", self.parametro)
			.success(function(item){
				$rootScope.$broadcast("parametro:update", "success");
				$rootScope.$broadcast("parametros:message:success", item.success);
				$timeout(function(){
					$rootScope.$broadcast("parametros:message:success", "");
				}, 5000);
			})
			.error(function(item){
				$rootScope.$broadcast("parametro:update", "error");
				$rootScope.$broadcast("parametros:message:error", item.error);
				$timeout(function(){
					$rootScope.$broadcast("parametros:message:error", "");
				}, 5000);
			});
		}

	}]);