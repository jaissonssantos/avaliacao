'use strict';

app
	
	.factory('logadoServices', function($rootScope){

		/*private variable*/
		var logged = [];
			logged.nameuser 	= undefined;
			logged.profileuser 	= undefined;
			logged.planuser 	= undefined;

		/*public service*/
		logged.getnamelogged = function(){
			return this.nameuser;
		}

		logged.getprofilelogged = function(){
			  return this.profileuser;
		}

		logged.getplanlogged = function(){
			  return this.planuser;
		}

		logged.setinfouserlogged = function(nameuser, profileuser, planuser){
			this.nameuser = nameuser;
			this.profileuser = profileuser;
			this.planuser = planuser;
			this.broadcastLogged();
		}

		logged.broadcastLogged = function(){
			$rootScope.$broadcast('handleBroadcast');
		}

		return logged;

	})

	.factory('loginService', [ '$http', '$location', 'sessionSrv', '$timeout' , function( $http, $location, sessionSrv, $timeout ){

		return {

			login: function( user, scope ){
				/* disabled message authentication */
				scope.user.message = false;
				/* enable loading and submitting before authentication */
				scope.user.isloading = true;
				scope.user.submitting	= true;
				/* send data to authentication login */
				var $promise = $http.post('/controller/plataforma/login', {email: user.email, password: user.password});
				$promise.then(function(item){
					if( item.data.email ){
						if( user.rememberme ){
							sessionSrv.set('ang_plataforma_uid', item.data.id);
							sessionSrv.set('ang_plataforma_nome', item.data.nome);
							sessionSrv.set('ang_plataforma_email', item.data.email);
							sessionSrv.set('ang_plaforma_estabelecimento', item.data.idestabelecimento);
							sessionSrv.set('ang_plataforma_perfil', item.data.perfil);
							sessionSrv.set('ang_plataforma_password', user.password);
						}else{
							/*remove session storage*/
							sessionSrv.destroy('ang_plataforma_uid');
							sessionSrv.destroy('ang_plataforma_nome');
							sessionSrv.destroy('ang_plataforma_email');
							sessionSrv.destroy('ang_plaforma_estabelecimento');
							sessionSrv.destroy('ang_plataforma_perfil');
							sessionSrv.destroy('ang_plataforma_password');
						}
						$location.path('/agenda');
					}else if( item.data.status ){
						/* enable message error authentication */
						scope.user.message = true;
						scope.user.feedback = item.data.message;
						/* clear model user */
						scope.user.email 	= undefined;
						scope.user.password = undefined;
						/* hidden message in 5 seconds */
						$timeout(function(){
							scope.message = false;
						}, 5000);
					}
					/* disabled loading and submitting after authentication */
					scope.user.isloading 	= false;
					scope.user.submitting 	= false;
				});
			},

			getrememberme: function( scope ){
				/*get session storage, case remember iguals true*/
				if( sessionSrv.get('ang_plataforma_email') != undefined && sessionSrv.get('ang_plataforma_password') != undefined ){
					scope.user.email = sessionSrv.get('ang_plataforma_email');
					scope.user.password = sessionSrv.get('ang_plataforma_password');
					scope.user.rememberme = true;
				}
			},

			passwordreset: function( user, scope ){
				/* disabled message password reset */
				scope.messagewarning 			= false;
				scope.messagesuccess 			= false;
				/* enable loading and submitting before authentication */
				scope.isloading 	= true;
				scope.submitting	= true;
				/* send data to password reset */
				var $promise = $http.post('/controller/aplicacao/passwordresetuser', {email: user.email});
				$promise.then(function(item){
					if( item.data.status == 'success' ){
						/*show message and feedback success*/
						scope.messagesuccess = true;
					}else if( item.data.status == 'error' ){
						/*show message and feedback error*/
						scope.messagewarning = true;
						/* hidden message in 5 seconds */
						$timeout(function(){
							scope.messagewarning = false;
						}, 5000);
					}
					/*feed back*/
					scope.messagefeedbackps = item.data.message;
					/* clear model user email */
					scope.user.email = undefined;
					/* disabled loading and submitting after authentication */
					scope.isloading 	= false;
					scope.submitting 	= false;
				});
			},

			logout: function(){
				sessionSrv.destroy('ang_plataforma_uid');
				sessionSrv.destroy('ang_plataforma_nome');
				sessionSrv.destroy('ang_plataforma_email');
				sessionSrv.destroy('ang_plaforma_estabelecimento');
				sessionSrv.destroy('ang_plataforma_perfil');
				sessionSrv.destroy('ang_plataforma_password');
				//http to destroy session
				var $promise = $http.post('/controller/plataforma/logout');
				$promise.then(function(item){
					if( item.data.status == 'success' ){
						$location.path('/login');
					}else if( item.data.status == 'error' ){
						alert(item.data.message);
					}
				});
			},

			islogged: function(){
 				var $promise = $http.post('/controller/plataforma/checksessionlogin');
 				return $promise;
			},

			tokenValidated: function( token, scope ){
				var $promise = $http.post('/controller/aplicacao/checktokenuser', {token: token});
				$promise.then(function(item){
					if( item.data.status == 'success' && item.data.token == 'valid' ){
						scope.tokenvalidated = true;
					}else if( item.data.status == 'error' && item.data.token == 'invalid' ){
						scope.messagewarning = true;
						scope.messagefeedbackps = item.data.message;
					}
				});
			},

			passwordChange: function( user, token, scope ){
				/* disabled message password change */
				scope.messagewarning = false;
				scope.messagesuccess = false;
				/* enable loading and submitting before password change */
				scope.isloading 	= true;
				scope.submitting	= true;
				/* send data to password change */
				var $promise = $http.post('controller/aplicacao/passwordchangeuser', {password: user.password, token: token });
				$promise.then(function(item){
					if( item.data.status == 'success' ){
						scope.messagesuccess = true;
					}else if( item.data.status == 'error' ){
						scope.messagewarning = true;
					}
					scope.messagefeedbackps = item.data.message;
					scope.tokenvalidated = false;
				});
			},

			roles: function(){
				var $promise = $http.post('/controller/plataforma/rolespermission');
				return $promise;
			}
			
		}

	}]);