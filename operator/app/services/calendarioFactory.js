'use strict';

app
	.factory('calendarioFactory', [ '$q', function($q){
		return {
			getMonthDescription: function( month ){
				var m = '';
				switch(parseInt(month)){
					case 1: m = 'Janeiro'; break;
					case 2: m = 'Feveiro'; break;
					case 3: m = 'Março'; break;
					case 4: m = 'Abril'; break;
					case 5: m = 'Maio'; break;
					case 6: m = 'Junho'; break;
					case 7: m = 'Julho'; break;
					case 8: m = 'Agosto'; break;
					case 9: m = 'Setembro'; break;
					case 10: m = 'Outubro'; break;
					case 11: m = 'Novembro'; break;
					case 12: m = 'Dezembro'; break;
				}
				return m;
			},

			getDaysInMonth: function( year, month, day ){
				month = (parseInt(month)-1);
         		var days = [];
         		var start = new Date(year, month , day);
         		var end = new Date(year, month +1 , day);
         		while (start <= end) {
            		days.push(new Date(start));
            		start.setDate(start.getDate() + 1);
         		}
         		return days;
			},

			getDisabledDaySun: function(day, daywork){
				if( day == null ){
					return "";
				}
				var retorno = 0;
				for(var i = 0; i < daywork.length; i++){
					if( parseInt(day) == parseInt(daywork[i].dia) ){
						retorno++;
					}
				}
				if( retorno == 0 ){/*sunday = 0 in days array*/
					return true;
				}
				return false;
			},

			getComparedDateCurrentDate: function(dateweek, dateserver){
				if( dateweek == null || dateserver == null )
					return '';
				if( dateweek - dateserver == 0 ){
					return true;
				}else{

				}
				return false;
			}

		};
	}]);