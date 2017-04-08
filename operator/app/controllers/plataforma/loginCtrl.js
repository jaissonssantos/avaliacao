'use strict';

app
	.controller('loginCtrl', ['$rootScope', '$scope', '$http', 'loginService', '$location', 'logadoServices', '$uibModal', 'lembreteService', function($rootScope, $scope, $http, loginService, $location, logadoServices, $uibModal, lembreteService){


		/*variable*/
		$scope.user 					= [{
			rememberme: false,
			message: false,
			feedback: undefined,
			isloading: false,
			submitting: false
		}];

		/* information user logged */
		$scope.nameuser				= undefined; /* name user is session logged */
		$scope.profileuser			= undefined; /* profile user logged */
		$scope.planuser				= undefined; /* profile user logged */
		$scope.usuario				= {
			isloading: false
		};
		$scope.alterou				= 0;


		/*function update user plataform logged*/
		$scope.$on('handleBroadcast', function(){
			$scope.nameuser = logadoServices.getnamelogged();
			$scope.profileuser = logadoServices.getprofilelogged();
			$scope.planuser = logadoServices.getplanlogged();
		});
		/*end function*/

		$scope.login = function( ){
			if( $scope.formLogin.$valid ){
				loginService.login( $scope.user, $scope );
			}
		}

		$scope.getloginremembered = function(){
			loginService.getrememberme( $scope );
		}

		$scope.logout = function(){
			loginService.logout();
		}

		$scope.getinfologged = function(){
			var $promise = loginService.islogged();
			$promise.then(function(item){
				if( item.data.status == 'success' ){
					logadoServices.setinfouserlogged(item.data.session.name, item.data.session.profile, item.data.session.plan);
				}else if( item.data.status == 'error' ){
					logadoServices.setinfouserlogged(undefined, undefined, undefined);	
				}
			});
		}

		//alteracao de senha
		$scope.alterarsenha = function(){
			$scope.alterou = 0;
			var modalInstance = $uibModal.open({
				templateUrl: '/plataforma/views/mypasswordchange.html',
				controller: function ($scope, $uibModalInstance, ps) {
					$scope.agendamento = ps;
					$scope.changePassword = function() { 
						var $promise = $http.post('/controller/plataforma/plataforma/changepass', {senha: $scope.usuario.senha});
						$promise.then(function(item){
							console.log("Retorno: "+item);
							if(item.data.msg == "success"){
								$scope.usuario.senha = "";
								$scope.usuario.confirmarsenha = "";
								$scope.alterou = 1;
							}else{
								$scope.alterou = 2;
							}
						});
					};
					$scope.cancel = function () {
						$uibModalInstance.dismiss('cancel');
					};
				},resolve: {
					ps: function () {
				        return null;
				    }
				}

			});
			modalInstance.result.then(function () {
			}, function () {
			});
		};

		//alteracao de senha
		$scope.meuperfil = function(){
			var $scopeuser = $scope.usuario;
			var modalInstance = $uibModal.open({
				templateUrl: '/plataforma/views/myprofile.html',
				controller: function ($scope, $uibModalInstance, ps) {

					$scope.usuario = ps;
					$scope.usuario.isloading = true;
					var $promise = $http.post('/controller/plataforma/plataforma/getmeuperfil');
					$promise.then(function(item){
						$scope.usuario = item.data[0];
						$scope.usuario.isloading = false;
					});

					$scope.changePassword = function() { 
						$uibModalInstance.close();
					};
					$scope.cancel = function () {
						$uibModalInstance.dismiss('cancel');
					};
				},resolve: {
					ps: function () {
				        return $scopeuser;
				    }
				}
			});
			modalInstance.result.then(function () {
			}, function () {
			});
		};

		//alteracao de senha
		$scope.anotacao = function(){
			var modalInstance = $uibModal.open({
				templateUrl: '/plataforma/views/annotation.html',
				controller: function ($rootScope, $scope, $uibModalInstance, lembreteService) {

					/*-- variables --*/
					$scope.lembrete = $scope.lembretes = {};
					$scope.results = {};

					$scope.$on("lembrete:loading", function(event, status){
						$scope.results.loading = status;
					});

					$scope.$on("lembrete", function(event, lembrete){
						$scope.lembrete = lembrete;
					});
					$rootScope.$on('lembrete:save', function(event, status) {
					    $scope.status = {
					      loading: (status == 'loading'),
					      success: (status == 'success'),
					      error: (status == 'error')
					    };
					});

					$rootScope.$on('lembretes:message:success', function(event, message){
						$rootScope.success = message;
					});

					$rootScope.$on('lembretes:message:error', function(event, message){
						$rootScope.error = message;
					});

					/*load lembrete*/
					$scope.load = function(){
						lembreteService.load();
					}

					$scope.update = function(){
						lembreteService.set($scope.lembrete);
						lembreteService.update();
					}

					$scope.cancel = function () {
						$uibModalInstance.dismiss('cancel');
					};

				}
			});
			modalInstance.result.then(function () {
			}, function () {
			});
		};

	}]);