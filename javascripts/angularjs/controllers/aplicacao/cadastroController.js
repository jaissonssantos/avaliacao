'use strict';

angular.module('app').controller('cadastroController', ['$scope', '$http', 'authenticateMarketplaceSrv', function($scope, $http, authenticateMarketplaceSrv){

		$scope.ismessagesignup 			= false;
		$scope.messagefeedbacksignup 	= undefined;
		$scope.signupuser 				= [];
		$scope.isloading 				= false;
		$scope.submitting 				= false;

		$scope.signup = function( signupuser ){
			if( $scope.formSignup.$valid ){
				authenticateMarketplaceSrv.signup( signupuser, $scope );
			}
		}

	}]);