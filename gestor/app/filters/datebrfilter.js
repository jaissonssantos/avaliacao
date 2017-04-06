'use strict';

app
  .filter('datebr', function($filter) {
    return function(input) {
      if (input == null) {
        return "";
      }
      var day = input.substring(0, 2);
      var month = input.substring(2, 4);
      var year = input.substring(4, 9);

      var _date = $filter('date')(new Date(year, (month - 1), day), 'dd/MM/yyyy');
      return _date.toUpperCase();
    };
  })
  .filter('weekday', function($filter){
    return function(input){
      if (input == null) {
        return "";
      }
      var weekday = new Array('Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab');
      return weekday[input];
    };
  })
  .filter('weekdayextension', function($filter){
    return function(input){
      if (input == null) {
        return "";
      }
      var weekday = new Array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
      return weekday[input];
    };
  })
  .filter('monthabbr', function($filter){
    return function(input){
      if (input == null) {
        return "";
      }
      var monthabbr = new Array('Jan', 'Fev', 'Mar', 'Abr', 'Maio', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez');
      return monthabbr[(input-1)];
    };
  })
  .filter('datetoday', function($filter){
    return function(input, format){
      if (input == null) {
        return "";
      }
      return moment(new Date(input)).format(format);
    };
  })
  .filter('timedate', function($filter){
    return function(input, format){
      if (input == null) {
        return "";
      }
      return moment(new Date(input)).format(format);
    };
  })
  .filter('timeHours', function($filter){
    return function(input){
      if(input == null){
        return "";
      }
      var minutes = parseInt(input);
      if( minutes < 60 ){
        return minutes+ ' minutos';
      }
      if( minutes == 60 ){ /* 1 hour description */
        var hours = Math.floor(Math.abs(minutes) / 60);
        return hours+ ' hora';
      }
      if( minutes == 120 ){ /* 2 hour description */
        var hours = Math.floor(Math.abs(minutes) / 60);
        return hours+ ' horas';
      }
      if( minutes == 180 ){ /* 3 hour description */
        var hours = Math.floor(Math.abs(minutes) / 60);
        return hours+ ' horas';
      }
      if( minutes == 220 ){ /* 4 hour description */
        var hours = Math.floor(Math.abs(minutes) / 60);
        return hours+ ' horas';
      }
      if( minutes == 300 ){ /* 5 hour description */
        var hours = Math.floor(Math.abs(minutes) / 60);
        return hours+ ' horas';
      }
      if( minutes > 60 && minutes <= 119 ){
        var hours = Math.floor(Math.abs(minutes) / 60);
        var minutes = Math.abs(minutes) % 60;
        return hours+' hora ' + minutes + ' minutos';
      }
      if( minutes >= 120 ){
        var hours = Math.floor(Math.abs(minutes) / 60);
        var minutes = Math.abs(minutes) % 60;
        return hours+' horas ' + minutes + ' minutos';
      }
    }
  })
  .filter('ispage', function($filter){
    return function(input, format){
      if (input == null || input == undefined) {
        return "company";
      }else{
        return 'page';
      }
    };
  })
