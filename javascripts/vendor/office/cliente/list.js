//variable global
var clientes = {};
var search = undefined;
var page = 1;
var offset = 0;
var limit = 10;

$(document).ready(function(){

    function list(reload = false, search = undefined){
        var params = (search==undefined) ? {offset: offset, limit: limit} : {offset: offset, limit: limit, search: search};
        params = JSON.stringify(params);

        if(reload)
            $('#col-reload').removeClass('hidden');

        //list
        app.util.getjson({
            url : "/controller/office/cliente/list",
            method : 'POST',
            contentType : "application/json",
            data: params,
            success: function(response){
                var html = '';
                for (var i=0;i<response.results.length;i++) {
                    html += '<tr>'+
                                '<td>'+ response.results[i].id + '</td>'+
                                '<td>'+ response.results[i].nome + '</td>'+
                                '<td>'+ response.results[i].email + '</td>'+
                                '<td>'+ response.results[i].telefone + '</td>'+
                            '</tr>';
                }
                if(parseInt(response.count.results) >= 1){

                    $('#col-total').removeClass('hidden');
                    $('#col-search').removeClass('hidden');
                    $('#col-note').removeClass('hidden');
                    $('#pagination-length').html('Exibindo ' + response.results.length + ' de ' + response.count.results + ' registros');
                    
                    var pagination = paginator(limit,page,response.count.results);
                    $('#pagination').html(pagination);

                }
                if(parseInt(response.count.results) == 0 && !reload){
                    html += '<tr>'+
                                '<td colspan="4">Nenhum registro encontrado</td>'+
                            '</tr>';
                }
                $('#table-loading').addClass('hidden');
                $('#table-results').removeClass('hidden');
                $("#table-results > tbody").html(html);
                if(reload)
                    $('#col-reload').addClass('hidden');
            },
            error : onError
        });
    }

    //paginator
    $('#pagination li a').livequery('click',function(event){
        if(!$(this).parent().hasClass('disabled')){
            page = parseInt($(this).data('page'));
            offset = Math.ceil((page-1) * limit);
            if(search != undefined && search.length >= 3){
                list(true, search);
            }else{
                list(true);
            }
        }
        return false;
    });

    //search
    $('input#search').livequery('keyup',function(event){
        search = $(this).val();
        if(search != undefined && search.length < 3){
          return;
        }else{
            list(true, search);
        }
    });

	function onError(response) {
      console.log(response);
    }

    //init
    list();

});