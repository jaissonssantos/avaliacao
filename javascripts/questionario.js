//variable global
var questionarios = {};
var usuarios = {};

$(document).ready(function(){

    //mask
    $('#telefone').mask('(00) 0000-00009');

    //validate
    $('form#formConta').validate({
        rules: {
            nome: {
                required: true
            },
            email: {
                required: true, 
                email: true,
                checkemail: true
            },
            telefone: {
                required: true,
                checktelefone: true
            },
            senha: { 
                required: true,
                minlength: 5
            },
            confirmasenha: { 
                required: true,
                equalTo: "form#formConta #senha"
            }
        },
        messages: {
            nome: {
                required: 'Qual seu nome?'
            },
            email: { 
                required: 'Preencha seu email', 
                email: 'Ops, tem certeza que é um email válido?',
                checkemail: 'Este endereço de E-mail está em uso, tente outro'
            },
            telefone: {
                required: 'Qual seu número de telefone?',
                checktelefone: 'Este número de telefone está em uso, tente outro'
            },
            senha: { 
                required: 'Preencha sua senha',
                minlength: 'Para sua segurança a senha deve ter no mínimo cinco caracteres'
            },
            confirmasenha: { 
                required: 'Vamos lá, confirme sua senha',
                equalTo: 'Pelo que estou vendo as senhas não coincidem, tente novamente'
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).closest('.input-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
            $(element).closest('.input-group').find('.input-group-addon i.fa').remove();
            $(element).closest('.input-group').find('.input-group-addon').append('<i class="fa fa-times fa-validate"></i>');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('.input-group').removeClass('has-error has-feedback').addClass('has-success has-feedback');
            $(element).closest('.input-group').find('.input-group-addon i.fa').remove();
            $(element).closest('.input-group').find('.input-group-addon').append('<i class="fa fa-check fa-validate"></i>');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            }else{
                error.insertAfter(element);
            }
        }
    });

    $('form#formLogin').validate({
        rules: {
            email: {
                required: true, 
                email: true
            },
            senha: { 
                required: true,
                minlength: 5
            }
        },
        messages: {
            email: { 
                required: 'Preencha seu email', 
                email: 'Ops, tem certeza que é um email válido?'
            },
            senha: { 
                required: 'Preencha sua senha',
                minlength: 'Para sua segurança a senha deve ter no mínimo cinco caracteres'
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).closest('.input-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
            $(element).closest('.input-group').find('.input-group-addon i.fa').remove();
            $(element).closest('.input-group').find('.input-group-addon').append('<i class="fa fa-times fa-validate"></i>');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('.input-group').removeClass('has-error has-feedback').addClass('has-success has-feedback');
            $(element).closest('.input-group').find('.input-group-addon i.fa').remove();
            $(element).closest('.input-group').find('.input-group-addon').append('<i class="fa fa-check fa-validate"></i>');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            }else{
                error.insertAfter(element);
            }
        }
    });

    //check login
    function check(){
        app.util.getjson({
            url : "/controller/guest/cliente/check",
            method : 'POST',
            contentType : "application/json",
            success: function(response){
                if(response.results.id){
                    $('#login').modal('hide');
                    $('#users').removeClass('hidden');
                    $('#users span.name').html(response.results.nome);
                    $('#users span.email').html(response.results.email);
                }
            },
            error : function(response){
                response = JSON.parse(response.responseText);
                if(response.error)
                    $("#login").modal({
                        cache:false,
                        show: true,
                        keyboard: false,
                        backdrop: 'static'
                    });
            }
        });
    }

    function get(){
        var params = {hash: $('#hash').val()};
        params = JSON.stringify(params);

        //list
        app.util.getjson({
            url : "/controller/guest/questionario/get",
            method : 'POST',
            contentType : "application/json",
            data: params,
            success: function(response){
                if(response.id){
                    $('#loading').addClass('hidden');
                    $('#title').removeClass('hidden');
                    $('#items').removeClass('hidden');
                    $('#send').removeClass('hidden');

                    //title
                    $('#title h3').html(response.titulo);
                    $('#title p').html(response.introducao);

                    //items
                    var html = '';
                    for (var i=0;i<response.pergunta.length;i++) {
                        var pergunta = response.pergunta[i];
                        switch(parseInt(pergunta.tipo)){
                            case 1:
                                html += '<input type="hidden" name="pergunta[]" value="'+pergunta.id+'">';
                                html += '<input type="hidden" name="tipo[]" value="'+pergunta.tipo+'">';
                                html += '<div class="col-md-12">'+
                                            '<div class="form-group">'+
                                                '<h5>'+pergunta.titulo+'</h5>'+
                                                '<input type="text" class="form-control input-lg" id="resposta'+pergunta.id+'" name="resposta'+pergunta.id+'">'+
                                            '</div>'+
                                        '</div>';
                            break;
                            case 2:
                                html += '<div class="col-md-12">'+
                                            '<div class="form-group">'+
                                            '<h5>'+pergunta.titulo+'</h5>';
                                html += '<input type="hidden" name="pergunta[]" value="'+pergunta.id+'">';
                                html += '<input type="hidden" name="tipo[]" value="'+pergunta.tipo+'">';
                                for (var x=0;x<pergunta.resposta.length;x++) {
                                    var resposta = pergunta.resposta[x];
                                    html += '<div class="radio-styled radio-danger">'+
                                                '<label>'+
                                                    '<input id="resposta'+pergunta.id+'" name="resposta'+pergunta.id+'" type="radio" value="'+resposta.id+'">'+
                                                    '<span>'+resposta.titulo+'</span>'+
                                                '</label>'+
                                            '</div>';
                                }
                                html +=     '</div>'+
                                        '</div>';
                            break;
                            case 3:
                                html += '<div class="col-md-12">'+
                                            '<div class="form-group">'+
                                            '<h5>'+pergunta.titulo+'</h5>';
                                html += '<input type="hidden" name="pergunta[]" value="'+pergunta.id+'">';
                                html += '<input type="hidden" name="tipo[]" value="'+pergunta.tipo+'">';
                                for (var x=0;x<pergunta.resposta.length;x++) {
                                    var resposta = pergunta.resposta[x];
                                    html += '<div class="checkbox-styled checkbox-danger">'+
                                                '<label>'+
                                                    '<input id="resposta'+pergunta.id+'" name="resposta'+pergunta.id+'[]" type="checkbox" value="'+resposta.id+'">'+
                                                    '<span>'+resposta.titulo+'</span>'+
                                                '</label>'+
                                            '</div>';
                                }
                                html +=     '</div>'+
                                        '</div>';
                            break;
                        }
                    }

                    $('#items').html(html);

                }else{
                    window.location.href = "/404";
                }
            },
            error : onError
        });
    }

    $('button#criarconta').livequery('click',function(event){
        if($("form#formConta").valid()){
            usuarios = {
                nome: $('form#formConta #nome').val(),
                email: $('form#formConta #email').val(),
                telefone: $('form#formConta #telefone').val(),
                senha: $('form#formConta #senha').val()
            };

            $('button#criarconta').html('PROCESSANDO...');
            $('button#criarconta').prop("disabled",true);

            //params
            var params = {};
            params = JSON.stringify(usuarios);

            app.util.getjson({
                url : "/controller/guest/cliente/create",
                method : 'POST',
                contentType : "application/json",
                data: params,
                success: function(response){
                    if(response.results.id){
                        $('#login').modal('hide');
                        $('#users').removeClass('hidden');
                        $('#users span.name').html(response.results.nome);
                        $('#users span.email').html(response.results.email);
                    }
                },
                error : function(response){
                    response = JSON.parse(response.responseText);
                    $('#errorModal').removeClass('hidden');
                    $('#errorModal').find('.alert p').html(response.error);
                    $('button#criarconta').html('CRIAR CONTA');
                    $('button#criarconta').prop("disabled",false);
                }
            });
        }else{
            $("form#formConta").valid()
        }
        return false;
    });
    
    $('a#entrar').livequery('click',function(event){
        $('form#formConta').addClass('hidden');
        $('form#formLogin').removeClass('hidden');
        return false;
    });

    $('button#acessar').livequery('click',function(event){
        if($("form#formLogin").valid()){
            usuarios = {
                email: $('form#formLogin #email').val(),
                senha: $('form#formLogin #senha').val()
            };

            $('button#acessar').html('PROCESSANDO...');
            $('button#acessar').prop("disabled",true);

            //params
            var params = {};
            params = JSON.stringify(usuarios);

            app.util.getjson({
                url : "/controller/guest/cliente/login",
                method : 'POST',
                contentType : "application/json",
                data: params,
                success: function(response){
                    if(response.results.id){
                        $('#login').modal('hide');
                        $('#users').removeClass('hidden');
                        $('#users span.name').html(response.results.nome);
                        $('#users span.email').html(response.results.email);
                    }
                },
                error : function(response){
                    response = JSON.parse(response.responseText);
                    $('#errorModal').removeClass('hidden');
                    $('#errorModal').find('.alert p').html(response.error);
                    $('button#acessar').html('ACESSAR');
                    $('button#acessar').prop("disabled",false);
                }
            });
        }else{
            $("form#formLogin").valid()
        }
    });

    $('a#voltar').livequery('click',function(event){
        $('form#formConta').removeClass('hidden');
        $('form#formLogin').addClass('hidden');
        return false;
    });
    
    $('a#recuperar').livequery('click',function(event){
        return false;
    });

    //enviar
    $('button#enviar').livequery('click',function(event){
        if($("form#forms").valid()){

            $('button#enviar').html('PROCESSANDO...');
            $('button#enviar').prop("disabled",true);

            app.util.getjson({
                url : "/controller/guest/questionario/save",
                method : 'POST',
                data: $("form#forms").serialize(),
                success: function(response){
                    if(response.success){
                        $('#success').removeClass('hidden');
                        $('#title').addClass('hidden');
                        $('#items').addClass('hidden');
                        $('#send').addClass('hidden');
                    }
                },
                error : function(response){
                    response = JSON.parse(response.responseText);
                    $('#error').removeClass('hidden');
                    $('#error').find('.alert p').html(response.error);
                    $('button#enviar').html('ACESSAR');
                    $('button#enviar').prop("disabled",false);
                }
            });

        }else{
            $("form#forms").valid();
        }
        return false;
	});

    //sair
    $('a#sair').livequery('click',function(event){
        app.util.getjson({
            url : "/controller/guest/cliente/logout",
            method : 'POST',
            success: function(response){
                if(response.success)
                    window.location.href = '/';
            },
            error : onError
        });
        return false;
    });

	function onError(response) {
      console.log(response);
    }

    //init
    check();
    get();

});