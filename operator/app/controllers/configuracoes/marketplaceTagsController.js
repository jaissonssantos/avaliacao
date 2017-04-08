'use strict'; 

app
	.controller('marketplaceTagsController', ['$scope', '$rootScope', '$http', 'Upload', '$timeout', 'marketplaceTagsService', function($scope, $rootScope, $http, Upload, $timeout, marketplaceTagsService){

		/*variables*/
		$scope.parametro 		= {};

		$rootScope.$on('parametro', function(event, parametro){
			$scope.parametro = parametro;
		});

		$rootScope.$on('parametro:update', function(event, status){
			$scope.status = {
				loading: (status == 'loading'),
				success: (status == 'success'),
				error: (status == 'error')
			}
		});

		$rootScope.$on('parametros:message:success', function(event, message){
			$rootScope.success = message;
		});

		$rootScope.$on('parametros:message:error', function(event, message){
			$rootScope.error = message;
		});

		$scope.load = function(){
			marketplaceTagsService.load();
		}

		$scope.update = function(){
			marketplaceTagsService.set($scope.parametro);
			marketplaceTagsService.update();
		}

	}]);