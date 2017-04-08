'use strict'; 

app
	.controller('dashboardController', ['$rootScope', '$scope','dashboardService', function($rootScope, $scope, dashboardService){

		/*variables*/
		$scope.painel = {};
		$scope.results = {};

		$scope.$on("painel:loading", function(event, status){
			$scope.results.loading = status;
		});

		$rootScope.$on('painel', function(event, painel){
			$scope.painel = painel;
		});

		$scope.load = function(){
			dashboardService.load();
		}

}]);

