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
                equalTo: "form#formConta #senha"
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

    $('button#salvarMudarsenha').livequery('click',function(event){
        if($("form#formMudarsenha").valid()){

        }else{
            $("form#formMudarsenha").valid()
        }
        return false;
    });

    function onError(response) {
      console.log(response);
    }

});