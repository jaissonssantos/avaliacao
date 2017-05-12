//variable global
var usuarios = {};

$(document).ready(function(){

    //validate
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
                minlength: 'Para sua segurança, sua senha foi cadastrada com cinco caracteres'
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
                                html += '<div class="col-md-12">'+
                                            '<div class="form-group">'+
                                                '<h5>'+pergunta.titulo+'</h5>'+
                                                '<input type="text" class="form-control input-lg" id="pergunta'+pergunta.id+'" name="pergunta'+pergunta.id+'">'+
                                            '</div>'+
                                        '</div>';
                            break;
                            case 2:
                                html += '<div class="col-md-12">'+
                                            '<div class="form-group">'+
                                            '<h5>'+pergunta.titulo+'</h5>';
                                for (var x=0;x<pergunta.resposta.length;x++) {
                                    var resposta = pergunta.resposta[x];
                                    html += '<div class="radio-styled radio-danger">'+
                                                '<label>'+
                                                    '<input id="resposta'+resposta.id+'" name="resposta'+pergunta.id+'" type="radio">'+
                                                    '<span>'+resposta.titulo+'</span>'+
                                                '</label>'+
                                            '</div>';
                                }
                                html +=     '</div>'+
                                        '</div>';
                            break;
                            case 3:
                                console.log(pergunta.resposta.length);
                                html += '<div class="col-md-12">'+
                                            '<div class="form-group">'+
                                            '<h5>'+pergunta.titulo+'</h5>';
                                for (var x=0;x<pergunta.resposta.length;x++) {
                                    var resposta = pergunta.resposta[x];
                                    html += '<div class="checkbox-styled checkbox-danger">'+
                                                '<label>'+
                                                    '<input id="resposta'+resposta.id+'" name="resposta'+pergunta.id+'" type="checkbox">'+
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

    //enviar
    $('button#enviar').livequery('click',function(event){
        if($("form#forms").valid()){
            usuarios = {
                email: $('#email').val(),
                senha: $('#senha').val(),
                lembrarme: $('#lembrarme').val()
            };

            $('button#acessar').html('PROCESSANDO...');
            $('button#acessar').prop("disabled",true);

            //params
            var params = {};
            params = JSON.stringify(usuarios);

            app.util.getjson({
                url : "/controller/guest/usuario/login",
                method : 'POST',
                contentType : "application/json",
                data: params,
                success: function(response){
                    if(response.results.id){
                        if(response.results.gestor == 1){
                            window.location.href = "/administrador/dashboard";
                        }else{
                            window.location.href = "/office/dashboard";
                        }
                    }
                },
                error : function(response){
                    response = JSON.parse(response.responseText);
                    $('#errorLogin').removeClass('hidden');
                    $('#errorLogin').find('.alert p').html(response.error);
                    $('button#acessar').html('ACESSAR');
                    $('button#acessar').prop("disabled",false);
                }
            });

        }else{
            $("form#forms").valid();
        }
        return false;
	});

	function onError(response) {
      console.log(response);
    }

    //init
    get();

});