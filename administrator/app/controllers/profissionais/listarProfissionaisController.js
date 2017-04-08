angular.module('app').controller('listarProfissionaisController', ['$rootScope', '$scope', '$routeParams', 'profissionalService',
function($rootScope, $scope, $routeParams, profissionalService) {

	$scope.profissionais = $scope.profissional = {};
	$scope.results = {};
	$scope.checkedItens = [];

	$scope.totalItems 	= 0;
	$scope.currentPage 	= 1;
	$scope.numPerPage 	= 10;
	$scope.entryLimit 	= 5;

	$rootScope.$on('profissionais:message:success', function(event, message) {
		$rootScope.success = message;
	});

	$scope.$on("profissionais", function(event, profissionais){
		$scope.checkedItens = [];
		$scope.selectedAll = false;
		$scope.totalItems = profissionais.count.results;
		$scope.profissionais = profissionais;
	});

	$scope.$on("profissionais:loading", function(event, status){
		$scope.results.loading = status;
	});

	$scope.setTab = function(statusTab) {
		$scope.checkedItens = [];
		$scope.selectedAll = false;
		$scope.currentPage = 1;
		$scope.tab = statusTab;
		$scope.profissional.offset = 0;
		$scope.profissional.limit = $scope.numPerPage;
		$scope.profissional.status = statusTab;
		profissionalService.set($scope.profissional);
		profissionalService.getList();
	};

	$scope.search = function(){
		profissionalService.set($scope.profissional);
		profissionalService.getList();
	};

	$scope.setStatus = function(status){
		profissionalService.setIds($scope.checkedItens);
		profissionalService.setStatus(status);
	};

	$scope.changePaginate = function(){
		$scope.profissional.offset = ($scope.currentPage - 1) * $scope.numPerPage;
		$scope.profissional.limit = $scope.numPerPage;
		profissionalService.getList();
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
		angular.forEach($scope.profissionais.results, function (item) {
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
