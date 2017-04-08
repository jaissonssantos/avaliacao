'use strict';

app
	.controller('signupUserCtrl', ['$scope', '$http', 'authenticateMarketplaceSrv', function($scope, $http, authenticateMarketplaceSrv){

		$scope.ismessagesignup 	= false;
		$scope.messagefeedbacksignup 	= undefined;
		$scope.signupuser 				= [];
		$scope.isloading 				= false;
		$scope.submitting 				= false;

		$scope.signup = function( signuser ){
			console.log('aqui');
			if( $scope.formSignup.$valid ){
				authenticateMarketplaceSrv.signup( signuser, $scope );
			}
		}

	}]);