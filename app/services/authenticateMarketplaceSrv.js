'use strict';

angular.module('app').factory('authenticateMarketplaceSrv', [ '$http', '$location', 'sessionService', '$timeout' , function( $http, $location, sessionService, $timeout ){

		return {

			login: function( user, scope ){
				/* disabled message authentication */
				scope.messageauthentication = false;
				/* enable loading and submitting before authentication */
				scope.isloading 	= true;
				scope.submitting	= true;
				/* send data to authentication login */
				$http.post('/controller/marketplace/authenticateuser', {email: user.email, password: user.password} )
				.success(function( item ){
					if( item.email ){
						if( user.rememberme ){
							sessionService.set('ang_markday_uid', item.id);
							sessionService.set('ang_markday_email', item.email);
							sessionService.set('ang_markday_cpf', item.cpf);
							sessionService.set('ang_markday_password', user.password);
						}else{
							/*remove session storage*/
							sessionService.destroy('ang_markday_uid');
							sessionService.destroy('ang_markday_email');
							sessionService.destroy('ang_markday_cpf');
							sessionService.destroy('ang_markday_password');
						}
						if(scope.redirect!=undefined){
							$location.url(scope.redirect);
						}else{
							$location.path('cliente/agendamentos');
						}
					}else if( item.credentials ){
						scope.msgcredentials = 'Favor verifique os dados, credenciais informada está incorreta.';
						/* clear model user */
						scope.user.email 	= undefined;
						scope.user.password = undefined;
						/* enable message error authentication */
						scope.messageauthentication = true;
						/* hidden message in 3 seconds */
						$timeout(function(){
							scope.messageauthentication = false;
						}, 3000);
					}
					/* disabled loading and submitting after authentication */
					scope.isloading 	= false;
					scope.submitting 	= false;
				})
				.error(function( error ){
					console.log( error );
				});
			},

			getrememberme: function( scope ){
				/*get session storage, case remember iguals true*/
				if( sessionService.get('ang_markday_email') != undefined && sessionService.get('ang_markday_password') != undefined ){
					scope.user.email = sessionService.get('ang_markday_email');
					scope.user.password = sessionService.get('ang_markday_password');
					scope.user.rememberme = true;
				}
			},

			signup: function( signuser, scope ){
				/* enable loading and submitting before authentication */
				scope.isloading 	= true;
				scope.submitting	= true;
				/* send data to authentication login */
				var $promise = $http.post('/controller/marketplace/signupuser', {name: signuser.name, phone: signuser.phone, email: signuser.email, password: signuser.password});
				$promise.then(function(item){
					if( item.data.status == 'success' ){
						$location.path('/cliente');
					}else if( item.data.status == 'error' ){
						/*show message and feedback error*/
						scope.ismessagesignup = true;
						scope.messagefeedbacksignup = item.data.message;
						/* hidden message in 5 seconds */
						$timeout(function(){
							scope.ismessagesignup = false;
						}, 5000);
					}
				});
			},

			passwordreset: function( user, scope ){
				/* disabled message password reset */
				scope.messagewarning 			= false;
				scope.messagesuccess 			= false;
				/* enable loading and submitting before authentication */
				scope.isloading 	= true;
				scope.submitting	= true;
				/* send data to password reset */
				var $promise = $http.post('/controller/marketplace/passwordresetuser', {email: user.email});
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
				sessionService.destroy('ang_markday_uid');
				sessionService.destroy('ang_markday_email');
				sessionService.destroy('ang_markday_password');
				sessionService.destroy('ang_markday_cpf');
				//http to destroy session
				var $promise = $http.post('/controller/marketplace/logout');
				$promise.then(function(item){
					if( item.data.status == 'success' ){
						$location.path('/entrar');
					}else if( item.data.status == 'error' ){
						alert(item.data.message);
					}
				});
			},

			isLogged: function(){
 				var $promise = $http.post('/controller/marketplace/checkauthenticateuser');
 				return $promise;
			},

			tokenValidated: function( token, scope ){
				var $promise = $http.post('/controller/marketplace/checktokenuser', {token: token});
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
				var $promise = $http.post('/controller/marketplace/passwordchangeuser', {password: user.password, token: token });
				$promise.then(function(item){
					if( item.data.status == 'success' ){
						scope.messagesuccess = true;
					}else if( item.data.status == 'error' ){
						scope.messagewarning = true;
					}
					scope.messagefeedbackps = item.data.message;
					scope.tokenvalidated = false;
				});
			}
			
		}

	}]);