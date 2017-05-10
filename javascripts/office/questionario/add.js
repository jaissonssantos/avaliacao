//variable global
var questionarios = {};

$(document).ready(function(){

    //validate
    $('form#formQuestionario').validate({
        rules: {
            titulo: {
                required: true,
                minlength: 5
            }
        },
        messages: {
            titulo: { 
                required: 'Preencha o título do seu questionário',
                minlength: 'Vamos lá o título deve ter cinco caracteres'
            }
        },
        highlight: function (element, errorClass, validClass) {
            if (element.type === "radio") {
                this.findByName(element.name).addClass(errorClass).removeClass(validClass);
                $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
            } else {
                $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
                $(element).closest('.form-group').find('i.fa').remove();
                $(element).closest('.form-group').append('<i class="fa fa-times fa-validate form-control-feedback fa-absolute"></i>');
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            if (element.type === "radio") {
                this.findByName(element.name).removeClass(errorClass).addClass(validClass);
            } else {
                $(element).closest('.form-group').removeClass('has-error has-feedback').addClass('has-success has-feedback');
                $(element).closest('.form-group').find('i.fa').remove();
                $(element).closest('.form-group').append('<i class="fa fa-check fa-validate form-control-feedback fa-absolute"></i>');
            }
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else if (element.attr("type") == "radio") {
                error.insertAfter(element.parent().parent());
            }else{
                error.insertAfter(element);
            }
        }
    });

    //add
    $('button#add').livequery('click',function(event){
        var pergunta = $('div#pergunta:first').clone();
        $('#perguntas').append(pergunta)

        var count = $('div#pergunta').length;
        var item = $('div#pergunta:last')
        item.attr('data-id',count);
        item.find('.form-group').removeClass('has-success has-feedback');
        item.find('.form-group i').remove();
        item.find('#pergunta').attr('name', 'pergunta'+count);
        item.find('#pergunta').val('');
        item.find('button#pergunta-excluir').removeClass('hidden');
        item.find('button#pergunta-duplicar').removeClass('hidden');
        return false;
    });

    //tipo de pergunta change
    $('select#tipo').livequery('change',function(event){
        var id = $(this).parents('#pergunta').data('id');
        var tipo = parseInt($(this).val());
        var resposta = $(this).parents('#pergunta');
        var html = '';
        resposta.find('#respostas').html('');
        switch(tipo){
            case 1:
                html = '<div id="campo" class="form-group">'+
                            '<input type="text" class="form-control" id="resposta'+id+'" name="resposta'+id+'[]" placeholder="Texto da resposta curta" disabled="true">'+
                        '</div>';
            break;
            case 2:
                html = '<div id="campo" class="m-b-15">'+
                            '<div class="input-group m-b-15">'+
                                '<div class="input-group-addon">'+
                                    '<i class="fa fa-dot-circle-o"></i>'+
                                '</div>'+
                                '<input type="text" class="form-control" id="resposta'+id+'" name="resposta'+id+'[]" placeholder="Opção 1">'+
                            '</div>'+
                        '</div>'+
                        '<div class="m-b-15">'+
                            '<a id="adicionar-radio" href="javascript:void(0);">Adicionar opção</a>'+
                        '</div>';
            break;
            case 3:
                html = '<div id="campo" class="m-b-15">'+
                            '<div class="input-group m-b-15">'+
                                '<div class="input-group-addon">'+
                                    '<i class="fa fa-check-square-o"></i>'+
                                '</div>'+
                                '<input type="text" class="form-control" id="resposta'+id+'" name="resposta'+id+'[]" placeholder="Opção 1">'+
                            '</div>'+
                        '</div>'+
                        '<div class="m-b-15">'+
                            '<a id="adicionar-check" href="javascript:void(0);">Adicionar opção</a>'+
                        '</div>';
            break;
        }
        resposta.find('#respostas').html(html);
    });

    //duplicate
    $('button#pergunta-duplicar').livequery('click',function(event){
        var pergunta = $(this).parents('div#pergunta').clone();
        $('#perguntas').append(pergunta);

        var count = $('div#pergunta').length;
        var item = $('div#pergunta:last')
        item.attr('data-id',count);
        item.find('.form-group').removeClass('has-success has-feedback');
        item.find('.form-group i').remove();
        item.find('#pergunta').attr('name', 'pergunta'+count);
    });

    //add radio
    $('a#adicionar-radio').livequery('click',function(event){
        var id = $(this).parents('#pergunta').data('id');
        var resposta = $(this).parents('#pergunta');
        var count = resposta.find('#respostas input[type="text"]').length;
        var item = '<div class="input-group m-b-15">'+
                        '<div class="input-group-addon">'+
                            '<i class="fa fa-dot-circle-o"></i>'+
                        '</div>'+
                        '<input type="text" class="form-control" id="resposta'+id+'" name="resposta'+id+'[]" placeholder="Opção '+(count+1)+'">'+
                        '<a href="javascript:void(0);" id="btn-op" class="btn-op">'+
                            '<i class="fa fa-times-circle-o"></i>'+
                        '</a>'+
                    '</div>';
        resposta.find('#respostas #campo').append(item);
        return false;
    });

    //add check
    $('a#adicionar-check').livequery('click',function(event){
        var id = $(this).parents('#pergunta').data('id');
        var resposta = $(this).parents('#pergunta');
        var count = resposta.find('#respostas input[type="text"]').length;
        var item = '<div class="input-group m-b-15">'+
                        '<div class="input-group-addon">'+
                            '<i class="fa fa-check-square-o"></i>'+
                        '</div>'+
                        '<input type="text" class="form-control" id="resposta'+id+'" name="resposta'+id+'[]" placeholder="Opção '+(count+1)+'">'+
                        '<a href="javascript:void(0);" id="btn-op" class="btn-op">'+
                            '<i class="fa fa-times-circle-o"></i>'+
                        '</a>'+
                    '</div>';
        resposta.find('#respostas #campo').append(item);
        return false;
    });

    //remove radio
    $('a#btn-op').livequery('click',function(event){
        $(this).parent('.input-group').remove();
        return false;
    });

    //remove pergunta
    $('button#pergunta-excluir').livequery('click',function(event){
        $(this).parents('#pergunta').remove();
    });

    //save
    $('button#salvar').livequery('click',function(event){
        if($("form#formQuestionario").valid()){

            $('button#salvar').html('Processando...');
            $('button#salvar').prop("disabled",true);
            $('button#cancelar').prop("disabled",true);

            app.util.getjson({
                url : "/controller/office/questionario/create",
                method : 'POST',
                data: $("form#formQuestionario").serialize(),
                success: function(response){
                    // if(response.success){
                        // setSession('success', response.success);
                        // window.location.href = "/office/questionario";
                    // }
                    console.log(response.success);
                },
                error : function(response){
                    response = JSON.parse(response.responseText);
                    $('#error').removeClass('hidden');
                    $('#error').find('.alert p').html(response.error);
                    $('button#salvar').html('Salvar');
                    $('button#salvar').prop("disabled",false);
                    $('button#cancelar').prop("disabled",false);
                }
            });
        }else{
            $("form#formQuestionario").valid();
        }
        return false;
    });

    //cancel
    $('button#cancelar').livequery('click',function(event){
        window.location.href = "/office/questionario/";
        return false;
    });

	function onError(response) {
      console.log(response);
    }

});