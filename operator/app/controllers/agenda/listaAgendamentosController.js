app
	.controller('listaAgendamentosController', ['$rootScope','$scope','$locale','$http','$routeParams','$location','$uibModal','profissionalService','agendaService','aplicacaoService', 
		function($rootScope,$scope,$locale,$http,$routeParams,$location,$uibModal,profissionalService,agendaService,aplicacaoService){

		$scope.aplicacao = {};
		$scope.agendamento = {};
		$scope.profissionais = [];
		$scope.agendas = [];
		$scope.results = {
			aplicacao: {},
			agenda: {},
			profissional: {}
		};
		$scope.status = {
	      agenda: {
	      	drop: {},
	      	block: {}
	      }
	    };

		$scope.$on("aplicacao:loading", function(event, status){
	      $scope.results.aplicacao.loading = status;
	    }); 

	    $scope.$on("aplicacao", function(event, aplicacao){
			if(aplicacao.results){
				$scope.aplicacao = aplicacao.results;
				$scope.loadProfissional();
			}
		});

		$scope.$on("profissionais:loading", function(event, status){
	      $scope.results.profissional.loading = status;
	    }); 
			
		$scope.$on("profissionais", function(event, profissional){
			if(profissional.results){
				$scope.profissionais = [];
				profissional.results.forEach(function(profissional){ 
					$scope.profissionais.push({
						id: profissional.id,
						title: profissional.nome,
						eventColor: profissional.cor
					});
				});
				$scope.loadAgenda();
			}
		});

		$scope.$on("agendas:loading", function(event, status){
	      $scope.results.agenda.loading = status;
	    }); 
			
		$scope.$on("agendas", function(event, agenda){
			if(agenda.results){
				$scope.agendas = [];
				agenda.results.forEach(function(agenda){ 
					$scope.agendas.push({
						id: agenda.id,
						resourceId: agenda.idprofissional,
						title: agenda.status==6 ? 'Horário bloqueado' : agenda.cliente + ' - ' + agenda.servico,
						start: agenda.dtainicio,
						end: agenda.dtafim,
						status: agenda.status
					});
				});
				$scope.calendar();
			}
		});

		$rootScope.$on('agenda:drop', function(event, status) {
			$scope.status.agenda.drop = {
			    loading: (status == 'loading'),
			    success: (status == 'success'),
			    error: (status == 'error')
			};
	    });

	    $rootScope.$on('agenda:block', function(event, status) {
			$scope.status.agenda.block = {
			    loading: (status == 'loading'),
			    success: (status == 'success'),
			    error: (status == 'error')
			};
	    });

	    $rootScope.$on('agendas:block:message:success', function(event, message){
			if(message.success){
				var results = message.results;
				var agendas = [];
				agendas.push({
		  			id: results.id,
					resourceId: results.idprofissional,
					title: 'Horário bloqueado',
					start: results.dtainicio,
					end: results.dtafim,
					color: '#F08575',
					borderColor: '#E1DFD',
					backgroundColor: '#E1DFDB'
		  		});
		  		$('#calendar').fullCalendar('addEventSource',agendas);
			}
		});

		$scope.calendar = function(){
			$('#calendar').fullCalendar({
				schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
				locale: 'pt-br',
				timezone: 'local',
				ignoreTimezone: true,
				defaultView: 'agendaDay',
				slotDuration: '00:15:00',
				defaultDate: ($scope.aplicacao.ano+'-'+$scope.aplicacao.mes+'-'+$scope.aplicacao.dia),
				editable: true,
				eventLimit: true,
				selectable: true,
				header: {
					left: 'agendaDay,agendaWeek',
					center: 'today, prev, title, next',
					right: ''
				},
				titleFormat: 'ddd D MMM, YYYY',
				allDaySlot: false,
				resources: $scope.profissionais,
				events: $scope.agendas,
				eventDrop: function(event,delta,revertFunc) {
			        if (!confirm("Deseja realmente mover o agendamento?")) {
			            revertFunc();
			        }else{
			        	agendaService.drop({
			        		id: event.id,
			        		dtainicio: moment(event.start).format("YYYY-MM-DD HH:mm:ss"),
			        		dtafim: moment(event.end).format("YYYY-MM-DD HH:mm:ss"),
			        		profissional: event.resourceId
			        	});
			        }
			    },
			    select: function(start,end,event,view,resource) {
			    	$scope.tooltip(event);
			    	$scope.agendamento.start = new Date(start);
			    	$scope.agendamento.end = new Date(end);
			    	$scope.agendamento.profissional = resource.id;
			    },
			    eventRender: function(event, element) {   
			    	//console.log(event);
			    	if(event.status==6){
			    		console.log('passou aqui')
						element.find(".fc-event").css('color', '#F08575');
						element.find(".fc-event").css('background', '#E1DFDB');
						element.find(".fc-event").css('border', '#E1DFDB');
			    	}else{
				    	element.find(".fc-title").prepend("<i class='md md-account-child'></i> ");
			    	}
				},
				eventResize: function(event,delta,revertFunc) {
			        if (!confirm("Deseja realmente modificar o agendamento?")) {
			            revertFunc();
			        }else{
			        	agendaService.drop({
			        		id: event.id,
			        		dtainicio: moment(event.start).format("YYYY-MM-DD HH:mm:ss"),
			        		dtafim: moment(event.end).format("YYYY-MM-DD HH:mm:ss"),
			        		profissional: event.resourceId
			        	});
			        }

			    } 
			    // eventClick: function(event) {
	      //           console.log(event);
	      //       }
			});
		}

		$scope.loadAplicacao = function(){
			aplicacaoService.getDate();
		}

		$scope.loadProfissional = function(){
			profissionalService.getList();
		}

		$scope.loadAgenda = function(){
			agendaService.getList();
		}

		$scope.tooltip = function(attributes){
			$('.tooltip-calendar').removeClass('hidden');
			$('.tooltip-calendar').css('top', attributes.clientY-attributes.offsetX);
			$('.tooltip-calendar').css('left', attributes.clientX-attributes.offsetY);
		}

		$scope.tooltipClose = function(){
			$('.tooltip-calendar').addClass('hidden');
		}

		$scope.schedule = function(){
			$scope.tooltipClose();
			var modalInstance = $uibModal.open({
				backdrop: 'static',
		      	templateUrl: 'views/agenda/scheduling.html',
		      	controller: 'adicionarAgendamentoController',
		      	resolve: {
		        	agendamento: function () {
		          		return $scope.agendamento;
		        	}
		      	}
		  	});
		  	modalInstance.result.then(function(){}, 
		  		function(event){
		  			if(event!=undefined && event.id){
		  				var agendas = [];
				  		agendas.push({
				  			id: event.id,
							resourceId: event.idprofissional,
							title: event.cliente,
							start: event.dtainicio,
							end: event.dtafim
				  		});
				  		$('#calendar').fullCalendar('addEventSource',agendas);
			  		}
		    });
		}

		$scope.scheduleBlock = function(){
			$scope.tooltipClose();
			var agendas = {
				profissional: $scope.agendamento.profissional,
				dtainicio: moment($scope.agendamento.start).format("YYYY-MM-DD HH:mm:ss"),
				dtafim: moment($scope.agendamento.end).format("YYYY-MM-DD HH:mm:ss")
			}
			agendaService.set(agendas);
      		agendaService.block();
		}

		// /*variables*/
		// $scope.serverDate = undefined;
		// $scope.week = [];
		// $scope.daySun = 0;
		// $scope.descMonth = undefined;
		// $scope.descYear = undefined;
		// $scope.descWeek = 'esta semana'; /* default: 'esta semana', 'semana que vem', 'em 2 semanas', 'em 3 semanas', 'em 4 semanas' */
		// $scope.isloading, $scope.isagenda = false;
		// $scope.scheduling = [];
		// $scope.servico = [];
		// $scope.services = [];
		// $scope.professional = [];
		// $scope.agendamento = [];
		// $scope.professionalservices = [];
		// $scope.daywork = [];
		// $scope.commitmentmarked = [];
		// $scope.timePicker = [];
		// $scope.clientesdodia = [];
		// $scope.dayCalendar = undefined;
		// $scope.activeweekmanual = undefined;
		// $scope.activeweeknotdaymanual = undefined;
		// $scope.btcontinue = false;
		// $scope.autentication = false;
		// $scope.agendado = false;
		// $scope.step = 0; /*first step form*/
		// $scope.filtros = [];
		// var hashservice = undefined;
		// var hashcompany = undefined;

		// $scope.servicoscliente	= [];
		// $scope.clientesdodia = [];

		// $scope.cancel = function(agenda){
		// 	var modalInstance = $uibModal.open({
		// 		templateUrl: 'views/confirm.html',
		// 		controller: 'cancelarAgendamentoController',
		// 		resolve: {
		// 			agenda: function () {
		// 		        return agenda;
		// 		    }
		// 		}

		// 	});
		// 	/* funcao ao cancelar ou fechar o modal */
		// 	modalInstance.result.then(function () {
		// 		$scope.calendarDateWeekSelected($scope.scheduling.timeweek);
		// 	}, function () {
		// 		$scope.calendarDateWeekSelected($scope.scheduling.timeweek);
		// 	});
		// };

		// /*--cancelar agendamento--*/
		// $scope.details = function(agenda){
		// 	var modalInstance = $uibModal.open({
		// 		backdrop: true,
		//       	templateUrl: 'views/agenda/details.html',
		//       	controller: 'detalharAgendamentoController',
		//       	resolve: {
		//         	agenda: function () {
		//           		return agenda;
		//         	}
		//       	}
		//   	});
		//   	modalInstance.result.then(function () {
		//   		$scope.calendarDateWeekSelected($scope.dayCalendar);
		//     }, function () {
		//     	$scope.calendarDateWeekSelected($scope.dayCalendar);
		//     });

		// };


		// /*--novo agendamento--*/
		// $scope.newscheduling = function(servico,profissional,datahora){
		// 	var modalInstance = $uibModal.open({
		// 		backdrop: true,
		//       	templateUrl: 'views/agenda/scheduling.html',
		//       	controller: 'adicionarAgendamentoController',
		//       	resolve: {
		//         	servico: function () {
		//           		return servico;
		//         	},
		//         	profissional: function () {
		//           		return profissional;
		//         	},
		//         	datahora: function () {
		//           		return datahora;
		//         	}
		//       	}
		//   	});
		//   	modalInstance.result.then(function () {
		//   		$scope.calendarDateWeekSelected($scope.dayCalendar);
		//     }, function () {
		//     	$scope.calendarDateWeekSelected($scope.dayCalendar);
		//     });

		// };

		// /*get day in month calendar*/
		// $scope.calendar = function(){
		// 	var $promise = $http.post('/controller/plataforma/agenda/getdate');
		// 	$promise.then(function(item){
		// 		/*set date server*/
		// 		$scope.serverDate = new Date(item.data.year, (item.data.month-1), item.data.day);

		// 		$scope.week = calendarioFactory.getDaysInMonth(item.data.year, item.data.month, item.data.day);
		// 		$scope.descMonth = calendarioFactory.getMonthDescription(item.data.month);
		// 		$scope.descYear = item.data.year;

		// 		var $dateCurrent = new Date(item.data.year, item.data.month-1, item.data.day);
		// 		var $dateCurrentDay = $dateCurrent.getDay();
		// 		var $timeCurrentStart = undefined;
		// 		var $timeCurrentFinal = undefined;
		// 		var $dateCurrentOk = 0;
		// 		var $dateCurrentHoliday = 0;

		// 		/*set day in calendar for actions in filter and others*/
		// 		$scope.dayCalendar = $dateCurrent;

		// 		for(var $i = 0; $i < $scope.daywork.length; $i++){
		// 			if( parseInt($dateCurrentDay) == parseInt($scope.daywork[$i].dia) ){
		// 				$dateCurrentOk++;
		// 				$timeCurrentStart = $scope.daywork[$i].horainicial;
		// 				$timeCurrentFinal = $scope.daywork[$i].horafinal;
		// 			}
		// 		}
		// 		/*Check if the current day is Sunday and it is a day of work, if not ... sets the current day for the next day*/
		// 		if( parseInt($dateCurrentDay) == 0 && $dateCurrentOk == 0){
		// 			$dateCurrentDay = $dateCurrentDay+1;
		// 			for(var $i = 0; $i < $scope.daywork.length; $i++){
		// 				if( parseInt($dateCurrentDay) == parseInt($scope.daywork[$i].dia) ){
		// 					$dateCurrentOk++;
		// 					$timeCurrentStart = $scope.daywork[$i].horainicial;
		// 					$timeCurrentFinal = $scope.daywork[$i].horafinal;
		// 					/*set day*/
		// 					item.data.day = parseInt(item.data.day)+1;
		// 					$scope.serverDate = new Date((item.data.month) +'/'+ item.data.day + '/'+ item.data.year); /*format "mm/dd/yyyy hh:mm:ss";*/
		// 				}
		// 			}
		// 		}
		// 		/*Check if the current day is not day of work, if not ... sets the current day for the next day*/
		// 		if( $dateCurrentOk == 0){
		// 			/*verificated day current is day work*/
		// 			var $firstdaywork = [];
		// 			for( var i = 0; i < $scope.week.length; i++ ){
		// 				if( $scope.daywork[0].dia == $scope.week[i].getDay() ){
		// 					$firstdaywork.push({'dia': $scope.week[i]});
		// 				}
		// 			}

		// 			$dateCurrentOk++;
		// 			$timeCurrentStart = $scope.daywork[0].horainicial;
		// 			$timeCurrentFinal = $scope.daywork[0].horafinal;
		// 			$scope.activeweeknotdaymanual = $firstdaywork[0].dia;
		// 			item.data.day = $scope.activeweeknotdaymanual.getDate();
		// 		}

		// 		if( $dateCurrentOk ){
		// 			var d_start = new Date((item.data.month) +'/'+ item.data.day + '/'+ item.data.year + ' ' + $timeCurrentStart); /*format "mm/dd/yyyy hh:mm:ss"*/
		// 			var d_end = new Date((item.data.month) +'/'+ item.data.day + '/'+ item.data.year + ' ' + $timeCurrentFinal);   /*format "mm/dd/yyyy hh:mm:ss"*/
					
		// 			/*create and clear timePicker*/
		// 			var $timePicker = [];

		// 		    while( d_start <= d_end ){
		// 		        var m = (((d_start.getMinutes() + 7.5)/15 | 0) * 15) % 60;
		// 		        var h = ((((d_start.getMinutes()/105) + .5) | 0) + d_start.getHours()) % 24;
		// 		        var schedule = new Date(d_start);
		// 		        schedule.setHours(h);
		// 		        schedule.setMinutes(m);
		// 		        $timePicker.push({ 'id': 0, 'time' : schedule, 'schedulingtime' : false, 'cliente': '' });
		// 		        /*add 15 minutes in date current selected*/
		// 		        d_start.setMinutes(d_start.getMinutes() + parseInt( $scope.scheduling.professional.tempoconsulta));
		// 		    }

		// 		    $scope.timePicker = $timePicker;

		// 		    /*remove itens time in time picker*/
		// 		    /*verificated exists commitment marked schedule in professional*/
		// 		    /*function loader commitment marked in schedule professional*/
		// 		    var $promise = $http.post('/controller/plataforma/agenda/getcommitmentmarked', {date: $scope.serverDate, hash: hashcompany, professional: $scope.scheduling.professional.id });
		// 		    /* clear array commitment marked */
		// 		    $scope.commitmentmarked = [];
		// 			$promise.then(function(item){
		// 				$scope.commitmentmarked = item.data;
		// 				var horarios = new Array ();
		// 				var clientes = new Array ();
		// 				var ids = new Array();
		// 				/*remove itens time in time picker*/
		// 			    /*verificated exists commitment marked schedule in professional*/
		// 			    if( $scope.commitmentmarked.length >= 1 ){
		// 	        		for( var $i = 0; $i < $scope.commitmentmarked.length; $i++ ){
		// 	        			var marked_start = new Date($scope.commitmentmarked[$i].horainicial);
		// 				    	var marked_end = new Date($scope.commitmentmarked[$i].horafinal);
		// 				    	for( var $key = 0; $key < $scope.timePicker.length; $key++ ){

		// 				    		var $tpt = new Date( $scope.timePicker[$key].time );
		// 							$tpt.setMinutes( $tpt.getMinutes() + 1 );
		// 				    		if( $tpt >= marked_start && $tpt <= marked_end ){
		// 				    			horarios.push($scope.timePicker[$key].time);
		// 				    			clientes.push($scope.commitmentmarked[$i].nome);
		// 				    			ids.push($scope.commitmentmarked[$i].id);
		// 				    			$scope.timePicker[$key].schedulingtime = true;
		// 				    		}else{
		// 				    			$scope.timePicker[$key].schedulingtime = false;
		// 				    		}
		// 				    	}
		// 	        		}
		// 	        		for( var $key = 0; $key < $scope.timePicker.length; $key++ ){
		// 	        			for( var $x = 0; $x < horarios.length; $x++ ){
		// 	        				if(horarios[$x]==$scope.timePicker[$key].time){
		// 	        					$scope.timePicker[$key].schedulingtime = true;
		// 	        					$scope.timePicker[$key].cliente = clientes[$x];
		// 	        					$scope.timePicker[$key].id = ids[$x];
		// 	        					break;
		// 	        				}
		// 	        			}
		// 	        		}
		// 		        }
		// 		        $scope.isloading = false;
		// 				$scope.isagenda = true;
						
		// 			});
		// 		}
		// 	});
		// };/*end function*/
		
		// /*get day in week selected on calendar */
		// $scope.calendarDateWeekSelected = function(date){
		// 	var dataag = moment(date).format("YYYY-MM-DD");
		// 	$scope.retornaclientesdodia($scope.professional[0].id, dataag);

		// 	$scope.isloading = true;
		// 	$scope.isagenda = false;
		// 	var $dateCurrent = new Date(date);
		// 	var $dateCurrentDay = $dateCurrent.getDay();
		// 	var $timeCurrentStart = undefined;
		// 	var $timeCurrentFinal = undefined;
		// 	var $dateCurrentOk = 0;
		// 	var $timePicker = [];

		// 	/*set day in calendar for actions in filter and others*/
		// 	$scope.dayCalendar = $dateCurrent;

		// 	for(var $i = 0; $i < $scope.daywork.length; $i++){
		// 		if( parseInt($dateCurrentDay) == parseInt($scope.daywork[$i].dia) ){
		// 			$dateCurrentOk++;
		// 			$timeCurrentStart = $scope.daywork[$i].horainicial;
		// 			$timeCurrentFinal = $scope.daywork[$i].horafinal;
		// 		}
		// 	}
		// 	if( $dateCurrentOk ){
		// 		$timeCurrentStart = $timeCurrentStart.split(':');
		// 		var d_start = new Date(date);
		// 			d_start.setHours($timeCurrentStart[0]);
		// 			d_start.setMinutes($timeCurrentStart[1]);
		// 		$timeCurrentFinal = $timeCurrentFinal.split(':');
		// 		var d_end = new Date(date);
		// 			d_end.setHours($timeCurrentFinal[0]);
		// 			d_end.setMinutes($timeCurrentFinal[1]);	
		// 		/*clear array timePicker and set news values */
		// 		$scope.timePicker = [];
		// 		$scope.timePicker.length = 0;
		// 		for( var $tp = 0; $tp < $scope.timePicker.length; $tp ){
		// 			$scope.timePicker.pop();
		// 		}
		// 		while( d_start <= d_end ){
		// 	        var m = (((d_start.getMinutes() + 7.5)/15 | 0) * 15) % 60;
		// 	        var h = ((((d_start.getMinutes()/105) + .5) | 0) + d_start.getHours()) % 24;
		// 	        var schedule = new Date(d_start);
		// 	        schedule.setHours(h);
		// 	        schedule.setMinutes(m);
		// 	        $timePicker.push({ 'id': 0, 'time' : schedule, 'schedulingtime' : false, 'cliente': '' });
		// 	        /*add 15 minutes in date current selected*/
		// 	        d_start.setMinutes(d_start.getMinutes() + parseInt( $scope.scheduling.professional.tempoconsulta));
		// 	    }
		// 	    $scope.timePicker = $timePicker;

		// 	    /*function loader commitment marked in schedule professional*/
		// 	    var $promise = $http.post('/controller/plataforma/agenda/getcommitmentmarked', {date: date, professional: $scope.scheduling.professional.id});
		// 	    /* clear array commitment marked */
		// 	    $scope.commitmentmarked = [];
		// 		$promise.then(function(item){
		// 			$scope.commitmentmarked = item.data;
		// 			var horarios = new Array ();
		// 				var clientes = new Array ();
		// 				var ids = new Array();
		// 			/*remove itens time in time picker*/
		// 		    /*verificated exists commitment marked schedule in professional*/
		// 		    if( $scope.commitmentmarked.length >= 1 ){
		//         		for( var $i = 0; $i < $scope.commitmentmarked.length; $i++ ){

		//         			var marked_start = new Date($scope.commitmentmarked[$i].horainicial);
		// 			    	var marked_end = new Date($scope.commitmentmarked[$i].horafinal);

		// 			    	for( var $key = 0; $key < $scope.timePicker.length; $key++ ){
		// 			    		var $tpt = new Date( $scope.timePicker[$key].time );
		// 						$tpt.setMinutes( $tpt.getMinutes() + 1 );

		// 			    		if( $tpt >= marked_start && $tpt <= marked_end ){
		// 				    			//$scope.timePicker[$key].time = undefined;
		// 				    			horarios.push($scope.timePicker[$key].time);
		// 				    			clientes.push($scope.commitmentmarked[$i].nome);
		// 				    			ids.push($scope.commitmentmarked[$i].id);
		// 				    			$scope.timePicker[$key].schedulingtime = true;
		// 				    		}else{
		// 				    			$scope.timePicker[$key].schedulingtime = false;
		// 				    		}
		// 			    	}
		// 	        		for( var $key = 0; $key < $scope.timePicker.length; $key++ ){
		// 	        			for( var $x = 0; $x < horarios.length; $x++ ){
		// 	        				if(horarios[$x]==$scope.timePicker[$key].time){
		// 	        					$scope.timePicker[$key].schedulingtime = true;
		// 	        					$scope.timePicker[$key].cliente = clientes[$x];
		// 	        					$scope.timePicker[$key].id = ids[$x];
		// 	        					break;
		// 	        				}
		// 	        			}
		// 	        		}

		//         		}

		// 	        }
		// 	        $scope.isloading = false;
		// 			$scope.isagenda = true;
		// 		});
		// 	}
		// };/*end function*/

		// /*function disabled day equal sunday = 0 in array days*/
		// $scope.disabledSun = function(day, daywork){
		// 	var $result = calendarioFactory.getDisabledDaySun(day, daywork);
		// 	return $result;
		// };/*end function*/

		// /*funciton compared date to current date in active today*/
		// $scope.comparedCurrentDate = function(dateweek, dateserver){
		// 	var $result = calendarioFactory.getComparedDateCurrentDate(dateweek, dateserver);
		// 	return $result;
		// };/*end function*/

		// /*function change description week*/
		// $scope.changeDescWeek = function(description){
		// 	$scope.$apply(function(){
		// 		$scope.descWeek = description;
		// 	});
		// };/*end function*/

		// /*function loader professional*/
		// $scope.getprofessional = function(){
		// 	$scope.isloading = true;
		// 	$scope.isagenda = false;
		// 	var $promise = $http.post('/controller/plataforma/agenda/getprofessional', {establishment: hashcompany, service: hashservice});
		// 	$promise.then(function(item){
		// 		$scope.professional =  item.data;
		// 		$scope.scheduling.professional = item.data[0];
		// 		$scope.getprofessionaldaywork(item.data[0]);
		// 		var dataag = moment(new Date()).format("YYYY-MM-DD");
		// 		$scope.retornaclientesdodia($scope.professional[0].id, dataag);
		// 	});

			

		// };/*end function*/

		// /*function loader professional day work */
		// $scope.getprofessionaldaywork = function(professional){
		// 	var $promise = $http.post('/controller/plataforma/agenda/getprofessionaldaywork', {professional: professional});
		// 	$promise.then(function(item){
		// 		$scope.daywork = item.data;
		// 		$scope.getservices();
		// 	});	
		// };/*end function*/

		

		// /*function loader services execute*/
		// $scope.getservices = function(){
		// 	var $promise = $http.post('/controller/plataforma/agenda/getservice');
		// 	$promise.then(function(item){
		// 		$scope.services = item.data[0];
		// 		/* loader calendar */
		// 		$scope.calendar();
		// 	});
		// };/*end function*/

		// /*function loader professional*/
		// $scope.getprofessionalservice = function(idprofessional){
		// 	var $promise = $http.post('/controller/plataforma/agenda/getprofessionalservice', {establishment: hashcompany, idprofessional: idprofessional});
		// 	$promise.then(function(item){
		// 		$scope.professionalservices =  item.data;
		// 		$scope.newscheduling($scope.professionalservices, $scope.scheduling.professional, $scope.scheduling.timeweek);
		// 	});
		// };/*end function*/

		// /*function change professional*/
		// $scope.changeProfessional = function(){
		// 	/*enable loading calendar*/
		// 	$scope.isloading = true;
		// 	$scope.isagenda = false;
		// 	/*hidden btn continue*/
		// 	$scope.btcontinue = false;
		// 	/*hidden login or signup*/
		// 	$scope.autentication = false;
		// 	/*enable first step*/
		// 	$scope.step = 1;
		// 	/*clear not day work and sunday*/
		// 	$scope.activeweekmanual = undefined;
		// 	$scope.activeweeknotdaymanual = undefined;

		// 	var $promise = $http.post('/controller/marketplace/getprofessionaldaywork', {professional: $scope.scheduling.professional});
		// 	$promise.then(function(item){
		// 		if(item.data != null){
		// 			$scope.daywork = item.data;
					
		// 			/*get next useful day work in week*/
		// 			var $dateuseful = undefined;
		// 			var $dw = item.data;
		// 			var $datesmaller = [];

		// 			for( var $i = 0; $i <= 6; $i++ ){
		// 				for( var $x = 0; $x < $dw.length; $x++ ){
		// 					if( $dw[$x].dia == $scope.week[$i].getDay()  ){
		// 						$datesmaller.push({ 'position' : $dw[$x].dia });
		// 					}
		// 				}
		// 			}
					
		// 			for( var $i = 0; $i <= 6; $i++ ){
		// 				if( $datesmaller[0].position == $scope.week[$i].getDay() ){
		// 					$dateuseful = $scope.week[$i];
		// 				}
		// 			}

		// 			/*remove all class in week*/
		// 			var $liCalendar =  angular.element( $jq( '.calendario .faixa-dias .dias li' ) );
		// 			$liCalendar.removeClass('selecionado');
		// 			$liCalendar.removeClass('selecionadosunday');
		// 			$liCalendar.removeClass('selecionadonotday');
		// 			/* set day work manual */
		// 			$scope.activeweekmanual = $dateuseful;
		// 			$scope.calendarDateWeekSelected($dateuseful);

		// 			//retorna a lista de clientes de hoje
		// 			var dataag = moment($dateuseful).format("YYYY-MM-DD");
		// 			$scope.retornaclientesdodia($scope.scheduling.professional.id, dataag);	
		// 		}else{
		// 			$scope.notscheduling = true;
		// 		}
		// 	});
			
			
			
		// }

		// /*function click date week day*/
		// $scope.timePickerSelectDate = function(date){
		// 	var $okfunction = false; /* result: true = day disabled or false =  day ok(show) in week */
		// 	if( $scope.disabledSun(date.getDay(), $scope.daywork ) )
		// 		$okfunction = true
		// 	else
		// 		$okfunction = false;

		// 	if( !$okfunction ){
		// 		/* function calendar date day week and list times attendance professional */
		// 		$scope.calendarDateWeekSelected(date);
		// 		/*hidden btn continue*/
		// 		$scope.btcontinue = false;
		// 	}
		// };/*end function*/

		// /*function click time date week*/
		// $scope.timeSelectDate = function(date, agendado){
		// 	$scope.scheduling.timeweek = date;
		// 	if(!agendado)
		// 		$scope.getprofessionalservice($scope.scheduling.professional.id);
		// };/*end function*/

		// /*funcao para verificar se o horario esta ou nao agendado*/
		// $scope.verificaHora = function(prof, datahora){
		// 	var dt = ""+datahora;
		// 	var $promise = $http.post('/controller/plataforma/agenda/verificaagendamento', {prof: prof, datahora: dt});
		// 	$promise.then(function(item){
		// 		$scope.agendado = item.message;
		// 	});
		// }

		// $scope.retornaclientesdodia = function(prof, datahora){
		// 	var dt = ""+datahora;
		// 	var $promise = $http.post('/controller/plataforma/agenda/clientesdodia', {prof: prof, datahora: dt});
		// 	$promise.then(function(item){
		// 		$scope.clientesdodia = [];
		// 		$scope.clientesdodia = item.data;
				
		// 	});
		// }

  // 		$scope.cancelarAgendamento = function(agendamento){
  // 			var $promise = $http.post('/controller/plataforma/agenda/cancelaragendamento', {agendamento: agendamento});
		// 	$promise.then(function(item){
		// 		if(item.data.status == "success"){
		// 			alert(item.data.message);
		// 			$scope.calendar();
		// 		}
		// 	});
  // 		}

  // 		$scope.remarcarAgendamento = function(agendamento){
  // 			window.location.href = "/plataforma/agenda/remarcacao/"+agendamento;
  // 		}

  // 		$scope.finalizarAgendamento = function(agendamento){
  // 			window.location.href = "/plataforma/agenda/finalizacao/"+agendamento;
  // 		}

  // 		$scope.setToday = function(){
		// 	$scope.filtros.datainicio = new Date();	
		// 	$scope.filtros.datafim = new Date();	
		// };

		// $scope.setMonthCurrent = function(){
		// 	var date = new Date();
		// 	$scope.filtros.datainicio = new Date(date.getFullYear(), date.getMonth(), 1);
		// 	$scope.filtros.datafim = new Date(date.getFullYear(), date.getMonth() + 1, 0);
		// };

		// $scope.setMonthLast = function(){
		// 	var date = new Date();
		// 	$scope.filtros.datainicio = new Date(date.getFullYear(), date.getMonth() - 1, 1);
		// 	$scope.filtros.datafim = new Date(date.getFullYear(), date.getMonth(), 0);
		// };

		// $scope.setLast30Days = function(){
		// 	$scope.filtros.datainicio = new Date(new Date().setDate(new Date().getDate() - 30));
		// 	$scope.filtros.datafim = new Date();
		// };

  // 		/*funcao para retornar os filtros*/
  // 		$scope.filter = function(filter){
  // 			var modalInstance = $uibModal.open({
		// 			backdrop: true,
		// 			size: 'lg',
		// 	      	templateUrl: 'views/agenda/filter.html',
		// 	      	controller: function ($scope, $uibModalInstance, ft, timeweek) {

		// 	      		/*variable*/
		// 	      		$scope.filter = ft;
		// 	      		$scope.resultado = {
		//       				isloading: true
		//       			};
		// 	      		$scope.totalItems 	= 0;
		// 				$scope.currentPage 	= 1;
		// 				$scope.numPerPage 	= 5;
		// 				$scope.entryLimit 	= 5;
		//       			$scope.resultado.offset = 0;
		// 				$scope.resultado.limit = $scope.numPerPage;


		//       			$scope.getList = function(){
		//       				/*get result filter*/
		// 	      			var $promise = $http.post('/controller/plataforma/agenda/getfilterscheduling', {
		// 	      								nome: $scope.filter.nomecliente,
		// 	      								cpf: $scope.filter.cpfdocliente,
		// 	      								celular: $scope.filter.celulardocliente,
		// 	      								datainicio: $scope.filter.datainicio,
		// 	      								datafim: $scope.filter.datafim,
		// 	      								offset: $scope.resultado.offset,
		// 	      								limit: $scope.resultado.limit
		// 	      							});
		// 	      			$promise.then(function(item){
		// 	      				if(item.data.status != 'error'){
		// 	      					$scope.totalItems = item.data.count.results;
		// 	      					$scope.resultado.results = item.data.results;
		// 	      					$scope.resultado.count = item.data.count;
		// 	      				}
		// 	      				$scope.resultado.isloading = false;
		// 	      			}, function(){
		// 	      				$scope.resultado.results = undefined;
		// 	      				$scope.resultado.isloading = false;
		// 	      			});
		//       			};

		//       			$scope.changePaginate = function(){
		// 					$scope.resultado.offset = ($scope.currentPage - 1) * $scope.numPerPage;
		// 					$scope.resultado.limit = $scope.numPerPage;
		// 					$scope.getList();
		// 				};

		// 				$scope.remarcarAgendamento = function(agendamento){
		// 		  			window.location.href = "/plataforma/agenda/remarcacao/"+agendamento;
		// 		  		}

		// 		  		$scope.finalizarAgendamento = function(agendamento){
		// 		  			window.location.href = "/plataforma/agenda/finalizacao/"+agendamento;
		// 		  		}

		// 		  		$scope.cancelar = function(agendamento){
		// 					var modalInstance = $uibModal.open({
		// 						templateUrl: 'views/confirm.html',
		// 						controller: function ($scope, $uibModalInstance, ps) {
		// 							$scope.agendamento = ps;
		// 							$scope.ok = function() { 
		// 								var $promise = $http.post('/controller/plataforma/agenda/cancelaragendamento', {agendamento: agendamento});
		// 								$promise.then(function(item){
		// 									if(item.data.status == "success"){
		// 										var modalInstance = $uibModal.open({
		// 											templateUrl: 'views/message.html',
		// 											controller: function ($scope, $uibModalInstance, ps) {
		// 												$scope.message = ps;
		// 												$scope.ok = function() { 
		// 													$uibModalInstance.close();
		// 												};
		// 												$scope.cancel = function () {
		// 													$uibModalInstance.dismiss('cancel');
		// 												};
		// 											},resolve: {
		// 												ps: function () {
		// 											        return item.data.message;
		// 											    }
		// 											}

		// 										});
		// 									}
		// 								});
		// 								$uibModalInstance.close();
		// 							};
		// 							$scope.cancel = function () {
		// 								$uibModalInstance.dismiss('cancel');
		// 							};
		// 						},resolve: {
		// 							ps: function () {
		// 						        return agendamento;
		// 						    }
		// 						}

		// 					});
		// 					modalInstance.result.then(function () {
		// 						$scope.getList();
		// 					}, function(){
		// 						$scope.getList();
		// 					});
		// 				};

		// 				$scope.cancel = function () {
		// 				    $uibModalInstance.dismiss('cancel');
		// 				};
		// 	      	},
		// 	      	resolve: {
		// 	        	ft: function () {
		// 	          		return filter;
		// 	        	},
		// 	        	timeweek: function(){
		// 			    	return $scope.scheduling.timeweek;
		// 			    }
		// 	      	}
		// 	    });

		// 	/* funcao ao cancelar ou fechar o modal */
		// 	modalInstance.result.then(function () {
		// 		$scope.calendarDateWeekSelected($scope.dayCalendar);
		// 	}, function () {
		// 		$scope.calendarDateWeekSelected($scope.dayCalendar);
		// 	});
  // 		}

  // 		/*pesquisar por nome do cliente no filtro*/
		// $scope.filterSearchClient = function( name ){
		// 	if( name != undefined && name.length >= 3 ){
		// 		$http.post('/controller/plataforma/agenda/checkclientes', {nome: name})
		// 		.success(function( data ){
		// 			if( data && data.message != 'noresults' ){
		// 				$scope.filtros.resultados = data;
		// 				$scope.filtros.isAutocomplete = true;
		// 			}else if( data.message == 'noresults' ){
		// 				$scope.filtros.resultados  = null;
		// 			}else{
		// 				$scope.filtros.isAutocomplete = false;
		// 			}
		// 			$scope.filtros.totalresultados = data.length != 0 ? true : false;
		// 		})
		// 		.error(function(error){
		// 			console.log(error);
		// 		});
		// 	}else{
		// 		$scope.filtros.resultados = undefined;
		// 		$scope.filtros.isAutocomplete = false;
		// 	}
		// }
		// /*setar o item ao selecionar da busca*/
		// $scope.filterSelectedSearchClient = function(result){
		// 	$scope.filtros.isAutocomplete = false;
		// 	$scope.filtros.nomecliente = result.nome;
		// 	$scope.filtros.cpfdocliente = result.cpf;
		// 	$scope.filtros.resultados = undefined;
		// }

		// jQuery(document).ready(function(){
			
		// 	var $positioncurrent = 0;
		// 	var $positionprev = 0;
		// 	var $countWeeknext = 0;

		// 	if( $positioncurrent <= 6 )
		// 		$jq('.seta-esq').addClass('desabilitado');

		// 	$jq('.seta-esq').on('click toucstart', function(){
				
		// 	 	var $totaldays = $jq('ul.dias li').length;
		// 	 	var $aux = 0;

		// 	 	if( $countWeeknext > 0  && $countWeeknext <= 4)
		// 	 		$countWeeknext--;
		// 	 	else if($countWeeknext <= 4)
		// 	 		$countWeeknext = 0;

		// 		if( $positioncurrent == 0 ) 
		// 	 			$positioncurrent = 6 
		// 	 		else 
		// 	 			$positioncurrent = $positioncurrent; 

		// 	 	if( ($positioncurrent -6) <= $totaldays && ($positioncurrent -6) >= 0){

		// 	 		$positionprev = $positioncurrent - 6;

		// 	 		$aux = $positionprev - 6;

		// 	 		if( $aux >= 0 ){
		// 		 		for( var i = $aux; i <= $positionprev; i++ ){
		// 		 			$jq('ul.dias li:nth-child('+(i+1)+')').fadeIn(180);
		// 		 		}
		// 			}
		// 	 	}

		// 	 	$positioncurrent -= 6;

		// 	 	if( $positioncurrent <= 6 )
		// 			$jq('.seta-esq').addClass('desabilitado')
		// 			$jq('.seta-dir').removeClass('desabilitado');

		// 		switch( $countWeeknext ){
		// 			case 0:
		// 				$scope.changeDescWeek('esta semana');
		// 			break;
		// 			case 1:
		// 				$scope.changeDescWeek('semana que vem');
		// 			break;
		// 			case 2:
		// 				$scope.changeDescWeek('em 2 semanas');
		// 			break;
		// 			case 3:
		// 				$scope.changeDescWeek('em 3 semanas');
		// 			break;
		// 			case 4:
		// 				$scope.changeDescWeek('em 4 semanas');
		// 			break;
		// 		}

		// 	});
		// 	$jq('.seta-dir').on('click toucstart', function(){
		// 		var $totaldays = $jq('ul.dias li').length;

		// 		if( $countWeeknext >= 0 && $countWeeknext <= 3)
		// 			$countWeeknext++;
		// 		else if($countWeeknext <= 3)
		// 			$countWeeknext = 0;

		// 		if( $positioncurrent == 0 ) 
		// 	 			$positioncurrent = 6 
		// 	 		else 
		// 	 			$positioncurrent = $positioncurrent; 

		// 	 	if( ($positioncurrent +6) <= $totaldays ){
		// 		 	$jq('.dias li').each(function(i){
		// 		 		if( i <= $positioncurrent ){
		// 		 			$jq(this).delay(100).fadeOut(180);
		// 		 		}
		// 		 	});
		// 	 	}

		// 	 	$positioncurrent += 6;

		// 	 	if( $positioncurrent > 6 )
		// 			$jq('.seta-esq').removeClass('desabilitado');

		// 		if( $positioncurrent > $totaldays )
		// 			$jq(this).addClass('desabilitado');

		// 		switch( $countWeeknext ){
		// 			case 0:
		// 				$scope.changeDescWeek('esta semana');
		// 			break;
		// 			case 1:
		// 				$scope.changeDescWeek('semana que vem');
		// 			break;
		// 			case 2:
		// 				$scope.changeDescWeek('em 2 semanas');
		// 			break;
		// 			case 3:
		// 				$scope.changeDescWeek('em 3 semanas');
		// 			break;
		// 			case 4:
		// 				$scope.changeDescWeek('em 4 semanas');
		// 			break;
		// 		}

		// 	});
		// });

	}])