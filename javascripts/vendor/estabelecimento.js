//variable global
var estabelecimentos = enderecos = {};

$(document).ready(function(){

    //mask
    var options = {
            onKeyPress: function (cpf, ev, el, op) {
                var masks = ['000.000.000-000', '00.000.000/0000-00'],
                    mask = (cpf.length > 14) ? masks[1] : masks[0];
                el.mask(mask, op);
            }
    }
    $('#cpfcnpj').mask('000.000.000-000', options);
    $('#telefone').mask('(00) 0000-00009');

    //validate
    $('form#formUsuario').validate({
        rules: {
            nome: { 
                required: true, 
                minlength: 2
            },
            email: {
                required: true, 
                email: true,
                checkemail: true
            },
            senha: { 
                required: true,
                minlength: 5
            },
            confirmasenha: { 
                required: true,
                equalTo: "#senha"
            }
            
        },
        messages: {
            nome: { 
                required: 'Qual o seu nome?', 
                minlength: 'Seu nome tem menos que duas letras?'
            },
            email: { 
                required: 'Preencha seu email', 
                email: 'Ops, tem certeza que é um email válido?',
                checkemail: 'Este endereço de E-mail está em uso, tente outro'
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
            if (element.type === "radio") {
                this.findByName(element.name).addClass(errorClass).removeClass(validClass);
                $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
            } else {
                $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
                $(element).closest('.form-group .input-group .input-group-addon').find('i.fa').remove();
                $(element).closest('.form-group .input-group .input-group-addon').append('<i class="fa fa-times fa-validate form-control-feedback"></i>');
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            if (element.type === "radio") {
                this.findByName(element.name).removeClass(errorClass).addClass(validClass);
            } else {
                $(element).closest('.form-group').removeClass('has-error has-feedback').addClass('has-success has-feedback');
                $(element).closest('.form-group .input-group .input-group-addon').find('i.fa').remove();
                $(element).closest('.form-group .input-group .input-group-addon').append('<i class="fa fa-check fa-validate form-control-feedback"></i>');
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

    $('form#formCadastroEstabelecimento').validate({
        rules: {
            nomefantasia: { 
                required: true, 
                minlength: 3
            },
            cpfcnpj: {
                required: true, 
                checkcpfcnpj: true
            },
            telefone: { 
                required: true
            },
            cep: { 
                required: true
            },
            estado: { 
                required: true
            },
            cidade: { 
                required: true
            },
            endereco: { 
                required: true
            },
            bairro: { 
                required: true
            },
            numero: { 
                required: true
            }
        },
        messages: {
            nomefantasia: { 
                required: 'Qual o nome fantasia do estabelecimento?', 
                minlength: 'O nome fantasia tem menos que três letras?'
            },
            cpfcnpj: { 
                required: 'Preencha seu email', 
                checkcpfcnpj: 'Este CPF/CNPJ está em uso, tente outro'
            },
            telefone: { 
                required: 'Preencha o telefone'
            },
            cep: { 
                required: 'Precisamos do CEP'
            },
            estado: { 
                required: 'Qual estado do seu estabelecimento?'
            },
            cidade: { 
                required: 'Falta pouco, qual cidade do seu estabelecimento?'
            },
            endereco: { 
                required: 'Qual logradouro do seu estabelecimento?'
            },
            bairro: { 
                required: 'Qual logradouro do seu estabelecimento?'
            },
            numero: { 
                required: 'Qual o número?'
            }
        },
        highlight: function (element, errorClass, validClass) {
            if (element.type === "radio") {
                this.findByName(element.name).addClass(errorClass).removeClass(validClass);
                $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
            } else {
                $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
                $(element).closest('.form-group .input-group .input-group-addon').find('i.fa').remove();
                $(element).closest('.form-group .input-group .input-group-addon').append('<i class="fa fa-times fa-validate form-control-feedback"></i>');
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            if (element.type === "radio") {
                this.findByName(element.name).removeClass(errorClass).addClass(validClass);
            } else {
                $(element).closest('.form-group').removeClass('has-error has-feedback').addClass('has-success has-feedback');
                $(element).closest('.form-group .input-group .input-group-addon').find('i.fa').remove();
                $(element).closest('.form-group .input-group .input-group-addon').append('<i class="fa fa-check fa-validate form-control-feedback"></i>');
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

    //estado
    app.util.getjson({
        url : "/controller/guest/estadocidade/getestado",
        method : 'POST',
        contentType : "application/json",
        success: function(response){
        	var options = '<option value="" disabled selected>Estado</option>';
	        for (var i=0;i<response.results.length;i++) {
		        options += '<option value="'+response.results[i].id+'">'+ response.results[i].nome+'</option>';
	    	}
	    	$("#estado").html(options);
        },
        error : onError
    });

    //cidade
    $('select#estado').change(function(){
    	var params = {estado: $(this).val()};
    	params = JSON.stringify(params);
    	var options = '<option value="" disabled selected>Carregando...</option>';
    	$("#cidade").html(options);
    	app.util.getjson({
	        url : "/controller/guest/estadocidade/getcidade",
	        method : 'POST',
	        contentType : "application/json",
	        data : params,
	        success: function(response){
	        	options = undefined;
		        for (var i=0;i<response.results.length;i++) {
			        options += '<option value="'+response.results[i].id+'">'+ response.results[i].nome+'</option>';
		    	}
		    	$("#cidade").html(options);
                if(enderecos){
                    $('select#cidade').find('option').each(function() {
                        if($(this).text().toUpperCase() == enderecos.cidade.toUpperCase()){
                            $(this).attr('selected', true);
                        }
                    });
                }
	        },
	        error : onError
	    });
    });

    //cep
    $('#cep').livequery('keyup',function(event){
        var cep = $(this).val();
        if(cep != undefined && cep.length < 8){
          return;
        }

        app.util.post({
            url : "http://api.postmon.com.br/v1/cep/" + cep,
            type : 'GET',
            success: function(response){
                //set endereços
                enderecos = response;
                $('#endereco').val(response.logradouro);
                $('#bairro').val(response.bairro);
                //estado
                $('select#estado').find('option').each(function() {
                    if($(this).text().toUpperCase() == response.estado_info.nome.toUpperCase()){
                        $(this).attr('selected', true);
                        $('select#estado').trigger("change");
                    }
                });
            },
            error: function(){
                enderecos = {};
            }
        });
    });

    //continuar
    $('button#continuar').livequery('click',function(event){
        if($("form#formUsuario").valid()){
            $('#step-1').addClass('hidden');
            $('#step-2').removeClass('hidden');
        }
        return false;
    });

    //voltar
    $('button#voltar').livequery('click',function(event){
        $('#step-1').removeClass('hidden');
        $('#step-2').addClass('hidden');
        return false;
    });

    //save
    $('button#salvar').livequery('click',function(event){
        if($("form#formCadastroEstabelecimento").valid()){
            estabelecimentos = {
                nome: $('#nome').val(),
                sobrenome: $('#sobrenome').val(),
                email: $('#email').val(),
                senha: $('#senha').val(),
                nomefantasia: $('#nomefantasia').val(),
                cpfcnpj: $('#cpfcnpj').val(),
                telefone: $('#telefone').val(),
                cep: $('#cep').val(),
                estado: $('#estado').val(),
                cidade: $('#cidade').val(),
                endereco: $('#endereco').val(),
                bairro: $('#bairro').val(),
                numero: $('#numero').val(),
                complemento: $('#complemento').val()
            };

            $('button#salvar').html('PROCESSANDO...');
            $('button#salvar').prop("disabled",true);

            //params
            var params = {};
            params = JSON.stringify(estabelecimentos);

            app.util.getjson({
                url : "/controller/guest/estabelecimento/create",
                method : 'POST',
                contentType : "application/json",
                data: params,
                success: function(response){
                    if(response.success)
                        window.location.href = "/dashboard";
                },
                error : function(response){
                    response = JSON.parse(response.responseText);
                    $('#errorCadastro').removeClass('hidden');
                    $('#errorCadastro').find('.alert p').html(response.error);
                    $('button#salvar').html('CADASTRAR');
                    $('button#salvar').prop("disabled",false);
                }
            });

        }else{
            $("form#formCadastroEstabelecimento").valid();
        }
        return false;
	});

	function onError(response) {
      console.log(response);
    }

});