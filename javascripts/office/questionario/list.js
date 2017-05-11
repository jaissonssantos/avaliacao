//variable global
var search = undefined;
var page = 1;
var offset = 0;
var limit = 10;
var status = 1;

$(document).ready(function(){

    function checkSuccess(){
        //success
        if(getSession('success')){
            $('#success').removeClass('hidden');
            $('#success').find('p').html(getSession('success'));
        }
        setTimeout(function() {
            $('#success').addClass('hidden');
            destroySession('success');
        }, 5000);
    }

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
            url : "/controller/office/questionario/list",
            method : 'POST',
            contentType : "application/json",
            data: params,
            success: function(response){
                var html = '';
                for (var i=0;i<response.results.length;i++) {
                    status = (response.results[i].status == 1) ? 'Ativo' : 'Inativo'; 
                    labelStatus = (response.results[i].status == 1) ? 'label-success' : 'label-danger'; 
                    html += '<tr>'+
                                '<td><input type="checkbox" class="checkItem" value="'+ response.results[i].id + '"></td>'+
                                '<td>'+ response.results[i].id + '</td>'+
                                '<td>'+ response.results[i].titulo + '</td>'+
                                '<td>'+ response.results[i].prazo + '</td>'+
                                '<td class="text-center"><span class="label '+labelStatus+'">'+status+'</span></td>'+
                                '<td class="text-center">'+
                                    '<a href="/office/questionario/edit/'+ response.results[i].id +'" title="Editar"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>'+
                                    '<a href="/forms/'+ response.results[i].hash +'" title="Pré visualização"> <i class="icon-eye text-inverse m-r-10"></i> </a>'+
                                    '<a href="/office/questionario/relatorio/'+ response.results[i].id +'" title="Relatório em tempo real"> <i class="icon-graph text-inverse m-r-10"></i> </a>'+
                                '</td>'+
                            '</tr>';
                }
                if(parseInt(response.count.results) >= 1){

                    $('#col-total').removeClass('hidden');
                    $('#col-search').removeClass('hidden');
                    $('#col-action').addClass('hidden');
                    $('#pagination-length').html('Exibindo ' + response.results.length + ' de ' + response.count.results + ' registros');
                    
                    var pagination = paginator(limit,page,response.count.results);
                    $('#pagination').html(pagination);

                }
                if(parseInt(response.count.results) == 0){

                    $('#notfound').removeClass('hidden');
                    $('#notfound').html('Nenhum registro encontrado');
                    $('#table-results').addClass('hidden');
                    $('#col-total').addClass('hidden');
                    if($('input#search').val() == undefined && search.length <= 3)
                        $('#col-search').addClass('hidden');
                    $('#col-action').addClass('hidden');
                }else{
                    $('#notfound').addClass('hidden');
                    $('#table-results').removeClass('hidden');
                    $("#table-results > tbody").html(html);
                }
                $('#table-loading').addClass('hidden');
                $('#add').removeClass('hidden');
                $('ul.customtab').removeClass('hidden');
                $('#count-ativo').html(response.count.ativos);
                $('#count-inativo').html(response.count.inativos);
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

    //set list tab item
    $('a#ativo,a#inativo').livequery('click',function(event){
        status = $(this).data('status');
        list(true, search, status);
    });

    //add form
    $('button.btn-add').livequery('click',function(event){
        window.location.href = "/office/questionario/add";
    });

    //check all list
    $('#checkAll').livequery('click',function(event){
        var count = 0;
        $(':checkbox.checkItem').prop('checked', this.checked);    
        var count = 0;
        $('.checkItem:checked').each(function(){
            if(this.checked)
                count++;
        })
        if(count >= 1){
            $('#col-action').removeClass('hidden');
        }else{
            $('#col-action').addClass('hidden');
        }
    });

    //check item
    $('.checkItem').livequery('change',function(event){
        var count = 0;
        $('.checkItem:checked').each(function(){
            if(this.checked)
                count++;
        })
        if(count >= 1){
            $('#col-action').removeClass('hidden');
        }else{
            $('#col-action').addClass('hidden');
        }
    });

    //setStatus
    $('a#setStatus').livequery('click',function(event){
        var status = $(this).data('status');
        var items = [];
        $('.checkItem:checked').each(function(){
            items.push($(this).val());
        });

        //params
        var params = {
            usuarios: items,
            status: status
        }
        params = JSON.stringify(params);

        //status
        app.util.getjson({
            url : "/controller/office/questionario/setstatus",
            method : 'POST',
            contentType : "application/json",
            data: params,
            success: function(response){
                if(response.success){
                    setSession('success', response.success);
                    list();
                    checkSuccess();
                }
            },
            error: onError()
        });
    });

	function onError(response) {
      console.log(response);
    }

    //init
    list();
    checkSuccess();

});