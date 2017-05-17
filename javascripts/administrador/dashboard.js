$(document).ready(function(){

    function list(){
        
        //list
        app.util.getjson({
            url : "/controller/administrador/dashboard/list",
            method : 'POST',
            contentType : "application/json",
            success: function(response){
                var html = '';

                $('#countEstabelecimento h4').html(response.count.estabelecimentos);   
                $('#countCliente h4').html(response.count.clientes); 
                $('#countQuestionario h4').html(response.count.questionarios);    
                $('#countUsuario h4').html(response.count.usuarios);          


            },
            error : onError
        });
    }

    function list_clientes(){

        //list
        app.util.getjson({
            url : "/controller/administrador/dashboard/listclientes",
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
            url : "/controller/administrador/dashboard/listestabelecimentos",
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

    function onError(response) {
      console.log(response);
    }

    list();
    list_clientes();
    list_estabelecimentos();

});