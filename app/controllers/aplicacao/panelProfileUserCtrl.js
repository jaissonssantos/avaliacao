'use strict';

angular.module('app').controller('panelProfileUserCtrl', ['$scope', '$http', '$location', '$timeout', 'Upload', '$routeParams', function($scope, $http, $location, $timeout, Upload, $routeParams){

		$scope.user 			= [];
		$scope.submitting   	= false;
		$scope.isloading    	= false;
		$scope.isloadingCPF 	= false;
		$scope.isloadingPhone 	= false;
		$scope.errorInvalid 	= 0;
		$scope.tabshow 			= $routeParams.tab == 'perfil' || $routeParams.tab == 'agendamentos' ? $routeParams.tab : undefined;

		$scope.getInfoProfileUser = function(){
			var $promise = $http.post('/controller/marketplace/getinfoprofileuser');
			$promise.then(function(item){
				$scope.user = item.data[0];
			});
		}

		$scope.saveprofile = function(user){
			/*verification invalid*/
			if( $scope.errorInvalid == 0 && $scope.formProfile.$valid ){
				/*show loading e submitting items informations user*/
				$scope.submitting = true;
				$scope.isloading = true;
				var $promise = Upload.upload({ url: '/controller/marketplace/updateprofileuser',  data: {name: user.name, cpf: user.cpf, phone: user.phone, file: user.image} });
				$promise.then(function(item){
					if( item.data.status == 'success' ){
						$scope.messagesuccess = true;
					}else if( item.data.status == 'error' ){
						$scope.messagewarning = true;
					}
					/*message feedback*/
					$scope.messagefeedbackps = item.data.message;
					/*hidden loading e submitting*/
					$scope.submitting = false;
					$scope.isloading = false;
					/*hidden message in 5 secondes*/
					$timeout(function(){
						$scope.messagesuccess = false;
						$scope.messagewarning = false;
					}, 5000);
				});
			}
		}

		$scope.checkCPF = function(event){
			if( event.keyCode == 9 ){
				if( $scope.user.cpf != undefined && $scope.user.cpf.length > 10 ){
					$scope.isloadingCPF = true;
					var $promise = $http.post('/controller/marketplace/checkcpfuser', {cpf: $scope.user.cpf});
					$promise.then(function(item){
						if( item.data.status == 'error' ){
							$scope.messagewarning = true;
							/*message feedback*/
							$scope.messagefeedbackps = item.data.message;
							/*set form invalid*/
							$scope.formProfile.$invalid = true;
							/*incremental invalid*/
							$scope.errorInvalid++;
						}else if( item.data.status == 'success' ){
							$scope.messagewarning = false;
							/*decremental invalid*/
							$scope.errorInvalid = 0;
						}
						$scope.isloadingCPF = false;
					});
				}
			}
		}

		$scope.checkPhone = function(event){
			if( event.keyCode == 9 ){
				if( $scope.user.phone != undefined && $scope.user.phone.length >= 10 ){
					$scope.isloadingPhone = true;
					var $promise = $http.post('/controller/marketplace/checkphoneuser', {phone: $scope.user.phone});
					$promise.then(function(item){
						if( item.data.status == 'error' ){
							$scope.messagewarning = true;
							/*message feedback*/
							$scope.messagefeedbackps = item.data.message;
							/*set form invalid*/
							$scope.formProfile.$invalid = true;
							/*incremental invalid*/
							$scope.errorInvalid++;
						}else if( item.data.status == 'success' ){
							$scope.messagewarning = false;
							/*decremental invalid*/
							$scope.errorInvalid = 0;
						}
						$scope.isloadingPhone = false;
					});
				}
			}
		}

		/*init function call*/
			/*get information user*/
			$scope.getInfoProfileUser();

	}]);