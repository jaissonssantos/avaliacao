angular.module('app').controller('listarServicosController', ['$rootScope', '$scope', '$routeParams', 'servicoService',
function($rootScope, $scope, $routeParams, servicoService) {

	$scope.servicos = $scope.servico = {};
	$scope.results = {};
	$scope.checkedItens = [];

	$scope.totalItems 	= 0;
	$scope.currentPage 	= 1;
	$scope.numPerPage 	= 10;
	$scope.entryLimit 	= 5;

	$rootScope.$on('servicos:message:success', function(event, message) {
		$rootScope.success = message;
	});

	$scope.$on("servicos", function(event, servicos){
		$scope.checkedItens = [];
		$scope.selectedAll = false;
		$scope.totalItems = servicos.count.results;
		$scope.servicos = servicos;
	});

	$scope.$on("servicos:loading", function(event, status){
		$scope.results.loading = status;
	});

	$scope.setTab = function(statusTab) {
		$scope.checkedItens = [];
		$scope.selectedAll = false;
		$scope.currentPage = 1;
		$scope.tab = statusTab;
		$scope.servico.offset = 0;
		$scope.servico.limit = $scope.numPerPage;
		$scope.servico.status = statusTab;
		servicoService.set($scope.servico);
		servicoService.getList();
	};

	$scope.search = function(){
		servicoService.set($scope.servico);
		servicoService.getList();
	};

	$scope.setStatus = function(status){
		servicoService.setIds($scope.checkedItens);
		servicoService.setStatus(status);
	};

	$scope.changePaginate = function(){
		$scope.servico.offset = ($scope.currentPage - 1) * $scope.numPerPage;
		$scope.servico.limit = $scope.numPerPage;
		servicoService.getList();
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
		angular.forEach($scope.servicos.results, function (item) {
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
