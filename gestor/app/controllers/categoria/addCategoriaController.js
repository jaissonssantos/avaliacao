
angular.module('app').controller('addCategoriaController', ['$routeParams', '$location', '$rootScope', '$scope', 'categoriaService', function($routeParams, $location, $rootScope, $scope, categoriaService){

	$scope.categoria = {
		id: $routeParams.id
	};

	$rootScope.$on('categoria', function(event, categoria) {
		$scope.categoria = categoria;
	});

	$rootScope.$on('categoria:save', function(event, status) {
    $scope.status = {
      loading: (status == 'loading'),
      success: (status == 'success'),
      error: (status == 'error')
    };

		if($scope.status.success){
			$location.path('/gestor/categorias);
		}

  	});

  	$rootScope.$on('categoria:update', function(event, status) {
    $scope.status = {
      loading: (status == 'loading'),
      success: (status == 'success'),
      error: (status == 'error')
    };

		if($scope.status.success){
			$location.path('/gestor/categorias');
		}

  	});

	$scope.save = function() {
		categoriaService.set($scope.categoria);
		categoriaService.save();
	};
	$scope.load = function() {
		categoriaService.set($scope.categoria);
		categoriaService.load();
	};
	$scope.update = function() {
		categoriaService.set($scope.categoria);
		categoriaService.update();
	};

}]);
