//variable global
var usuarios = {};

$(document).ready(function(){

    //validate
    $('form#formUsuario').validate({
        rules: {
            nome: {
                required: true,
                minlength: 5
            },
            email: {
                required: true, 
                email: true
            },
            perfil: { 
                required: true
            } 
        },
        messages: {
            nome: { 
                required: 'Preencha o nome de usuário',
                minlength: 'Vamos lá o nome de usuário deve ter cinco caracteres'
            },
            email: { 
                required: 'Preencha seu email', 
                email: 'Ops, tem certeza que é um email válido?'
            },
            perfil: { 
                required: 'Escolha o nível de acesso do usuário'
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

    function get(){
        var params = {id: $('#id').val()};
        params = JSON.stringify(params);

        //list
        app.util.getjson({
            url : "/controller/office/usuario/get",
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

    //edit
    $('button#salvar').livequery('click',function(event){
        if($("form#formUsuario").valid()){
            usuarios = {
                id: $('#id').val(),
                nome: $('#nome').val(),
                sobrenome: $('#sobrenome').val(),
                email: $('#email').val(),
                perfil: $('#perfil').val()
            };

            //params
            var params = {};
            params = JSON.stringify(usuarios);

            $('button#salvar').html('Processando...');
            $('button#salvar').prop("disabled",true);
            $('button#cancelar').prop("disabled",true);

            app.util.getjson({
                url : "/controller/office/usuario/update",
                method : 'POST',
                contentType : "application/json",
                data: params,
                success: function(response){
                    if(response.success){
                        setSession('success', response.success);
                        window.location.href = "/office/usuario";
                    }
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
            $("form#formUsuario").valid();
        }
        return false;
    });

    //cancel
    $('button#cancelar').livequery('click',function(event){
        window.location.href = "/office/usuario/";
        return false;
    });

	function onError(response) {
      console.log(response);
    }

    //init
    get();

});