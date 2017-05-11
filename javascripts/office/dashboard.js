$(document).ready(function(){

    //sair da conta
    $('a#sair').livequery('click',function(event){
        app.util.getjson({
            url : "/controller/guest/usuario/logout",
            method : 'POST',
            contentType : "application/json",
            success: function(response){
                if(response.success)
                    window.location.href = "/login";
            },
            error : onError
        });
        return false;
	});

	function onError(response) {
      console.log(response);
    }

    function list(){

        //alert("chamou");
        
        //list
        app.util.getjson({
            url : "/controller/office/dashboard/list",
            method : 'POST',
            contentType : "application/json",
            success: function(response){

                $('#count-perguntas').html('<h3 class="counter text-right m-t-15 text-info">'+response.count.perguntas+'</h3>');   
                $('#count-clientes').html('<h3 class="counter text-right m-t-15 text-success">'+response.count.clientes+'</h3>'); 
                $('#count-questionarios').html("<h3 class='counter text-right m-t-15 text-danger'>"+response.count.questionarios+"</h3>");    
                $('#count-usuarios').html('<h3 class="counter text-right m-t-15 text-warning">'+response.count.usuarios+'<h3>');          


            },
            error : onError
        });
    }

    function list_semanal(){

        //alert("chamou");
        
        //list
        app.util.getjson({
            url : "/controller/office/dashboard/list_semanal",
            method : 'POST',
            contentType : "application/json",
            success: function(response){

                $('#count-s-clientes').html('<h3 class="counter text-right m-t-15 text-success">'+response.count.clientes+'</h3>'); 
                $('#count-s-questionarios').html("<h3 class='counter text-right m-t-15 text-danger'>"+response.count.questionarios+"</h3>");    

            },
            error : onError
        });
    }

    list();

    list_semanal();


    Morris.Area({
        element: 'morris-area-chart',
        data: [{
            period: '2010',
            iphone: 50,
            ipad: 80,
            itouch: 20
        }, {
            period: '2011',
            iphone: 130,
            ipad: 100,
            itouch: 80
        }, {
            period: '2012',
            iphone: 80,
            ipad: 60,
            itouch: 70
        }, {
            period: '2013',
            iphone: 70,
            ipad: 200,
            itouch: 140
        }, {
            period: '2014',
            iphone: 180,
            ipad: 150,
            itouch: 140
        }, {
            period: '2015',
            iphone: 105,
            ipad: 100,
            itouch: 80
        },
         {
            period: '2016',
            iphone: 250,
            ipad: 150,
            itouch: 200
        }],
        xkey: 'period',
        ykeys: ['iphone', 'ipad', 'itouch'],
        labels: ['iPhone', 'iPad', 'iPod Touch'],
        pointSize: 3,
        fillOpacity: 0,
        pointStrokeColors:['#00bfc7', '#fdc006', '#9675ce'],
        behaveLikeLine: true,
        gridLineColor: '#e0e0e0',
        lineWidth: 1,
        hideHover: 'auto',
        lineColors: ['#00bfc7', '#fdc006', '#9675ce'],
        resize: true
        
    });

});