
angular.module('app').controller('editarProfissionalController', ['$location', '$rootScope',  '$scope', '$routeParams', 'profissionalService', function($location, $rootScope, $scope, $routeParams, profissionalService){

	$scope.profissional = {
		id: $routeParams.id
	};
	$scope.profissional.diastrabalho = [];

	$rootScope.$on('profissionais:message:error', function(event, message) {
		$rootScope.error = message;
	});

	$rootScope.$on('profissional', function(event, profissional) {
	    $scope.profissional = profissional;
	    if(profissional.diastrabalho){
	    	$scope.profissional.diastrabalho.forEach(function(diatrabalho, index){
	    		if(diatrabalho.almoco_inicio == '0000')
	    			diatrabalho.almoco_inicio = null;
	    		if(diatrabalho.almoco_fim == '0000')
	    			diatrabalho.almoco_fim = null;
	    	});
	    }
	});

	$rootScope.$on('profissional:update', function(event, status) {
	    $scope.status = {
	      loading: (status == 'loading'),
	      success: (status == 'success'),
	      error: (status == 'error')
	    };

		if($scope.status.success){
			$location.path('configuracoes/list/staff/');
		}
  	});

	$scope.load = function() {
		profissionalService.set($scope.profissional);
		profissionalService.load();
	};

	$scope.update = function() {
		profissionalService.set($scope.profissional);
		profissionalService.update();
	};

	$scope.addDiaTrabalho = function(dia){
		$scope.profissional.diastrabalho.push({dia:dia});
	};

	$scope.delDiaTrabalho = function(dia){
		$scope.profissional.diastrabalho.forEach(function(diatrabalho, index){
			if(diatrabalho.dia == dia) $scope.profissional.diastrabalho.splice(index, 1);
		});
	};

}]);
