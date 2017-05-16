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

    function list_clientes(){

        //list
        app.util.getjson({
            url : "/controller/office/dashboard/list_clientes",
            method : 'POST',
            contentType : "application/json",
            success: function(response){
                var html = '';
                for (var i=0;i<response.results.length;i++) {
                    html += '<tr>'+
                                '<td>'+ (i+1) + '</td>'+
                                '<td>'+ response.results[i].nome + '</td>'+
                                '<td>'+ response.results[i].email + '</td>'+
                                '<td>'+ response.results[i].telefone + '</td>'+
                            '</tr>';
                }
                $("#table-clientes > tbody").html(html);
            },
            error : onError
        });
    }

    list();

    list_semanal();

    list_clientes()



});