angular.module('app').controller('listarPublicidadesController', ['$rootScope', '$scope', '$routeParams', 'publicidadeService',
function($rootScope, $scope, $routeParams, publicidadeService) {

	$scope.publicidades = $scope.publicidade = {};
	$scope.results = {};
	$scope.checkedItens = [];

	$scope.totalItems 	= 0;
	$scope.currentPage 	= 1;
	$scope.numPerPage 	= 10;
	$scope.entryLimit 	= 5;

	$rootScope.$on('publicidades:message:success', function(event, message) {
		$rootScope.success = message;
	});

	$scope.$on("publicidades", function(event, publicidades){
		$scope.checkedItens = [];
		$scope.selectedAll = false;
		$scope.totalItems = publicidades.count.results;
		$scope.publicidades = publicidades;
	});

	$scope.$on("publicidades:loading", function(event, status){
		$scope.results.loading = status;
	});

	$scope.setTab = function(statusTab) {
		$scope.checkedItens = [];
		$scope.selectedAll = false;
		$scope.currentPage = 1;
		$scope.tab = statusTab;
		$scope.publicidade.offset = 0;
		$scope.publicidade.limit = $scope.numPerPage;
		$scope.publicidade.status = statusTab;
		publicidadeService.set($scope.publicidade);
		publicidadeService.getList();
	};

	$scope.search = function(){
		publicidadeService.set($scope.publicidade);
		publicidadeService.getList();
	};

	$scope.setStatus = function(status){
		publicidadeService.setIds($scope.checkedItens);
		publicidadeService.setStatus(status);
	};

	$scope.changePaginate = function(){
		$scope.publicidade.offset = ($scope.currentPage - 1) * $scope.numPerPage;
		$scope.publicidade.limit = $scope.numPerPage;
		publicidadeService.getList();
	};

	$scope.checkItem = function (item) {
		if(item.selected){
			$scope.checkedItens.push(item.id);
		}else{
			var index = $scope.checkedItens.indexOf(item.id);
			$scope.checkedItens.splice(index, 1);
		}
	};

	$scope.checkAll = function () {
		$scope.selectedAll = !$scope.selectedAll;
		angular.forEach($scope.publicidades.results, function (item) {
			item.selected = $scope.selectedAll;
			if(item.selected){
				$scope.checkedItens.push(item.id);
			}else{
				var index = $scope.checkedItens.indexOf(item.id);
				$scope.checkedItens.splice(index, 1);
			}
		});
	};

}]);
