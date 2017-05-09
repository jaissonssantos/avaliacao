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
        
        //list
        app.util.getjson({
            url : "/controller/administrador/dashboard/list",
            method : 'POST',
            contentType : "application/json",
            success: function(response){
                var html = '';

                $('#count-estabelecimentos').html(response.count.estabelecimentos);   
                $('#count-clientes').html(response.count.clientes); 
                $('#count-questionarios').html(response.count.questionarios);    
                $('#count-usuarios').html(response.count.usuarios);          


            },
            error : onError
        });
    }

    function list_clientes(){

        //list
        app.util.getjson({
            url : "/controller/administrador/dashboard/list_clientes",
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

    function list_estabelecimentos(){

        //list
        app.util.getjson({
            url : "/controller/administrador/dashboard/list_estabelecimentos",
            method : 'POST',
            contentType : "application/json",
            success: function(response){
                var html = '';
                for (var i=0;i<response.results.length;i++) {
                    status = (response.results[i].status == 1) ? 'Ativo' : 'Inativo'; 
                    labelStatus = (response.results[i].status == 1) ? 'label-success' : 'label-danger'; 
                    html += '<tr>'+
                                '<td>'+ (i+1) + '</td>'+
                                '<td>'+ response.results[i].nomefantasia + '</td>'+
                                '<td>'+ response.results[i].cpfcnpj + '</td>'+
                                '<td><span class="label '+labelStatus+'">'+status+'</span></td>'+
                            '</tr>';
                }
                $("#table-estabelecimentos > tbody").html(html);
            },
            error : onError
        });
    }

    list();
    list_clientes();
    list_estabelecimentos();

});