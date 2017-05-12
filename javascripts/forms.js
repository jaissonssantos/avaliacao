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

    //login
    $('button#acessar').livequery('click',function(event){
        if($("form#formLogin").valid()){
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
            $("form#formLogin").valid();
        }
        return false;
	});

	function onError(response) {
      console.log(response);
    }

});