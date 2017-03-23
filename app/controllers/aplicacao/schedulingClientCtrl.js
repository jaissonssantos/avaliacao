'use strict'; 

angular.module('app').controller('schedulingClientCtrl', ['$scope', 'agendaSrv', '$locale', '$http', '$routeParams', '$location', 'ngProgressFactory', 'authenticateMarketplaceSrv', '$timeout', function($scope, agendaSrv, $locale, $http, $routeParams, $location, ngProgressFactory, authenticateMarketplaceSrv, $timeout){
		
		/*loading page start*/
		$scope.ngProgressApp = ngProgressFactory.createInstance();
		$scope.ngProgressApp.start();

		/*variables*/
		$scope.countWeeknext = 0; //default variable week next navegation
		$scope.positioncurrent = 0; //default variable count navegation 
		$scope.positionprev = 0; //default variable week previons navegation 

		$scope.notscheduling = false;
		$scope.schedulingcompletedata = [];
		$scope.schedulingcomplete = {
			status: 'error', //error or success(default: error)
			show: false
		}
		$scope.serverDate = undefined;
		$scope.week = [];
		$scope.daySun = 0;
		$scope.descMonth = '';
		$scope.descYear = '';
		$scope.descWeek = 'esta semana'; /* default: 'esta semana', 'semana que vem', 'em 2 semanas', 'em 3 semanas', 'em 4 semanas' */
		$scope.isloading, $scope.isagenda = false;
		$scope.scheduling = [];
		$scope.services = [];
		$scope.professional = [];
		$scope.daywork = [];
		$scope.commitmentmarked = [];
		$scope.timePicker = [];
		$scope.timePickerAvailable = false;
		$scope.scheduledtime = []; /*dados edição do horário agendado*/
		$scope.activeweekmanual = undefined;
		$scope.activeweeknotdaymanual = undefined;
		$scope.btcontinue = false;
		$scope.autentication = false;
		$scope.payscheduling = false;
		$scope.step = 0; /*first step form*/
		$scope.signupuser = [];
		$scope.signupsubmitting = false;
		$scope.signupisloading = false;
		$scope.signupismessage = false;
		$scope.signupmessagefeedback = undefined;
		$scope.isformsignup = true;
		$scope.isformlogin = false;
		$scope.isformpasswordchange = false;
		$scope.loginuser = [];
		$scope.loginsubmitting = false;
		$scope.loginisloading = false;
		$scope.loginismessage = false;
		$scope.loginmessagefeedback = undefined;
		$scope.passw = [];
		$scope.passwsubmitting = false;
		$scope.passwisloading = false;
		$scope.passwismessagesuccess = false;
		$scope.passwismessagewarning = false;
		$scope.passwmessagefeedback = undefined;
		$scope.checkautenticateuser = false;
		$scope.pay = [];
		$scope.pay = {
			method: 2 //default method payment(pagar no estabelecimento)
		}
		$scope.paysubmitting = false;
		$scope.card = [];
		$scope.schedulingsubmitting = false;
		var hashscheduled = $routeParams.hashscheduled || undefined;
		var hashservice = $routeParams.hashservice || undefined;
		var hashcompany =  $scope.hashcompanyname = $routeParams.companyname || undefined;
		if( hashservice == undefined && hashcompany == undefined ){
			$location.path('/404');
		}

		/*get day in month calendar*/
		$scope.calendar = function(){
			var $promise = $http.post('/controller/marketplace/getdate');
			$promise.then(function(item){
				/*set date server*/
				$scope.serverDate = new Date(item.data.year, (item.data.month-1), item.data.day);
				var timeoff = new Date(item.data.year, (item.data.month-1), item.data.day, item.data.hour, item.data.minute); /*format "mm/dd/yyyy hh:mm:ss"*/

				$scope.week = agendaSrv.getDaysInMonth(item.data.year, item.data.month, item.data.day);
				$scope.descMonth = agendaSrv.getMonthDescription(item.data.month);
				$scope.descYear = item.data.year;

				var $dateCurrent = new Date(item.data.year, item.data.month-1, item.data.day);
				var $dateCurrentDay = $dateCurrent.getDay();
				var $timeCurrentStart = undefined;
				var $timeCurrentFinal = undefined;
				var $dateCurrentOk = 0;
				var $dateCurrentHoliday = 0;

				for(var $i = 0; $i < $scope.daywork.length; $i++){
					if( parseInt($dateCurrentDay) == parseInt($scope.daywork[$i].dia) ){
						$dateCurrentOk++;
						$timeCurrentStart = $scope.daywork[$i].horainicial;
						$timeCurrentFinal = $scope.daywork[$i].horafinal;
					}
				}

				/*Check if the current day is Sunday and it is a day of work, if not ... sets the current day for the next day*/
				if( parseInt($dateCurrentDay) == 0 && $dateCurrentOk == 0){
					$dateCurrentDay = parseInt($dateCurrentDay)+1;
					for(var $i = 0; $i < $scope.daywork.length; $i++){
						if( parseInt($dateCurrentDay) == parseInt($scope.daywork[$i].dia) ){
							$dateCurrentOk++;
							$timeCurrentStart = $scope.daywork[$i].horainicial;
							$timeCurrentFinal = $scope.daywork[$i].horafinal;
							/*set day*/
							item.data.day = parseInt(item.data.day)+1;
							$scope.serverDate = new Date((item.data.month) +'/'+ item.data.day + '/'+ item.data.year); /*format "mm/dd/yyyy hh:mm:ss";*/
						}
					}
				}
				/*Check if the current day is not day of work, if not ... sets the current day for the next day*/
				if( $dateCurrentOk == 0){
					/*verificated day current is day work*/
					var $firstdaywork = [];
					for( var i = 0; i < $scope.week.length; i++ ){
						if( $scope.daywork[0].dia == $scope.week[i].getDay() ){
							$firstdaywork.push({'dia': $scope.week[i]});
						}
					}

					$dateCurrentOk++;
					$timeCurrentStart = $scope.daywork[0].horainicial;
					$timeCurrentFinal = $scope.daywork[0].horafinal;
					$scope.activeweeknotdaymanual = $firstdaywork[0].dia;
					item.data.day = $scope.activeweeknotdaymanual.getDate();
				}

				if( $dateCurrentOk ){
					var d_start = new Date((item.data.month) +'/'+ item.data.day + '/'+ item.data.year + ' ' + $timeCurrentStart); /*format "mm/dd/yyyy hh:mm:ss"*/
					var d_end = new Date((item.data.month) +'/'+ item.data.day + '/'+ item.data.year + ' ' + $timeCurrentFinal);   /*format "mm/dd/yyyy hh:mm:ss"*/
					
					/*create and clear timePicker*/
					var $timePicker = [];

				    while( d_start <= d_end ){
				        var m = (((d_start.getMinutes() + 7.5)/15 | 0) * 15) % 60;
				        var h = ((((d_start.getMinutes()/105) + .5) | 0) + d_start.getHours()) % 24;
				        var schedule = new Date(d_start);
				        schedule.setHours(h);
				        schedule.setMinutes(m);
				        /*verification time is smaller datetime the calendar*/
				        if( timeoff > schedule ){
				        	$timePicker.push({ 'time' : schedule, 'timeoff': true });
				        }else{
				        	$timePicker.push({ 'time' : schedule, 'timeoff': false });
				        }
				        /*add 15 minutes in date current selected*/
				        d_start.setMinutes(d_start.getMinutes() + parseInt($scope.scheduling.professional.tempoconsulta) );
				    }

				    $scope.timePicker = $timePicker;

				    /*set interval*/
					var $intervalBegin = new Date((item.data.month) +'/'+ item.data.day + '/'+ item.data.year + ' ' + $timeCurrentStart); /*format "mm/dd/yyyy hh:mm:ss"*/
					$intervalBegin.setHours(11,59,0); /* hours 11:59:00*/
					var $intervalEnd = new Date((item.data.month) +'/'+ item.data.day + '/'+ item.data.year + ' ' + $timeCurrentStart); /*format "mm/dd/yyyy hh:mm:ss"*/
					$intervalEnd.setHours(13,59,0); /* hours 13:59:00*/

				    /*remove itens time in time picker*/
				    /*verificated exists commitment marked schedule in professional*/
				    /*function loader commitment marked in schedule professional*/
				    var $promise = $http.post('/controller/marketplace/getcommitmentmarked', {date: $scope.serverDate, hash: hashcompany, professional: $scope.scheduling.professional.id });
				    /* clear array commitment marked */
				    $scope.commitmentmarked = [];
					$promise.then(function(item){
						$scope.commitmentmarked = item.data;
						/*remove itens time in time picker*/
					    /*verificated exists commitment marked schedule in professional*/
					    if( $scope.commitmentmarked.length >= 1 ){
			        		for( var $i = 0; $i < $scope.commitmentmarked.length; $i++ ){

			        			var marked_start = new Date($scope.commitmentmarked[$i].horainicial);
						    	var marked_end = new Date($scope.commitmentmarked[$i].horafinal);

						    	for( var $key = 0; $key < $scope.timePicker.length; $key++ ){

									var $tpt = new Date( $scope.timePicker[$key].time );
									$tpt.setMinutes( $tpt.getMinutes() + 1 );

						    		if( $tpt >= marked_start && $tpt <= marked_end ){
						    			if( hashscheduled == undefined && $scope.scheduledtime.length == 0 ){
						    				$scope.timePicker[$key].time = undefined;
						    			}
						    		}

						    		/*clear time in interval*/
						    		if( $scope.timePicker[$key].time >= $intervalBegin && $scope.timePicker[$key].time <= $intervalEnd ){
					    				$scope.timePicker[$key].time = undefined;
					    			}

						    		if( hashscheduled != undefined /*&& $scope.scheduledtime.length != undefined*/ ){
						    			var scheduled_start = new Date($scope.scheduledtime.horainicial);
						    			var scheduled_end = new Date($scope.scheduledtime.horafinal);

						    			if( $scope.timePicker[$key].time >= scheduled_start && $scope.timePicker[$key].time <= scheduled_end ){
						    				$scope.timePicker[$key].scheduledtime = true;
						    			}else{
						    				$scope.timePicker[$key].scheduledtime = false;
						    			}
						    		}
						    	}

			        		}

				        }

				        /*verification time available*/
				        var $totalTimePicker = 0;
				        for(var $i = 0; $i < $scope.timePicker.length; $i++){
				        	if( $scope.timePicker[$i].time != undefined ){
				        		$totalTimePicker++;
				        	}
				        }
				        if( $totalTimePicker == 0 ){
				        	$scope.timePickerAvailable = true;
				        }else{
				        	$scope.timePickerAvailable = false;
				        }
				        $scope.isloading = false;
						$scope.isagenda = true;

						/*loading page complete*/
						$scope.ngProgressApp.complete();
						
					});
				}
			});
		};/*end function*/
		
		/*get day in week selected on calendar */
		$scope.calendarDateWeekSelected = function(date){
			$scope.isloading = true;
			$scope.isagenda = false;
			var $dateCurrent = new Date(date);
			var $dateCurrentDay = $dateCurrent.getDay();
			var $timeCurrentStart = undefined;
			var $timeCurrentFinal = undefined;
			var $dateCurrentOk = 0;
			var $timePicker = [];

			for(var $i = 0; $i < $scope.daywork.length; $i++){
				if( parseInt($dateCurrentDay) == parseInt($scope.daywork[$i].dia) ){
					$dateCurrentOk++;
					$timeCurrentStart = $scope.daywork[$i].horainicial;
					$timeCurrentFinal = $scope.daywork[$i].horafinal;
				}
			}
			if( $dateCurrentOk ){
				$timeCurrentStart = $timeCurrentStart.split(':');
				var d_start = new Date(date);
					d_start.setHours($timeCurrentStart[0]);
					d_start.setMinutes($timeCurrentStart[1]);
				$timeCurrentFinal = $timeCurrentFinal.split(':');
				var d_end = new Date(date);
					d_end.setHours($timeCurrentFinal[0]);
					d_end.setMinutes($timeCurrentFinal[1]);	
				/*clear array timePicker and set news values */
				$scope.timePicker = [];
				$scope.timePicker.length = 0;
				for( var $tp = 0; $tp < $scope.timePicker.length; $tp ){
					$scope.timePicker.pop();
				}
				while( d_start <= d_end ){
			        var m = (((d_start.getMinutes() + 7.5)/15 | 0) * 15) % 60;
			        var h = ((((d_start.getMinutes()/105) + .5) | 0) + d_start.getHours()) % 24;
			        var schedule = new Date(d_start);
			        schedule.setHours(h);
			        schedule.setMinutes(m);
			        var timeoff = new Date(); /*format "mm/dd/yyyy hh:mm:ss"*/
			        /*verification time is smaller datetime the calendar*/
			        if( timeoff > schedule ){
			        	$timePicker.push({ 'time' : schedule, 'timeoff': true });
			        }else{
			        	$timePicker.push({ 'time' : schedule, 'timeoff': false });
			        }
			        /*add 15 minutes in date current selected*/
			        d_start.setMinutes(d_start.getMinutes() + parseInt( $scope.scheduling.professional.tempoconsulta ) );
			    }
			    
			    $scope.timePicker = $timePicker;

			    /*set interval*/
				var $intervalBegin = new Date(date); /*format "mm/dd/yyyy hh:mm:ss"*/
				$intervalBegin.setHours(11,59,0); /* hours 11:59:00*/
				var $intervalEnd = new Date(date); /*format "mm/dd/yyyy hh:mm:ss"*/
				$intervalEnd.setHours(13,59,0); /* hours 13:59:00*/

			    /*function loader commitment marked in schedule professional*/
			    var $promise = $http.post('/controller/marketplace/getcommitmentmarked', {date: date, hash: hashcompany, professional: $scope.scheduling.professional.id});
			    /* clear array commitment marked */
			    $scope.commitmentmarked = [];
				$promise.then(function(item){
					$scope.commitmentmarked = item.data;
					/*remove itens time in time picker*/
				    /*verificated exists commitment marked schedule in professional*/
				    if( $scope.commitmentmarked.length >= 1 ){
		        		for( var $i = 0; $i < $scope.commitmentmarked.length; $i++ ){

		        			var marked_start = new Date($scope.commitmentmarked[$i].horainicial);
					    	var marked_end = new Date($scope.commitmentmarked[$i].horafinal);

					    	for( var $key = 0; $key < $scope.timePicker.length; $key++ ){
					    		var $tpt = new Date( $scope.timePicker[$key].time );
									$tpt.setMinutes( $tpt.getMinutes() + 1 );

					    		if( $tpt >= marked_start && $tpt <= marked_end ){
					    			if( hashscheduled == undefined && $scope.scheduledtime.length == 0 ){
					    				$scope.timePicker[$key].time = undefined;
					    			}
					    		}

					    		/*clear time in interval*/
					    		if( $scope.timePicker[$key].time >= $intervalBegin && $scope.timePicker[$key].time <= $intervalEnd ){
				    				$scope.timePicker[$key].time = undefined;
				    			}

					    		if( hashscheduled != undefined /*&& $scope.scheduledtime.length != undefined*/ ){
					    			var scheduled_start = new Date($scope.scheduledtime.horainicial);
					    			var scheduled_end = new Date($scope.scheduledtime.horafinal);
					    			if( $scope.timePicker[$key].time >= scheduled_start && $scope.timePicker[$key].time <= scheduled_end ){
					    				$scope.timePicker[$key].scheduledtime = true;
					    			}else{
					    				$scope.timePicker[$key].scheduledtime = false;
					    			}
					    		}
					    	}

		        		}

			        }else{
			        	/*clear time in interval*/
			        	for( var $key = 0; $key < $scope.timePicker.length; $key++ ){
			        		if( $scope.timePicker[$key].time >= $intervalBegin && $scope.timePicker[$key].time <= $intervalEnd ){
			    				$scope.timePicker[$key].time = undefined;
			    			}
			        	}
			        }
			        /*verification time available*/
			        var $totalTimePicker = 0;
			        for(var $i = 0; $i < $scope.timePicker.length; $i++){
			        	if( $scope.timePicker[$i].time != undefined ){
			        		$totalTimePicker++;
			        	}
			        }
			        if( $totalTimePicker == 0 ){
			        	$scope.timePickerAvailable = true;
			        }else{
			        	$scope.timePickerAvailable = false;
			        }
			        $scope.isloading = false;
					$scope.isagenda = true;
				});
			}
		};/*end function*/

		/*function disabled day equal sunday = 0 in array days*/
		$scope.disabledSun = function(day, daywork){
			var $result = agendaSrv.getDisabledDaySun(day, daywork);
			return $result;
		};/*end function*/

		/*funciton compared date to current date in active today*/
		$scope.comparedCurrentDate = function(dateweek, dateserver){
			var $result = agendaSrv.getComparedDateCurrentDate(dateweek, dateserver);
			return $result;
		};/*end function*/

		/*function change description week*/
		$scope.changeDescWeek = function(description){
			$scope.$apply(function(){
				$scope.descWeek = description;
			});
		};/*end function*/

		/*function loader professional*/
		$scope.getprofessional = function(){
			$scope.isloading = true;
			$scope.isagenda = false;
			var $promise = $http.post('/controller/marketplace/getprofessional', {establishment: hashcompany, service: hashservice});
			$promise.then(function(item){
				if(item.data != null){
					$scope.professional =  item.data;
					$scope.scheduling.professional = item.data[0];
					$scope.getprofessionaldaywork(item.data[0]);
				}else{
					$scope.notscheduling = true;
					/*loading page complete*/
					$scope.ngProgressApp.complete();
				}
			});
		};/*end function*/

		/*function loader professional day work */
		$scope.getprofessionaldaywork = function(professional){
			var $promise = $http.post('/controller/marketplace/getprofessionaldaywork', {professional: professional});
			$promise.then(function(item){
				if(item.data != null){
					$scope.daywork = item.data;
					$scope.getscheduledtime();
				}else{
					$scope.notscheduling = true;
					/*loading page complete*/
					$scope.ngProgressApp.complete();
				}
			});
		};/*end function*/

		/*function loader scheduled edit*/
		$scope.getscheduledtime = function(){

			//disabled previons week navegation
			var $aPrevWeek =  angular.element( $jq( '.calendario .faixa-dias .seta-esq' ) );
			if( $scope.positioncurrent <= 6 )
				$aPrevWeek.addClass('desabilitado');

			if( hashscheduled != undefined ){
				var $promise = $http.post('/controller/marketplace/getscheduledtime', {hashscheduled: hashscheduled});
				$promise.then(function(item){
					$scope.scheduledtime = item.data[0];
					$scope.getservices();
				});
			}else{
				$scope.getservices();
			}
		}

		/*function loader services execute*/
		$scope.getservices = function(){
			var $promise = $http.post('/controller/marketplace/getservice', {establishment: hashcompany, service: hashservice});
			$promise.then(function(item){
				if(item.data != null){
					$scope.services = item.data[0];
					/* loader calendar */
					$scope.calendar();
				}else{
					$scope.notscheduling = true;
					/*loading page complete*/
					$scope.ngProgressApp.complete();
				}
		    });
		};/*end function*/

		/*function change professional*/
		$scope.changeProfessional = function(){
			/*enable loading calendar*/
			$scope.isloading = true;
			$scope.isagenda = false;
			/*hidden btn continue*/
			$scope.btcontinue = false;
			/*hidden login or signup*/
			$scope.autentication = false;
			/*enable first step*/
			$scope.step = 1;
			/*clear not day work and sunday*/
			$scope.activeweekmanual = undefined;
			$scope.activeweeknotdaymanual = undefined;

			var $promise = $http.post('/controller/marketplace/getprofessionaldaywork', {professional: $scope.scheduling.professional});
			$promise.then(function(item){
				if(item.data != null){
					$scope.daywork = item.data;
					
					/*get next useful day work in week*/
					var $dateuseful = undefined;
					var $dw = item.data;
					var $datesmaller = [];

					for( var $i = 0; $i <= 6; $i++ ){
						for( var $x = 0; $x < $dw.length; $x++ ){
							if( $dw[$x].dia == $scope.week[$i].getDay()  ){
								$datesmaller.push({ 'position' : $dw[$x].dia });
							}
						}
					}
					
					for( var $i = 0; $i <= 6; $i++ ){
						if( $datesmaller[0].position == $scope.week[$i].getDay() ){
							$dateuseful = $scope.week[$i];
						}
					}

					/*remove all class in week*/
					var $liCalendar =  angular.element( $jq( '.calendario .faixa-dias .dias li' ) );
					$liCalendar.removeClass('selecionado');
					$liCalendar.removeClass('selecionadosunday');
					$liCalendar.removeClass('selecionadonotday');
					/* set day work manual */
					$scope.activeweekmanual = $dateuseful;
					$scope.calendarDateWeekSelected($dateuseful);
				}else{
					$scope.notscheduling = true;
					/*loading page complete*/
					$scope.ngProgressApp.complete();
				}
			});
		}

		/*function click date week day*/
		$scope.timePickerSelectDate = function(date){
			var $okfunction = false; /* result: true = day disabled or false =  day ok(show) in week */
			if( $scope.disabledSun(date.getDay(), $scope.daywork ) )
				$okfunction = true
			else
				$okfunction = false;

			if( !$okfunction ){
				/* function calendar date day week and list times attendance professional */
				$scope.calendarDateWeekSelected(date);
				/*hidden btn continue*/
				$scope.btcontinue = false;
			}
		};/*end function*/

		/*function click time date week*/
		$scope.timeSelectDate = function(date){
			$scope.scheduling.timeweek = date;
			/*show btn continue*/
			$scope.btcontinue = true;
		};/*end function*/

		/*function action continue enable payment*/
		$scope.actionContinue = function(){
			if( $scope.checkautenticateuser ){
				/*enable next step*/
				$scope.step = 2;
				/*disable authenticate form*/
				$scope.autentication = false;
				/*enable payment scheduling*/
				$scope.payscheduling = true;
			}else{
				/*enable next step*/
				$scope.step = 1;
				/*enable authenticate form*/
				$scope.autentication = true;
			}
		}
		/*end function*/

		/*verification user logged*/
		$scope.islogged = function(){
			var $promise = authenticateMarketplaceSrv.isLogged();
			$promise.then(function(item){
				if( item.data.status == 'success' ){
					$scope.checkautenticateuser = true;
				}else if( item.data.status == 'error' ){
					$scope.checkautenticateuser = false;
				}
			});
		}
		/*end function*/

		/*function action signup*/
		$scope.signup = function(signupuser, formSignup){
			if( formSignup.$valid ){
				/* enable loading and submitting before authentication */
				$scope.signupisloading 	= true;
				$scope.signupsubmitting	= true;
				/* send data to authentication login */
				var $promise = $http.post('/controller/marketplace/signupuser', {name: signupuser.name, phone: signupuser.phone, email: signupuser.email, password: signupuser.password});
				$promise.then(function(item){
					if( item.data.status == 'success' ){
						var $listener = authenticateMarketplaceSrv.isLogged();
						$listener.then(function(item){
							if( item.data.status == 'success' ){
								$scope.checkautenticateuser = true;
								//loggedMarketplaceSrv.setUserLogged(true, item.data.ang_session_name);
								/*enable next step*/
								$scope.step = 2;
								/*disable autentication*/
								$scope.autentication = false;
								/*enable pay*/
								$scope.payscheduling = true;
							}else if( item.data.status == 'error' ){
								$scope.checkautenticateuser = false;
								//loggedMarketplaceSrv.setUserLogged(false, undefined);	
							}
						});
					}else if( item.data.status == 'error' ){
						/*show message and feedback error*/
						$scope.signupismessage = true;
						$scope.signupmessagefeedback = item.data.message;
						/* hidden message in 5 seconds */
						$timeout(function(){
							$scope.signupismessage = false;
						}, 5000);
					}
					$scope.signupisloading = false;
					$scope.signupsubmitting = false;
				});
			}
		}
		/*end function*/

		/*function action login*/
		$scope.login = function(user, formLogin){
			if( formLogin.$valid ){
				/* enable loading and submitting before authentication */
				$scope.loginsubmitting = true;
				$scope.loginisloading = true;
				/* send data to authentication login */
				var $promise = $http.post('/controller/marketplace/authenticateuser', {email: user.email, password: user.password} );
				$promise.then(function( item ){
					if( item.data.email ){
						var $listener = authenticateMarketplaceSrv.isLogged();
						$listener.then(function(item){
							if( item.data.status == 'success' ){
								$scope.checkautenticateuser = true;
								//loggedMarketplaceSrv.setUserLogged(true, item.data.ang_session_name);
								/*enable next step*/
								$scope.step = 2;
								/*disable autentication*/
								$scope.autentication = false;
								/*enable pay*/
								$scope.payscheduling = true;
							}else if( item.data.status == 'error' ){
								$scope.checkautenticateuser = false;
								//loggedMarketplaceSrv.setUserLogged(false, undefined);	
							}
						});
					}else if(item.data.credentials){
						$scope.loginismessage = true;
						$scope.loginmessagefeedback = item.data.message;
						/* clear model user */
						$scope.loginuser.email 	= undefined;
						$scope.loginuser.password = undefined;
						/* hidden message in 3 seconds */
						$timeout(function(){
							$scope.loginismessage = false;
						}, 3000);
					}
					/* disabled loading and submitting after authentication */
					$scope.loginsubmitting = false;
					$scope.loginisloading = false;
				});
			}
		}
		/*end function*/

		/*function action password reset*/
		$scope.passwreset = function(passw, formPassword){
			if( formPassword.$valid ){
				/* enable loading and submitting before password reset */
				$scope.passwsubmitting = true;
				$scope.passwisloading = true;
				/* send data to password reset */
				var $promise = $http.post('/controller/marketplace/passwordresetuser', {email: passw.email});
				$promise.then(function(item){
					if( item.data.status == 'success' ){
						/*show message and feedback success*/
						$scope.passwismessagesuccess = true;
					}else if( item.data.status == 'error' ){
						/*show message and feedback error*/
						$scope.passwismessagewarning = true;
						/* hidden message in 5 seconds */
						$timeout(function(){
							$scope.passwismessagewarning = false;
						}, 5000);
					}
					/*feed back*/
					$scope.passwmessagefeedback = item.data.message;
					/* clear model user email */
					$scope.passw.email = undefined;
					/* disabled loading and submitting after password reset */
					$scope.passwsubmitting 	= false;
					$scope.passwisloading 	= false;
				});
			}
		}

		/*action for view form step 2*/
		$scope.actionviewformsteptwo = function(option){
			/*hidden all form*/
			$scope.isformsignup = false;
			$scope.isformlogin = false;
			$scope.isformpasswordchange = false;
			switch(option){
				case 'signup':
					$scope.isformsignup = true;
				break;
				case 'login':
					$scope.isformlogin = true;
				break;
				case 'passwordchange':
					$scope.isformpasswordchange = true;
				break;
			}
		}
		/*end function*/

		/*action save sheduling*/
		$scope.actionschedulingnotpay = function(){
			/*enable loading and submitting before scheduling*/
			$scope.schedulingsubmitting = true;
			/* send data to sheduling data */
			var $promise = $http.post('/controller/marketplace/saveschedulingnotpay', 
								{	professional: $scope.scheduling.professional.id, 
									intervaltime: $scope.scheduling.professional.tempoconsulta,
									timebegin: ""+$scope.scheduling.timeweek, /*jogadinha para passar o horário correto*/
									establishment: hashcompany,
									services: hashservice,
									methodpay: $scope.pay.method,
									notice: $scope.scheduling.message,
									hashscheduled: hashscheduled
								});
			$promise.then(function(item){
				if( item.data.status == 'success' ){
					$scope.schedulingcomplete.status = 	item.data.status;
					$scope.schedulingcomplete.show = true;
				}else if( item.data.status == 'error' ){
					$scope.schedulingcomplete.status = 	item.data.status;
					$scope.schedulingcomplete.show = true;
				}
				$scope.schedulingcompletedata = item.data;
				/* disabled loading and submitting after scheduling */
				$scope.schedulingsubmitting = false;
			});
		}
		/*end function*/

	}])