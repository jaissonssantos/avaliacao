$(document).ready(function(){

    //validate
    $('form#formMudarsenha').validate({
        rules: {
            senha: { 
                required: true,
                minlength: 5
            },
            confirmasenha: { 
                required: true,
                equalTo: "form#formMudarsenha #senha"
            }
        },
        messages: {
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
            $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
            $(element).closest('.form-group').find('.input-group-addon i.fa').remove();
            $(element).closest('.form-group').find('.input-group-addon').append('<i class="fa fa-times fa-validate"></i>');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass('has-error has-feedback').addClass('has-success has-feedback');
            $(element).closest('.form-group').find('.input-group-addon i.fa').remove();
            $(element).closest('.form-group').find('.input-group-addon').append('<i class="fa fa-check fa-validate"></i>');
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

    $('button#salvarMudarsenha').livequery('click',function(event){
        if($("form#formMudarsenha").valid()){
            usuarios = {
                senha: $('form#formMudarsenha input#senha').val()
            };

            $('button#salvarMudarsenha').html('PROCESSANDO...');
            $('button#salvarMudarsenha').prop("disabled",true);

            //params
            var params = {};
            params = JSON.stringify(usuarios);

            app.util.getjson({
                url : "/controller/administrador/usuario/password",
                method : 'POST',
                contentType : "application/json",
                data: params,
                success: function(response){
                    if(response.success){
                        $('#successModal').removeClass('hidden');
                        $('#successModal').find('.alert p').html(response.success);
                        $('form#formMudarsenha').addClass('hidden');
                        $('button#salvarMudarsenha').addClass('hidden');
                    }
                },
                error : function(response){
                    response = JSON.parse(response.responseText);
                    $('#errorModal').removeClass('hidden');
                    $('#errorModal').find('.alert p').html(response.error);
                    $('button#salvarMudarsenha').html('Salvar');
                    $('button#salvarMudarsenha').prop("disabled",false);
                }
            });
        }else{
            $("form#formMudarsenha").valid()
        }
        return false;
    });

    function onError(response) {
      console.log(response);
    }

});