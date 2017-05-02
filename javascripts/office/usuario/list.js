//variable global
var search = undefined;
var page = 1;
var offset = 0;
var limit = 10;
var status = 1;

$(document).ready(function(){

    //success
    if(getSession('success')){
        $('#success').removeClass('hidden');
        $('#success').find('p').html(getSession('success'));
    }
    setTimeout(function() {
        $('#success').addClass('hidden');
        destroySession('success');
    }, 5000);

    function list(reload = false, search = undefined, status = 1){
        var params = (search == undefined) ? 
                        {offset: offset, limit: limit, status: status} : 
                        {offset: offset, limit: limit, status: status, search: search};
        params = JSON.stringify(params);

        if(reload)
            $('#col-reload').removeClass('hidden');

        //remove all itens active
        $('.nav .nav-item a').removeClass('active');
        switch(parseInt(status)){
            case 1: 
                $('#ativo').addClass('active');
            break;
            case 2: 
                $('#inativo').addClass('active');
            break;
        }

        //list
        app.util.getjson({
            url : "/controller/office/usuario/list",
            method : 'POST',
            contentType : "application/json",
            data: params,
            success: function(response){
                var html = '';
                for (var i=0;i<response.results.length;i++) {
                    role = (response.results[i].perfil == 1) ? 'Acesso comum' : 'Gestor';
                    labelRole = (response.results[i].perfil == 1) ? 'label-info' : 'label-success'; 
                    status = (response.results[i].status == 1) ? 'Ativo' : 'Inativo'; 
                    labelStatus = (response.results[i].status == 1) ? 'label-success' : 'label-danger'; 
                    html += '<tr>'+
                                '<td>'+ response.results[i].id + '</td>'+
                                '<td>'+ response.results[i].nome + '</td>'+
                                '<td>'+ response.results[i].sobrenome + '</td>'+
                                '<td>'+ response.results[i].email + '</td>'+
                                '<td class="text-center"><span class="label '+labelRole+'">'+role+'</span></td>'+
                                '<td class="text-center"><span class="label '+labelStatus+'">'+status+'</span></td>'+
                                '<td class="text-center">'+
                                    '<a href="/office/usuario/edit/'+ response.results[i].id +'" title="Editar"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>'+
                                '</td>'+
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
                if(parseInt(response.count.results) == 0){
                    html += '<tr>'+
                                '<td colspan="6">Nenhum registro encontrado</td>'+
                            '</tr>';
                }
                $('#table-loading').addClass('hidden');
                $('ul.customtab').removeClass('hidden');
                $('#count-ativo').html(response.count.ativos);
                $('#count-inativo').html(response.count.inativos);
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
        if(search == undefined){
          return;
        }else{
            list(true, search, status);
        }
    });

    $('a#ativo,a#inativo').livequery('click',function(event){
        status = $(this).data('status');
        list(true, search, status);
    });

	function onError(response) {
      console.log(response);
    }

    //init
    list();

});