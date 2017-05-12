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
                    $('#editName').html(response.nome + ' ' + response.sobrenome);
                    $('#nome').val(response.nome);
                    $('#sobrenome').val(response.sobrenome);
                    $('#email').val(response.email);
                    $( "#perfil option" ).each(function(){
                        if($(this).val() == response.perfil)
                            $(this).attr('selected', true);
                    });
                    $('#form-loading').addClass('hidden');
                    $('#form').removeClass('hidden');
                }else{
                    window.location.href = "/office/404";
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