'use strict';

angular.module('app').controller('panelSchedulingCtrl', ['$scope', '$http', '$location', '$timeout', '$routeParams', function($scope, $http, $location, $timeout, $routeParams){

		$scope.shedulingCurrentDate 	= [];
		$scope.shedulingPreviousDate 	= [];
		$scope.shedulingAllDate 		= [];
		$scope.submitting   			= false;
		$scope.isloading    			= false;
		$scope.messagewarning 			= false;
		$scope.messagesuccess 			= false;
		$scope.messagefeedback 			= undefined;
		$scope.progress 				= [{
			submitting: false
		}];
		$scope.tabshow 					= $routeParams.tab == 'perfil' || $routeParams.tab == 'agendamentos' ? $routeParams.tab : undefined;

		$scope.getInfoShedulingCurrentDateUser = function(){
			$scope.isloading = true;
			var $promise = $http.post('/controller/marketplace/getinfoshedulingcurrentdateuser');
			$promise.then(function(item){
				$scope.shedulingCurrentDate = item.data;
				$scope.isloading = false;
			});
		}

		$scope.getInfoShedulingPreviousDateUser = function(){
			$scope.isloading = true;
			var $promise = $http.post('/controller/marketplace/getinfoshedulingpreviousdateuser');
			$promise.then(function(item){
				$scope.shedulingPreviousDate = item.data;
				$scope.isloading = false;
			});
		}

		$scope.getInfoShedulingAllDateUser = function(){
			$scope.isloading = true;
			var $promise = $http.post('/controller/marketplace/getinfoshedulingalldateuser');
			$promise.then(function(item){
				$scope.shedulingAllDate = item.data;
				$scope.isloading = false;
			});
		}

		$scope.cancelSheduling = function( agendamento, evento ){
			if( confirm("Você tem certeza que quer cancelar esse agendamento?") ){
				$scope.progress.submitting = true;
				var $promise = $http.post('/controller/marketplace/cancelsheduling', {hash: agendamento.id});
				$promise.then(function(item){
					if( item.data.status == 'success' ){
						$scope.messagesuccess = true;
					}else if( item.data.status == 'error' ){
						$scope.messagewarning = true;
					}
					$scope.messagefeedback = item.data.message;
					switch( evento ){
						case 'all':
							$scope.getInfoShedulingAllDateUser();
						break;
						case 'current':
							$scope.getInfoShedulingCurrentDateUser();
						break;
						case 'previous':
							$scope.getInfoShedulingPreviousDateUser();
						break;
					}
					$scope.progress.submitting = false;
					/*hidden message in 5 seconds*/
					$timeout(function(){
						$scope.messagesuccess = false;
						$scope.messagewarning = false;
					}, 5000);
				});
			}
		}

		/*init function call*/
			/*get information sheduling current date user*/
			$scope.getInfoShedulingCurrentDateUser();

	}]);